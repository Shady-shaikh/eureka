@php
//use Session;
@endphp
<script>
  @if(Session::has('success'))

  		// toastr.success("{{ Session::get('success') }}");
      Swal.fire({
      position: "top-end",
      icon: "success",
      title: "{{Session::get('success')}}",
      showConfirmButton: false,
      timer: 1500
    });
      // alert('test');
  @endif


  @if(Session::has('info'))

  		// toastr.info("{{ Session::get('info') }}");
      Swal.fire({
      position: "top-end",
      icon: "info",
      title: "{{Session::get('info')}}",
      showConfirmButton: false,
      timer: 1500
    });

  @endif


  @if(Session::has('warning'))

  		// toastr.warning("{{ Session::get('warning') }}");
      Swal.fire({
      position: "top-end",
      icon: "warning",
      title: "{{Session::get('warning')}}",
      showConfirmButton: false,
      timer: 1500
    });

  @endif


  @if(Session::has('error'))

  		// toastr.error("{{ Session::get('error') }}");
      Swal.fire({
      position: "top-end",
      icon: "error",
      title: "{{Session::get('error')}}",
      showConfirmButton: false,
      timer: 1500
    });

  @endif

  @if(Session::has('message'))
  // alert('msg');
  		// toastr.info("{{ Session::get('message') }}");
      Swal.fire({
      position: "top-end",
      icon: "success",
      title: "{{Session::get('message')}}",
      showConfirmButton: false,
      timer: 1500
    });


  @endif

</script>