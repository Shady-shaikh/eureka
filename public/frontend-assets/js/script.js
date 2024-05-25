$(document).ready(function () {
  zoomimage();
  function zoomimage()
  {
    $(".block__pic").imagezoomsl({
      zoomrange: [3, 3]
    });
  }
    // $(document).on('imagezoomsl',".block__pic",{
    //     zoomrange: [3, 3]
    // });
});
