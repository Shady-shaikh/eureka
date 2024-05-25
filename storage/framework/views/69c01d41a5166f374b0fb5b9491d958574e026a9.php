<?php
//use Session;
?>
<script>
  <?php if(Session::has('success')): ?>

  		// toastr.success("<?php echo e(Session::get('success')); ?>");
      Swal.fire({
      position: "top-end",
      icon: "success",
      title: "<?php echo e(Session::get('success')); ?>",
      showConfirmButton: false,
      timer: 1500
    });
      // alert('test');
  <?php endif; ?>


  <?php if(Session::has('info')): ?>

  		// toastr.info("<?php echo e(Session::get('info')); ?>");
      Swal.fire({
      position: "top-end",
      icon: "info",
      title: "<?php echo e(Session::get('info')); ?>",
      showConfirmButton: false,
      timer: 1500
    });

  <?php endif; ?>


  <?php if(Session::has('warning')): ?>

  		// toastr.warning("<?php echo e(Session::get('warning')); ?>");
      Swal.fire({
      position: "top-end",
      icon: "warning",
      title: "<?php echo e(Session::get('warning')); ?>",
      showConfirmButton: false,
      timer: 1500
    });

  <?php endif; ?>


  <?php if(Session::has('error')): ?>

  		// toastr.error("<?php echo e(Session::get('error')); ?>");
      Swal.fire({
      position: "top-end",
      icon: "error",
      title: "<?php echo e(Session::get('error')); ?>",
      showConfirmButton: false,
      timer: 1500
    });

  <?php endif; ?>

  <?php if(Session::has('message')): ?>
  // alert('msg');
  		// toastr.info("<?php echo e(Session::get('message')); ?>");
      Swal.fire({
      position: "top-end",
      icon: "success",
      title: "<?php echo e(Session::get('message')); ?>",
      showConfirmButton: false,
      timer: 1500
    });


  <?php endif; ?>

</script><?php /**PATH C:\wamp64\www\eureka\resources\views/frontend/includes/alerts.blade.php ENDPATH**/ ?>