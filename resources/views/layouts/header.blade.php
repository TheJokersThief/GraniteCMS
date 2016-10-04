<!DOCTYPE html>
<html>
<head>
	<title>@yield('title', env('SITE_TITLE'))</title>
	<meta charset="utf-8">
	<meta name="description" content="@yield('description')" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="shortcut icon" href="{{ URL::to('/') }}/images/favicon.png">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="assets/slick/slick.css"/>

	<link rel="stylesheet" type="text/css" href="{{ URL::to('/') }}/css/app.css">
	@yield('extra-css')

	@yield('extra-head')
</head>
<body class="@yield('body-class')">
	
	<div id="wrap">
		<!-- ********** HEADER ********** -->
		<div class="container header">
			<div class="row">
				<div class="col span4">
					<h1><a href="<cfoutput>#APPLICATION.siteUrl#</cfoutput>" title="<cfoutput>#APPLICATION.siteName#</cfoutput>"><cfoutput>#APPLICATION.siteName#</cfoutput></a></h1>
				</div><!--/col-->
				<div class="col span8">
					<div class="contact">
						<span><i class="fa fa-map-marker"></i><cfoutput>#APPLICATION.siteEngine.getUrlContent('companyAddress')#</cfoutput></span>
						<span><i class="fa fa-phone"></i><a href="tel:<cfoutput>#replaceNoCase(APPLICATION.siteEngine.getContent(argKey = 'companyPrimaryNumber', argShowPos = 'No'),' ','','ALL')#</cfoutput>"><cfoutput>#APPLICATION.siteEngine.getContent('companyPrimaryNumber')#</cfoutput></a></span>
						<span><i class="fa fa-envelope"></i><a href="mailto:<cfoutput>#APPLICATION.siteEngine.getContent(argKey = 'companyPrimaryEmailAddress', argShowPos = 'No')#</cfoutput>"><cfoutput>#APPLICATION.siteEngine.getContent('companyPrimaryEmailAddress')#</cfoutput></a></span>
					</div>
					<div class="search">
						<form class="searchForm mini" action="index.cfm" method="post">
							<input type="text" placeholder="Search" name="p" />
							<input type="hidden" value="search" name="page" />
							<button type="submit"><i class="fa fa-search"></i></button>
						</form>
					</div><!--/search-->
				</div><!--/col-->
				<div class="mainNav col span8">
						<a class="toggleMainNav" href="javascript:" title="show navigation"><span><cfoutput>#ATTRIBUTES.pageName#</cfoutput></span><i class="fa fa-bars"></i></a>
						<ul class="mainNavList">
							<cfoutput>#APPLICATION.siteEngine.getSubNav( "mainNav" , false)#</cfoutput>
						</ul>
					</div><!--/col-->
			</div><!--/row-->
		</div><!--/header-->

	@yield('content')
