		<div class="footer container">
			<div class="row">
				<div class="col span3">
					<ul>
						<cfoutput>#APPLICATION.siteEngine.getSubNav( "mainNav" , false, true)#</cfoutput>
					</ul>
				</div><!--/col-->

				<div class="col span3">
					<ul>
						<cfoutput>#APPLICATION.siteEngine.getSubNav( "mainNav" , false, true)#</cfoutput>
					</ul>
				</div><!--/col-->

				<div class="col span3 contact">
					<div class="contact">
						<span><i class="fa fa-map-marker"></i><cfoutput>#replaceNoCase(APPLICATION.siteEngine.getUrlContent('companyAddress'),", ",",<br/>","ALL")#</cfoutput></span>
						<span><i class="fa fa-phone"></i><a href="tel:<cfoutput>#replaceNoCase(APPLICATION.siteEngine.getContent(argKey = 'companyPrimaryNumber', argShowPos = 'No'),' ','','ALL')#</cfoutput>"><cfoutput>#APPLICATION.siteEngine.getContent('companyPrimaryNumber')#</cfoutput></a></span>
						<span><i class="fa fa-envelope"></i><a href="mailto:<cfoutput>#APPLICATION.siteEngine.getContent(argKey = 'companyPrimaryEmailAddress', argShowPos = 'No')#</cfoutput>"><cfoutput>#APPLICATION.siteEngine.getContent('companyPrimaryEmailAddress')#</cfoutput></a></span>
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
						<cfoutput>#APPLICATION.siteEngine.getSubNav( "social-links" , false, true)#</cfoutput>
					</ul>
				</div><!--/col-->
			</div><!--/row-->
		</div>

		<div class="bottom container">
			<div class="row">
				<div class="col span6">
					<ul class="footerNavList">
						<cfoutput>#APPLICATION.siteEngine.getSubNav( "footerNav" , false, true)#</cfoutput>
					</ul>
				</div>
				<div class="col span6 text-right">
					&copy; <cfoutput>#DateFormat(now(),'yyyy')# #APPLICATION.siteName#</cfoutput> | <a href="http://granite.ie">Built by Granite Digital</a>
				</div>
			</div>
		</div>

	</div><!--/wrap-->

	<script src="assets/html5-placeholder-shim/jquery.html5-placeholder-shim.js"></script>
	<script type="text/javascript" src="assets/slick/slick.min.js"></script>
	<script src="assets/jquery/jquery-1.11.2.min.js"></script>
	<script src="assets/flexslider/jquery.flexslider-min.js"></script>
	<script src="assets/fancybox/jquery.fancybox.pack.js"></script>
	<script src="assets/easytabs/jquery.easytabs.min.js"></script>
	<script src="js/site.js"></script>

	@yield('extra-js')
</body>
</html>
