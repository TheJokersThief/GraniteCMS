<div class="footer container">
	<?php
		$main_menu = getAllMenuPages(getMenuByName('Main Menu'))->get(0);
		$pages = $main_menu['children'];
	?>
			<div class="row">
				<div class="col span3">
					<ul>
						<li class="lev1"><a href="/">Home</a></li>
						@foreach($pages as $page)
							<li class="lev1"><a href="{{ page($page['slug']) }}" title="{{ $page['text'] }}">{{ $page['text'] }}</a></li>
						@endforeach
					</ul>
				</div><!--/col-->

				<div class="col span3">
					<ul>
						<li class="lev1"><a href="/">Home</a></li>
						@foreach($pages as $page)
							<li class="lev1"><a href="{{ page($page['slug']) }}" title="{{ $page['text'] }}">{{ $page['text'] }}</a></li>
						@endforeach
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
					&nbsp;
				</div>
				<div class="col span6 text-right">
					&copy; 2017 DefaultSite 
				</div>
			</div>
		</div>
	</div><!--/wrap-->

<script src="{{ siteAsset('js/html5-placeholder-shim/jquery.html5-placeholder-shim.js') }}"></script>
<script type="text/javascript" src="{{ siteAsset('js/slick/slick.min.js') }}"></script>
<script src="{{ siteAsset('js/flexslider/jquery.flexslider-min.js') }}"></script>
<script src="{{ siteAsset('js/fancybox/jquery.fancybox.pack.js') }}"></script>
<script src="{{ siteAsset('js/easytabs/jquery.easytabs.min.js') }}"></script>
<script src="{{ siteAsset('js/site.js') }}"></script>

@yield('extra-js')

</body>
</html>