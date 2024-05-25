var minVal = 1, maxVal = 10; // Set Max and Min values
// Increase product quantity on cart page
$(".increaseQty").on('click', function(){
  var $parentElm = $(this).parents(".qtySelector");
  $(this).addClass("clicked");
  setTimeout(function(){
    $(".clicked").removeClass("clicked");
  },100);
  var value = $parentElm.find(".qtyValue").val();
  if (value < maxVal) {
    value++;
  }
  $parentElm.find(".qtyValue").val(value);
});
// Decrease product quantity on cart page
$(".decreaseQty").on('click', function(){
  var $parentElm = $(this).parents(".qtySelector");
  $(this).addClass("clicked");
  setTimeout(function(){
    $(".clicked").removeClass("clicked");
  },100);
  var value = $parentElm.find(".qtyValue").val();
  if (value > 1) {
    value--;
  }
  $parentElm.find(".qtyValue").val(value);
});
