<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>@yield('title', 'GraniteCMS')</title> 
	<meta name="Description" content="@yield('description')" /> 
	<meta name="Keywords" content="@yield('keywords')" /> 
	<link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon" /> 

	<link href="{{ siteAsset('css/main.css') }}" rel="stylesheet" type="text/css" />

	@yield('extra-css')

	<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
	<script src="https://unpkg.com/vue@2.3.3"></script>

</head>
<body id="@yield('page-id')">