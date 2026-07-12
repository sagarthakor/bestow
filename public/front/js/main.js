;(function($){
	$(document).ready(function(){
		/* meanmenu */		
		$('#mobile-menu').meanmenu({
			meanMenuContainer: '.mobile-menu',
			meanScreenWidth: "1199"
		});
		
		/* Carousel */
		$('#Carousel-1').carousel({
			interval: false,		
		});
		$('#Carousel-2').carousel({
			interval: false,
		});
		$('#Carousel-3').carousel({
			interval: false,
		});
		$('#Carousel-4').carousel({
			interval: false,
		});
		$('#Carousel-5').carousel({
			interval: false,
		});
		$('#Carousel-6').carousel({
			interval: false,
		});
		$('#catCarousel').carousel({
			interval: 5000,
		});
		$('#catCarousel2').carousel({
			interval: 5000,
		});	

		/* Gallery */
		$('#gallery').carousel({
			interval: false,
		});

		/* Accordion menu */
		$("#accordion li > h4").on("click", function () {	  	     	
			if( $(this).next().is(':visible')){
				$(this).next().slideUp(300);
				$(this).children(".plusminus").text('+');
			}else {
				$(this).next("#accordion ul").slideDown(300);
				$(this).children(".plusminus").text('-');
			}	    	
		});

		/* Checkout accordion */
		$(".mini-login > h3 > span").on("click", function () {
			$(".mini-login-form").toggle("slow");
		});		
		$(".mini-coupon > h3 > span").on("click", function () {
			$(".coupon-box").toggle("slow");
		});
		$(".checkbox").on("click", function () {
			$(".create-account").toggle("slow");
		});
		$(".different").on("click", function () {
			$(".diffrent-address").toggle("slow");
		});

		/* Price filter */
		if ($("#slider-range").length) {
			$("#slider-range").slider({
				range: true,
				min: 0,
				max: 500,
				values: [80, 300],
				slide: function (event, ui) {
					$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
				}
			});
			$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
		}

		/* Quantiy plus minus */		
	   	$(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
		$(".qtybutton").on("click", function () {
			var $button = $(this);
			var oldValue = $button.parent().find("input").val();
			if ($button.text() == "+") {
				var newVal = parseFloat(oldValue) + 1;
			} else {
				// Don't allow decrementing below zero
				if (oldValue > 0) {
					var newVal = parseFloat(oldValue) - 1;
				} else {
					newVal = 0;
				}
			}
			$button.parent().find("input").val(newVal);
		});

		/* Masonry */
		if ($(".blog-masonry").length) {
			$('.blog-masonry').imagesLoaded(function () {
				// init Isotope
				var $grid = $('.blog-masonry').isotope({
					itemSelector: '.grid-item',
					percentPosition: true,
					masonry: {
						// use outer width of grid-sizer for columnWidth
						columnWidth: '.grid-item',
					}
				});
			});
		}

		/* Back to top */    
		$(window).scroll(function() {
			if($(this).scrollTop()){
				$('#top').fadeIn();
			}else{
				$('#top').fadeOut();
			}
		});
		$("#top").on("click", function () {
			$("html, body").animate({scrollTop: 0}, 800);
		});

		/* Google map */
		function basicmap() {
			// Basic options for a simple Google Map
			// For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
			var mapOptions = {
				// How zoomed in you want the map to start at (always required)
				zoom: 9,
				scrollwheel: false,
				// The latitude and longitude to center the map (always required)
				center: new google.maps.LatLng(52.5152319, 13.3975635), // Berlin
				// This is where you would paste any style found on Snazzy Maps.
				styles: [{ "stylers": [{ "hue": "#2d45cd" }] }, { "featureType": "road", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "road", "elementType": "geometry", "stylers": [{ "lightness": 100 }, { "visibility": "simplified" }] }]
			};
			// Get the HTML DOM element that will contain your map
			// We are using a div with id="map" seen below in the <body>
			var mapElement = document.getElementById('contact-map');

			// Create the Google Map using our element and options defined above
			var map = new google.maps.Map(mapElement, mapOptions);

			// Let's also add a marker while we're at it
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(52.5152319, 13.3975635),
				map: map,
				title: 'Cryptox'
			});
		}
		if ($('#contact-map').length != 0) {
			google.maps.event.addDomListener(window, 'load', basicmap);
		}

	});
})(jQuery)


$(document).ready(function(){

$(".tb").hover(function(){

$(".tb").removeClass("tb-active");
$(this).addClass("tb-active");

current_fs = $(".active");

next_fs = $(this).attr('id');
next_fs = "#" + next_fs + "1";

$("fieldset").removeClass("active");
$(next_fs).addClass("active");

current_fs.animate({}, {
step: function() {
current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({
'display': 'block'
});
}
});
});

});