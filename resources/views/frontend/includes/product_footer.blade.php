@include('frontend.includes.dynamic_footer')

<!--  -->

<!--  -->

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="{{ asset('public/frontend-assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/webticker.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle/3.0.3/jquery.cycle.all.min.js" integrity="sha512-T+Pw5KwLzU1wmH5slAFcNK8lstMe+BBEi1qLkM1XIWEPZzcNmuiuUKIdq8ATSUFxx138gAIGsKaq8/ljyElMqQ==" crossorigin="anonymous"></script>
	<script src="{{ asset('public/frontend-assets/js/price-range-slider.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
	<script src="{{ asset('public/frontend-assets/js/mega-menu.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/ticker-note.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/back-to-top.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/sort-by.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/product-hover-slider.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/zoomsl.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/product-quantity.js') }}"></script>

	<script src="{{ asset('public/frontend-assets/js/toastr.min.js') }}"></script>
	
	<script src="{{ asset('public/frontend-assets/js/slick.js') }}"></script>
	<script src="{{ asset('public/frontend-assets/js/slick.min.js') }}"></script>
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
     function openNav(){
      document.getElementById("collapsibleNavbar-two").style.display = "block";

     }
     function closeNav() {
       document.getElementById("collapsibleNavbar-two").style.display = "none";
     }
  </script>
	<script>
	$('.product-slider1').owlCarousel({
			 loop:true,
			 margin:10,
			 dots:false,
			 nav:true,
			 mouseDrag:true,
			 autoplay:false,
			 navSpeed: 1000,

			 responsive:{
					 0:{
							 items:1,
			slideBy: 1
					 },
					 600:{
							 items:2,
			slideBy: 1
					 },
					 1000:{
							 items:4,
			slideBy: 1
					 }
			 }
	 });
	  </script>



				<!-- image zoom code -->
				<script>

				var demoTrigger = document.querySelectorAll('.zoom-element');
				var paneContainer = document.querySelector('.drift');

				demoTrigger.forEach((item) => {
				  new Drift(item, {
				    paneContainer: paneContainer,
				    inlinePane: false,
				    // containInline: true,
				       responsive: true,
				          // hoverBoundingBox: true,
				  });

				});

				</script>


				<!--  -->
				<script>
				$(document).ready(function(){
				  $('.cloth-size').on('click',function()
				  {
				    $('.cloth-size').removeClass('cloth-size-selected')
				    $(this).addClass('cloth-size-selected');
				  });
				  $('.cloth-color').on('click',function()
				  {
				    $('.cloth-color').removeClass('cloth-col-selected')
				    $(this).addClass('cloth-col-selected');
				  });
				});
				</script>
				<!-- View more and less  -->
				<script>
				$(document).ready(function() {
				$("#toggle").click(function() {
				  var elem = $("#toggle").text();
				  if (elem == "View More Info") {
				    //Stuff to do when btn is in the read more state
				    $("#toggle").text("View Less Info");
				    $("#text").slideDown();
				  } else {
				    //Stuff to do when btn is in the read less state
				    $("#toggle").text("View More Info");
				    $("#text").slideUp();
				  }
				});
				});
				</script>
				<!-- six small images shadow effect script -->
				<script >
				$(document).ready(function() {
				 $('.img-li').click(function (e) {
				     e.preventDefault();
				     $('.img-li img').removeClass('active1');
				     $(this).find('.img-hover-shadow').addClass('active1');

				 });
				});
				</script>

				
