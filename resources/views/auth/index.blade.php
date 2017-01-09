@extends('layouts.app-no-nav')

@section('body-class') login @endsection

@section('content')
<div>
  <div class="login_wrapper">
    <div class="animate form login_form">
      <section class="login_content">
        <form role="form" method="POST" action="{{ route('post-username') }}">
          {{ csrf_field() }}

          <h1>Login Form</h1>
          <div>
            <input type="text" class="form-control" placeholder="Email" required="" name="username"/>
            @if( $errors->has('username') )
              <span class="help-block">
                <strong>{{ $errors->first('username') }}</strong>
              </span>
            @endif
          </div>
          <div>
            <button class="btn btn-default submit" type="submit">Log in</button>
          </div>

          <div class="clearfix"></div>

          <div class="separator">
            @include('auth.partials._auth-copyright')
          </div>
        </form>
      </section>
    </div>
  </div>
</div>
@endsection
