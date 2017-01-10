@extends('layouts.app-no-nav')

@section('body-class') login social-login @endsection

@section('content')
<div>
  <div class="login_wrapper">
    <h1>Login</h1>

    @if(($cookie = request()->cookie('providers')) != null )

      <span class="help-block danger">
        You're already logged in by:
        @foreach($cookie as $provider)
          {{ $provider }} &nbsp;
        @endforeach
      </span>
    @endif

    @foreach ($errors->all() as $error)
      <span class="help-block danger">{{ $error }}</span>
    @endforeach
    <br />

    @if( !isset($cookie) || !in_array('facebook', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'facebook']) }}" class="facebook">
          <i class="fa fa-facebook fa-3x"></i>
          <span>Facebook</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('twitter', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'twitter']) }}" class="twitter">
          <i class="fa fa-twitter fa-3x"></i>
          <span>Twitter</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('linkedin', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'linkedin']) }}" class="linkedin">
          <i class="fa fa-linkedin fa-3x"></i>
          <span>Linkedin</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('google', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'google']) }}" class="google">
          <i class="fa fa-google fa-3x"></i>
          <span>Google</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('github', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'github']) }}" class="github">
          <i class="fa fa-github fa-3x"></i>
          <span>Github</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('bitbucket', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'bitbucket']) }}" class="bitbucket">
          <i class="fa fa-bitbucket fa-3x"></i>
          <span>Bitbucket</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('local', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'local']) }}" class="local">
          <i class="fa fa-user fa-3x"></i>
          <span>Local</span>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('magic-link', $cookie))
      <div class="col-sm-3">
        <a href="{{ route('social-auth', ['provider' => 'magic-link']) }}" class="magic-link">
          <i class="fa fa-envelope fa-3x"></i>
          <span>Magic Link</span>
        </a>
      </div>
    @endif
  </div>
</div>
@endsection
