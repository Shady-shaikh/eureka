$(".product-hover").on("mouseenter",function() {
  $(".sliderdemo").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 1000,
    pauseOnHover:false,
    dots: true,
    initialSlide:0
  });
  });
  $(".product-hover").on("mouseleave", function() {
// $(this).slick('slickGoTo', 0)
    $(".sliderdemo").slick('destroy');
    // $(this).slick('slickPause');
  });
