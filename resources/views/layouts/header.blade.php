<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | GraniteCMS</title>

    <!-- Bootstrap -->
    <link href="{{ secure_asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ secure_asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ secure_asset('vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{ secure_asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="{{ secure_asset('css/maps/jquery-jvectormap-2.0.3.css')}}" rel="stylesheet"/>

    <!-- bootstrap-wysiwyg -->
    <link href="{{ secure_asset('vendors/google-code-prettify/bin/prettify.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ secure_asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link href="{{ secure_asset('vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <!-- starrr -->
    <link href="{{ secure_asset('vendors/starrr/dist/starrr.css') }}" rel="stylesheet">

    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    @yield('extra-css')

    <!-- Custom Theme Style -->
    <link href="{{ secure_asset('css/cms.css') }}" rel="stylesheet">



    <!-- Critical JS -->
    <script type="text/javascript">
        function addLoadEvent(func){
            var oldonload = window.onload;
            if( typeof window.onload != 'function' ){
                window.onload = func;
            } else{
                window.onload = function(){
                    if(oldonload){
                        oldonload();
                    }
                    func();
                }
            }
        }
    </script>
  </head>

<body class="nav-md @yield('body-class')">
