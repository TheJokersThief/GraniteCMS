<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	<title>@yield('title') | GraniteCMS</title> 
	<meta name="Description" content="@yield('description')" /> 
	<meta name="Keywords" content="@yield('keywords')" /> 
	<link rel="shortcut icon" href="favicon.ico?ver=2" type="image/x-icon" /> 

	<link href="{{ siteAsset('css/main.css') }}" rel="stylesheet" type="text/css" />

	@yield('extra-css')

	<link rel="stylesheet" type="text/css" href="{{ siteAsset('css/slick/slick.css') }}"/>
	

	<script src="{{ siteAsset('js/jquery/jquery-1.11.2.min.js') }}"></script>

</head>
<body id="@yield('page-id')">

@include('granitecms_dev.theme.views.partials.cookie-policy')
	<div id="wrap">
		<!-- ********** HEADER ********** -->
		<div class="container header">
			<div class="row">
				<div class="col span4">
					<h1><a href="/" title="DefaultSite">DefaultSite</a></h1>
				</div><!--/col-->
				<div class="col span8">
					<div class="contact">
						<span><i class="fa fa-map-marker"></i><a href="/" class="" id="" title="GRANITE, Address, Address"   >GRANITE, Address, Address</a></span>
						<span><i class="fa fa-phone"></i><a href="tel:+353212427890">+353 21 242 7890</a></span>
						<span><i class="fa fa-envelope"></i><a href="mailto:infoto@default.ie">infoto@default.ie</a></span>
					</div>
				</div><!--/col-->
				<div class="mainNav col span8">
					@include('granitecms_dev.theme.views.partials.main-menu')
				</div><!--/col-->
			</div><!--/row-->
		</div><!--/header-->