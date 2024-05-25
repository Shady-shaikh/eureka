@include('frontend.includes.dynamic_footer')

<!--footer end-->

<!--  -->


	<script src="{{ asset('public/frontend-assets/js/mega-menu.js') }}"></script>

	<script src="{{ asset('public/frontend-assets/js/product-quantity.js') }}"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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




			<script >
				$(window).scroll(function(){
				  if ($(window).scrollTop() >=100) {
					  $('#navbar').addClass('fixed-header');
				  }
				  else {
					  $('#navbar').removeClass('fixed-header');
				  }
				});
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
	                     items:5,
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

	<!-- registration page show hide password script start -->

	<!-- registration page show hide password script end -->


	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript">
	$('#datepicker').datepicker({
				weekStart: 1,
				daysOfWeekHighlighted: "6,0",
				autoclose: true,
				todayHighlight: true,
		});

	</script>

	<script>
	$('.regi-radio-btn').click(function() {

	 //console.log("Clicked");
	 $('.gender-mf .active').removeClass('active');
	 $(this).addClass('active');
	});
	</script>
	<script>
	$(function(){
	$('.hide-show').show();
	$('.hide-show span').addClass('show')

	$('.hide-show span').click(function(){
		// alert('test');
	if( $(this).hasClass('show') ) {
		$(this).text('Hide');
		$('.show_password').attr('type','text');
		$(this).removeClass('show');
	} else {
		 $(this).text('Show');
		 $('.show_password').attr('type','password');
		 $(this).addClass('show');
	}
	});

	$('form button[type="submit"]').on('click', function(){
	$('.hide-show span').text('Show').addClass('show');
	$('.hide-show').parent().find('.show_password').attr('type','password');
	});
	});


	$(function(){
	$('.hide-showRe').show();
	$('.hide-showRe span').addClass('show')

	$('.hide-showRe span').click(function(){
	if( $(this).hasClass('show') ) {
		$(this).text('Hide');
		$('.show_confirm_password').attr('type','text');
		$(this).removeClass('show');
	} else {
		 $(this).text('Show');
		 $('.show_confirm_password').attr('type','password');
		 $(this).addClass('show');
	}
	});

	$('form button[type="submit"]').on('click', function(){
	$('.hide-showRe span').text('Show').addClass('show');
	$('.hide-showRe').parent().find('.show_confirm_password').attr('type','password');
	});
	});

	</script>
