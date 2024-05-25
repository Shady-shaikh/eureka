

<!--footer end-->


	<script src="{{ asset('public/frontend-assets/js/mega-menu.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/deals-timer.js') }}"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.Marquee/1.5.0/jquery.marquee.min.js"></script>
	<script>
	(function ($) {
	$(function () {
			var agMarqueeOptions = {
				duration: 20500,
				gap: 0,
				delayBeforeStart: 0,
				direction: 'left',
				duplicated: true,
				pauseOnHover: true,
				startVisible: true
			};
			 $(document) .ready( function () {
				var agMarqueeBlock = $('.js-marquee');
				agMarqueeBlock.marquee(agMarqueeOptions);
			});
	});
	})(jQuery);
	</script>
			<script>
				// ===== Scroll to Top ====
				$(window).scroll(function() {
					if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
							$('#return-to-top').fadeIn(200);    // Fade in the arrow
					} else {
							$('#return-to-top').fadeOut(200);   // Else fade out the arrow
					}
				});
				$('#return-to-top').click(function() {      // When arrow is clicked
					$('body,html').animate({
							scrollTop : 0                       // Scroll to top of body
					}, 4000);
				});
			</script>


			<!-- product slider 15 images-->
			<script>
	         $('.product-slider').owlCarousel({
	             loop:true,
	             margin:10,
	             dots:false,
	             nav:true,
	             mouseDrag:true,
	             autoplay:true,
							 navSpeed: 1000,
	      			animateOut: "slideOutDown",
	      			animateIn: "slideInDown",

	             responsive:{
	                 0:{
	                     items:2,
						  slideBy: 2
	                 },
	                 600:{
	                     items:3,
						  slideBy: 1
	                 },
	                 1000:{

	                     items:5,
						  slideBy: 1
	                 }
	             }

	         });

	      </script>
	<!-- product slider 15 images end-->





	<script>
	function openNav(){
		document.getElementById("collapsibleNavbar-two").style.display = "block";

	}
	function closeNav() {
	  document.getElementById("collapsibleNavbar-two").style.display = "none";
	}
	</script>
	<script>
	$('.triggerSidebar').click(function() {
 // $('.sidebar').css('top', '160px')
   $('body').css('overflow', 'hidden')
})

var filter = function() {
  $('.sidebar').css('display', 'none')
}

$('.hideSidebar').click(filter)

</script>

<script>
$('.triggerSidebar1').click(function() {
   $('body').css('overflow', 'hidden')
})

var sortby = function() {
  $('.sidebar1').css('display', 'none')

}

$('.hideSidebar1').click(sortby)
// $('.overlay').click()
	</script>
