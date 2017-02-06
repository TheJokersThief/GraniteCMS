
$(function() {


	$('#bannerSlider').slick({
	  dots: false,
	  autoplay: true,
	  autoplaySpeed: 1500,
	  infinite: true,
	  speed: 500,
	  fade: true,
	  cssEase: 'linear'
	});



	//scrolls smoothly to next section
	$('a[href*=#]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
					return false;
				}
			}
	});

	//show/hide main nav
	$( ".toggleMainNav" ).click(function() {
		$( ".mainNav" ).toggleClass( "open" );
	});

	//show/hide page nav
	$( ".togglePageNav" ).click(function() {
		$( ".sidebar" ).toggleClass( "open" );
	});

	//show/hide tabs
	$( ".toggleTabs" ).click(function() {
		$( ".tab-container" ).toggleClass( "open" );
	});
	$( ".etabs a" ).click(function() {
		$( ".tab-container" ).toggleClass( "open" );
	});

	//change appearange of sticky top nav bar
	var shrinkHeader = 50;
		$(window).scroll(function() {
			var scroll = getCurrentScroll();
			if ( scroll >= shrinkHeader ) {
				$('.header.container').addClass('sticky');
			}
			else {
				$('.header.container').removeClass('sticky');
			}
		});
	function getCurrentScroll() {
		return window.pageYOffset;
	}

	//easy tabs
	$('.tab-container').easytabs();

	//accordion
	var allPanels = $('.accordion > div').hide();
	$('.accordion > h3 > a').click(function() {
		$this = $(this);
			$target =  $this.parent().next().slideToggle();
			$target.toggleClass('open');
			$this.parent().toggleClass('open');
			return false;
	});

});

function closeCookiePolicy(){
	var url = "/cookie-policy";
	$.ajax({
		url: url,
		asynchronous:true,
		success:function(response){
			$("#stickycookiePolicy").fadeOut();
		}
	});
}