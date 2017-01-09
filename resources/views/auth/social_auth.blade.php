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
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'facebook']) }}">
          <i class="fa fa-facebook fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('twitter', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'twitter']) }}">
          <i class="fa fa-twitter fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('linkedin', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'linkedin']) }}">
          <i class="fa fa-linkedin fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('google', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'google']) }}">
          <i class="fa fa-google fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('github', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'github']) }}">
          <i class="fa fa-github fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('bitbucket', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'bitbucket']) }}">
          <i class="fa fa-bitbucket fa-3x"></i>
        </a>
      </div>
    @endif

    @if( !isset($cookie) || !in_array('local', $cookie))
      <div class="col-sm-2">
        <a href="{{ route('social-auth', ['provider' => 'local']) }}">
          <i class="fa fa-user fa-3x"></i>
        </a>
      </div>
    @endif
  </div>
</div>
@endsection
