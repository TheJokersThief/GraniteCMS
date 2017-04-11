<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Nonce;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use App\User;
use App\UserSocial;
use Auth;
use DB;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mail;
use QrCode;
use Socialite;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------
    |   Security Note
    |--------------------------------------------------------
    |   The cookie used to store the counter is of the form
    |   {
    |       counter,            // Our date
    |       user,               // Verifies the user trying to login
    |                           //  (but does not provide the identity)
    |       nonce               // Nonces expire after 10 minutes
    |                           //  this allows us to test for freshness
    |   }
    |
    |   ~~~~ Cookies are also encrypted by default by Laravel ~~~~

     */

    private $counter_cookie_name = "auth_counter";
    private $username_cookie_name = "identification";
    private $username_column = "user_email";

    private $cookie_lifetime = 10; // In minutes
    private $required_logins = 2; // Number of accounts that need to be authenticated to login

    // Third-party providers we can auth with
    private $acceptable_providers = [
        'facebook',
        'twitter',
        'linkedin',
        'google',
        'github',
        'bitbucket',
        'fingerprint',
    ];

    private $error_messages = [
        'already_used' => 'You\'ve already used that form of authentication',
        'incorrect_password' => 'I\'m sorry, those details are incorrect',
        'malformed_cookie' => 'We detected intereferen ce with our login system',
        'no_data_supplied' => 'No data supplied with the request.',
        'no_provider' => 'That provider doesn\'nt exist',
        'no_social_link' => 'That account is not linked to the user provided',
        'no_username' => 'Please provide a username',
        'please_login' => 'Please login',
        'incorrect_nonce' => 'The code is incorrect or outdated. Please try another.',
    ];

    /**
     * View for handling username form
     * @param  Request $request
     */
    public function index(Request $request)
    {
        if ($request->cookie($this->username_cookie_name) != null) {
            return redirect()->route('auth-login');
        } else {
            return view('auth.index');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth');
    }

    /**
     * Validate username and setup cookie
     * @param  Request $request
     */
    public function postUsername(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|email',
        ]);

        $fingerprint = hash("sha256", $request->input('user_fingerprint'));
        if (env('ENABLE_FINGERPRINT') && $fingerprint != '') {
            $user = User::where('fingerprint', $fingerprint)
                ->where('user_email', $request->input('username'))
                ->first();

            if ($user != null) {
                // If supplied username and fingerprint match, only require one form of auth
                return $this->confirmAuth($request, $user, 'fingerprint')
                    ->cookie($this->username_cookie_name, $request->input('username'), $this->cookie_lifetime * 2);
            }
        }

        return response()
            ->view('auth.social_auth')
            ->cookie($this->username_cookie_name, $request->input('username'), $this->cookie_lifetime * 2);
    }

    /**
     * Receive, verify and handle magic link nonce
     * @param  Request $request
     * @param  string  $code    Nonce
     */
    public function magicLinkVerification(Request $request, $code)
    {
        // Get username from encrypted cookie
        $username = $request->cookie($this->username_cookie_name);
        if ($username == null) {
            return redirect()
                ->route('auth')
                ->withErrors(['message' => $this->error_messages['no_username']]);
        }

        $nonce = $code;

        $user = User::where($this->username_column, $username)->first();

        if ($user != null) {
            $verification_nonce = UserSocial::where('user_id', $user->id)->where('provider', 'magic-link')->first();
            if ($verification_nonce != null) {
                if ($nonce == $verification_nonce->social_id
                    && Nonce::checkNonce($nonce)) {
                    $verification_nonce->delete();
                    return $this->confirmAuth($request, $user, 'magic-link');
                }
            } else {
                return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['incorrect_password']]);
            }
        } else {
            return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['no_username']]);
        }

    }

    /**
     * View for jump-off to authentication points
     * @param  Request $request
     */
    public function socialAuth(Request $request)
    {
        if ($request->cookie($this->username_cookie_name) != null) {
            return view('auth.social_auth');
        } else {
            return redirect()->route('auth');
        }
    }

    /**
     * Redirect the user to the provider's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request, $provider)
    {
        if (in_array($provider, $this->acceptable_providers)) {

            switch ($provider) {
                case 'facebook':
                    return Socialite::driver($provider)
                        ->with(['app_id' => env('FACEBOOK_CLIENT_id')])
                        ->redirect();
                    break;

                default:
                    return Socialite::driver($provider)->redirect();
                    break;
            }
        } else if ($provider == 'local') {
            return view('auth.login');

        } else if ($provider == 'magic-link') {
            $username = $request->cookie($this->username_cookie_name);
            if ($username == null) {
                return redirect()
                    ->route('auth')
                    ->withErrors(['message' => $this->error_messages['no_username']]);
            }
            return $this->sendMagicLink($username);
        } else {
            return back();
        }
    }

    /**
     * Forward authentication data to relevant site
     * @param  Request $request
     * @param  string  $site     Domain of site to forward to
     * @param  string  $provider Provider used to authenticate
     */
    public function forwardCallback(Request $request, $site, $provider)
    {
        // use the nonce as a key to encrypt the value
        // and then send the nonce's id along with the
        // encrypted values
        $nonce = Nonce::getNonce(32);
        $encryptor = new Encrypter($nonce, 'AES-256-CBC');
        $encrypted_response = $encryptor->encrypt($request->input());

        $nonce = DB::table('nonces')->where('nonce', $nonce)->first();
        $payload = [
            'nonce' => $nonce->id,
            'data' => $encrypted_response,
        ];
        $response = new RedirectResponse("HTTPS://" . $site . "/auth/provider/callback/" . $provider . "?data=" . json_encode($payload));
        return $response;
    }

    /**
     * Forward social data to relevant site
     * @param  Request $request
     * @param  string  $site     Domain of site to forward to
     * @param  string  $provider Provider used to authenticate
     */
    public function forwardAddCallback(Request $request, $site, $provider)
    {
        // use the nonce as a key to encrypt the value
        // and then send the nonce's id along with the
        // encrypted values
        $nonce = Nonce::getNonce(32);
        $encryptor = new Encrypter($nonce, 'AES-256-CBC');
        $encrypted_response = $encryptor->encrypt($request->input());

        $nonce = DB::table('nonces')->where('nonce', $nonce)->first();
        $payload = [
            'nonce' => $nonce->id,
            'data' => $encrypted_response,
        ];
        $response = new RedirectResponse("HTTPS://" . $site . "/auth/provider/callback/" . $provider . "/add?data=" . json_encode($payload));
        return $response;
    }

    public function handleAddProviderCallback(Request $request, $provider)
    {
        if (Auth::check()) {
            // Get forwarded data from request
            $data = json_decode($request->input('data'));
            if ($data == null) {
                return back()->withErrors(['message' => $this->error_messages['no_data_supplied']]);
            }

            $nonce = DB::table('nonces')
                ->where('id', $data->nonce)
                ->first();

            // Decrypted our forwarded data using the corresponding nonce
            $encryptor = new Encrypter($nonce->nonce, 'AES-256-CBC');
            $data = $encryptor->decrypt($data->data);

            // Retrieve the social ID and convert it to a user
            if (method_exists(Socialite::driver($provider), 'getAccessTokenResponse')) {
                // OAuth 2 services
                $token = Socialite::driver($provider)->getAccessTokenResponse($data['code']);
                $user = Socialite::driver($provider)->userFromToken($token['access_token']);
            } else {
                // OAuth 2 services because this is a bitch
                $request->request->add($data);
                $user = Socialite::driver($provider)->user();
            }

            $user_id = Auth::id();

            UserSocial::create([
                'user_id' => $user_id,
                'social_id' => $user->id,
                'provider' => $provider,
                'site' => SiteController::getSiteID(SiteController::getSite()),
            ]);

            return redirect()->route('cms-account');
        } else {
            return redirect()->route('auth')->withErrors(['message' => $this->error_messages['please_login']]);
        }

        dd(Auth::check());
    }

    /**
     * Process social auth callback and verify the user is who they say they are
     * @param  Request $request
     * @param  string  $provider Social auth provider (EG: facebook)
     */
    public function handleProviderCallback(Request $request, $provider)
    {

        // Get username from encrypted cookie
        $username = $request->cookie($this->username_cookie_name);
        if ($username == null) {
            return redirect()
                ->route('auth')
                ->withErrors(['message' => $this->error_messages['no_username']]);
        }

        if (in_array($provider, $this->acceptable_providers)) {
            // Get forwarded data from request
            $data = json_decode($request->input('data'));
            if ($data == null) {
                return response($this->error_messages['no_data_supplied']);
            }

            $nonce = DB::table('nonces')
                ->where('id', $data->nonce)
                ->first();

            // Decrypted our forwarded data using the corresponding nonce
            $encryptor = new Encrypter($nonce->nonce, 'AES-256-CBC');
            $data = $encryptor->decrypt($data->data);

            // Retrieve the social ID and convert it to a user
            if (method_exists(Socialite::driver($provider), 'getAccessTokenResponse')) {
                // OAuth 2 services
                $token = Socialite::driver($provider)->getAccessTokenResponse($data['code']);
                $user = Socialite::driver($provider)->userFromToken($token['access_token']);
            } else {
                // OAuth 1 services because this is a bitch
                $request->request->add($data);
                $user = Socialite::driver($provider)->user();
            }

            $social = UserSocial::getSocialID($user->id, $provider)->first();
            if ($social == null) {
                // If no social ID is found, that's a problem
                return view('auth.social_auth')
                    ->withErrors(['message' => $this->error_messages['no_social_link']]);
            }
            $user = User::find($social->user_id);
            $username_column = $this->username_column;

            // Ensure both usernames match
            if ($user->$username_column != $username) {
                return view('auth.social_auth')->withErrors(['message' => $this->error_messages['no_social_link']]);
            }
        } else if ($provider == 'local') {
            // Local auth is username/password
            $result = $this->manualAuth($request);
            if ($result) {
                $user = User::where($this->username_column, $username)->first();
            } else {
                return view('auth.login')->withInput()
                    ->withErrors(['message' => $this->error_messages['incorrect_password']]);
            }

        } else if ($provider == 'magic-link') {

        } else {
            return view('auth.social_auth')
                ->withErrors(['message' => $this->error_messages['no_provider']]);
        }

        return $this->confirmAuth($request, $user, $provider);

    }

    public function confirmAuth(Request $request, $user, $provider)
    {
        // Initialise first-time cookie
        if ($request->cookie($this->counter_cookie_name) == null) {
            $cookie = $this->cookieBlueprint(0, $user);
        } else {
            $cookie = json_decode($request->cookie($this->counter_cookie_name), true);
        }

        if (!in_array($provider, $cookie['providers'])) {
            if ($this->verifyCookie($cookie, $user)) {

                $count = $cookie['counter'] + 1;

                if ($count < $this->required_logins) {
                    $cookie['providers'][] = $provider;
                    // Renew cookie with new counter
                    return redirect()
                        ->route('auth-login')
                        ->cookie($this->makeCookie($count, $user, $cookie['providers']))
                        ->cookie('providers', $cookie['providers'], $this->cookie_lifetime);
                } else {
                    // If they've reached the required login level, log them in
                    Auth::login($user);
                    // We're finisehd with all the cookies now
                    $this->removeCookies($request);
                    return redirect('cms');
                }
            } else {
                return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['malformed_cookie']]);
            }
        } else {
            return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['already_used']]);
        }
    }

    public function mobileLogin(Request $request, $data)
    {
        $data = json_decode(decrypt($data));
        $id = decrypt($data->encrypted_id);
        if (Nonce::checkNonce($data->nonce)) {
            Auth::loginUsingId($id, true);
            return redirect()->route('cms-account');
        } else {
            return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['incorrect_nonce']]);
        }
    }

    public static function mobileLoginQRCode($size = 100)
    {
        $id = Auth::id();
        $nonce = Nonce::getNonce(42);
        $encrypted_id = encrypt($id);

        $data = [
            'encrypted_id' => $encrypted_id,
            'nonce' => $nonce,
        ];

        $url = route('mobile-login', ['data' => encrypt(json_encode($data))]);
        return QrCode::size($size)->generate($url);
    }

    /**
     * Creates a secure cookie
     * @param  Request  $request
     * @param  int      $counter
     * @param  User     $user
     * @return Request
     */
    private function makeCookie($counter, $user, $providers = [])
    {
        $data = $this->cookieBlueprint($counter, $user, $providers);

        return cookie(
            $this->counter_cookie_name,
            json_encode($data),
            $this->cookie_lifetime
        );
    }

    /**
     * Send a nonce as a verification link to the user's email
     * @param  string $username
     */
    private function sendMagicLink($username)
    {

        $nonce = Nonce::getNonce(200);
        $user = User::where($this->username_column, $username)->first();

        if ($user != null) {
            UserSocial::create([
                'provider' => 'magic-link',
                'user_id' => $user->id,
                'social_id' => $nonce,
                'site' => SiteController::getSiteID(SiteController::getSite()),
            ]);

            Mail::to($user->user_email)->send(new \App\Emails\MagicLink($nonce));

            return view('auth.magic-link-thankyou');
        } else {
            return redirect()->route('auth-login')->withErrors(['message' => $this->error_messages['no_username']]);
        }
    }

    /**
     * Provides a skeleton for the counter cookie
     * @param  int      $counter   Number of successful auths
     * @param  User     $user      The user being authenticated
     * @param  array    $providers All providers successfully authed with
     * @return array
     */
    private function cookieBlueprint($counter, $user, $providers = [])
    {
        return [
            'counter' => $counter,
            'user' => $user->id,
            'nonce' => Nonce::getNonce(),
            'providers' => $providers,
        ];
    }

    /**
     * Deletes all relevant cookies to the authentication process
     * @param  Request $request
     */
    private function removeCookies(Request $request)
    {
        \Cookie::queue(\Cookie::forget($this->counter_cookie_name));
        \Cookie::queue(\Cookie::forget($this->username_cookie_name));
        \Cookie::queue(\Cookie::forget('providers'));
    }

    /**
     * Verify the cookie using its nonce
     * @param  array    $cookie
     * @param  User     $user
     * @return boolean
     */
    private function verifyCookie($cookie, $user)
    {
        $cookie_user = $cookie['user'];
        $cookie_nonce = $cookie['nonce'];

        if ($user->id == $cookie_user
            && Nonce::checkNonce($cookie_nonce)) {
            // If ids match and nonce is valid/fresh
            return true;
        }

        return false;
    }

    /**
     * Handle good old-fashioned username/password authentication
     * @param  Request $request
     * @return boolean
     */
    private function manualAuth(Request $request)
    {
        $email = $request->input('user_email');
        $password = $request->input('password');

        if ($email != null && $password != null) {
            if (Auth::attempt(['user_email' => $email, 'password' => $password], true)) {
                return true;
            }
        }

        return false;
    }
}
