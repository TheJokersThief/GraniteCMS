<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>@yield('title')</title> 
	<meta name="Description" content="@yield('description')" /> 
	<meta name="Keywords" content="@yield('keywords')" /> 
	<link rel="shortcut icon" href="favicon.ico?ver=2" type="image/x-icon" /> 

	<link href="{{ siteAsset('css/main.css') }}" rel="stylesheet" type="text/css" />

	@yield('extra-css')

	<link rel="stylesheet" type="text/css" href="{{ siteAsset('css/slick/slick.css') }}"/>
	

	<script src="{{ siteAsset('js/jquery/jquery-1.11.2.min.js') }}"></script>

</head>
<body id="@yield('page-id')">