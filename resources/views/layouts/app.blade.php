@include('layouts.header')
@include('layouts.navigation')

@if (count($errors) > 0)
    <div class="alert alert-danger col-lg-offset-2 col-lg-10 col-xs-12">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@yield('content')

@include('layouts.footer')