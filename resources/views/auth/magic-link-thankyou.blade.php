@extends('layouts.app-no-nav')

@section('body-class') login social-login @endsection

@section('content')
<div>
  <div class="login_wrapper">
    <h1>Login</h1>

    <p>Your magic link has been sent to your email. Please check your email and click the link to verify it's you.</p>

    <p><em>(The link will expire after a maximum of 10 minutes)</em></p>

    <p><a href="{{ route('auth-login') }}">Click Here To Return To Social Options</a></p>

  </div>
</div>
@endsection
