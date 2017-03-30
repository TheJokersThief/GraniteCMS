@extends('granitecms_dev.theme.views.layouts.default')

@section('content')
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
					<div class="search">
						<form class="searchForm mini" action="index.cfm" method="post">
							<input type="text" placeholder="Search" name="p" />
							<input type="hidden" value="search" name="page" />
							<button type="submit"><i class="fa fa-search"></i></button>
						</form>
					</div><!--/search-->
				</div><!--/col-->
				<div class="mainNav col span8">
					<a class="toggleMainNav" href="javascript:" title="show navigation"><span>Home</span><i class="fa fa-bars"></i></a>
					<ul class="mainNavList">
						<li class="onPath first lev1 sel  simpleDropdown"><a href="/" title="Home">Home</a></li>
						<li class="lev1  simpleDropdown"><a href="{{ page('services') }}" title="Services">Services</a></li>
						<li class="lev1  simpleDropdown"><a href="{{ page('about') }}-us" title="About Us">About Us</a></li>
						<li class="lev1  simpleDropdown"><a href="{{ page('contact') }}-us" title="Contact Us">Contact Us</a></li>
						<li class="lev1  simpleDropdown"><a href="{{ page('test') }}" title="test">test</a></li>
						<li class="lev1  last simpleDropdown"><a href="{{ page('staff') }}-profiles" title="Staff Profiles">Staff Profiles</a></li>
					</ul>
				</div><!--/col-->
			</div><!--/row-->
		</div><!--/header-->

		<!-- ********** BANNER ********** -->
		<div class="banner">
			<div class="row fullwidth">
				<div class="col span12 bannerSlider">
					<div id="bannerSlider">
						@foreach($banners as $banner)
							<div>
								<a href="/">
									<div class="text">
										<h2>Banner Headline 1</h2>
										<p>Images should be between 1600px and 2000px wide for a full width banner. The image height will determine the overall banner height.</p>
										<p class="more">Learn More <i class="fa fa-angle-right"></i></p>
									</div>
									<div class="image">
										<img src="{{ $banner->url }}"  />
									</div>
								</a>
							</div>

						@endforeach
						
					</div>
				</div><!--/col-->
			</div><!--/row-->
		</div><!--/banner-->

		<div class="container contentWrap">
			<div class="row sample">
				<div class="col span6">
					<div class="inner">
						span6 ( 6 + 6 = 12 )
					</div>
				</div>
				<div class="col span6">
					<div class="inner">
						span6 ( 6 + 6 = 12 )
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span2">
					<div class="inner">
						span2 (2 + 7 + 3 = 12)
					</div>
				</div>
				<div class="col span7">
					<div class="inner">
						span7 (2 + 7 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (2 + 7 + 3 = 12)
					</div>
				</div>
			</div>

			<div class="row sideItems">
				<ul class="sideItemsList">			
					<li class="col span3 " id="21" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
						
					</li>
					<li class="col span3 " id="22" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>

					<li class="col span3 " id="23" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>
				

					<li class="col span3 " id="24" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>
				</ul>
			</div><!--/sideItems-->
		</div><!--/container-->


		<div class="footer container">
			<div class="row">
				<div class="col span3">
					<ul>
						<li class="onPath first lev1 sel navhome  simpleDropdown"><a href="/" title="Home">Home</a></li>
						<li class="lev1 navservices  simpleDropdown"><a href="{{ page('services') }}" title="Services">Services</a></li>
						<li class="lev1 navabout-us  simpleDropdown"><a href="{{ page('about-us') }}" title="About Us">About Us</a></li>
						<li class="lev1 navcontact-us  simpleDropdown"><a href="{{ page('contact-us') }}" title="Contact Us">Contact Us</a></li>
						<li class="lev1 navtest  simpleDropdown"><a href="{{ page('test') }}" title="test">test</a></li>
						<li class="lev1 navstaff-profiles  last simpleDropdown"><a href="{{ page('staff') }}-profiles" title="Staff Profiles">Staff Profiles</a></li>
					</ul>
				</div><!--/col-->

				<div class="col span3">
					<ul>
						<li class="onPath first lev1 sel navhome  simpleDropdown"><a href="/" title="Home">Home</a></li>
						<li class="lev1 navservices  simpleDropdown"><a href="{{ page('services') }}" title="Services">Services</a></li>
						<li class="lev1 navabout-us  simpleDropdown"><a href="{{ page('about-us') }}" title="About Us">About Us</a></li>
						<li class="lev1 navcontact-us  simpleDropdown"><a href="{{ page('contact-us') }}" title="Contact Us">Contact Us</a></li>
						<li class="lev1 navtest  simpleDropdown"><a href="{{ page('test') }}" title="test">test</a></li>
						<li class="lev1 navstaff-profiles  last simpleDropdown"><a href="{{ page('staff') }}-profiles" title="Staff Profiles">Staff Profiles</a></li>
					</ul>
				</div><!--/col-->

				<div class="col span3 contact">
					<div class="contact">
						<span><i class="fa fa-map-marker"></i><a href="/'" class="" id="" title="GRANITE,<br/>Address,<br/>Address"   >GRANITE,<br/>Address,<br/>Address</a></span>
						<span><i class="fa fa-phone"></i><a href="tel:+353212427890">+353 21 242 7890</a></span>
						<span><i class="fa fa-envelope"></i><a href="mailto:infoto@default.ie">infoto@default.ie</a></span>
					</div>
				</div><!--/col-->
				<div class="col span3">
					<div class="newsletter">
						<form class="newsletterForm general inline">
							<div class="col span">
								<input type="text" placeholder="Email address">
							</div>
							<div class="col span3">
								<button type="submit">Join</button>
							</div>
						</form>
					</div><!--/newsletter-->
					<ul class="socialNavList col span12">
						<li class="first lev1 navfacebook  simpleDropdown"><a href="{{ page('facebook') }}" title="Facebook">Facebook</a></li>
						<li class="lev1 navtwitter  last simpleDropdown"><a href="{{ page('twitter') }}" title="Twitter">Twitter</a></li>
					</ul>
				</div><!--/col-->
			</div><!--/row-->
		</div><!--/footer-->

		<div class="bottom container">
			<div class="row">
				<div class="col span6">
					<ul class="footerNavList">
						<li class="first lev1 navsitemap  simpleDropdown"><a href="{{ page('sitemap') }}" title="Sitemap">Sitemap</a></li>
						<li class="lev1 navprivacy  simpleDropdown"><a href="{{ page('privacy') }}" title="Privacy">Privacy</a></li>
						<li class="lev1 navterms-of-use  simpleDropdown"><a href="{{ page('terms-of-use') }}" title="Terms of Use">Terms of Use</a></li>
						<li class="lev1 navcookie-policy  simpleDropdown"><a href="{{ page('cookie-policy') }}" title="Cookie Policy">Cookie Policy</a></li>
						<li class="lev1 navblog  last simpleDropdown"><a href="{{ page('test') }}" title="Blog">Blog</a></li>

					</ul>
				</div>
				<div class="col span6 text-right">
					&copy; 2017 DefaultSite | <a href="http://granite.ie">Built by Granite Digital</a>
				</div>
			</div>
		</div>
	</div><!--/wrap-->
@stop

