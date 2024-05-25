<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
  <title><?php echo $__env->yieldContent('title'); ?></title>
  <?php echo $__env->make('backend.includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="horizontal-layout horizontal-menu horizontal-menu-padding 2-columns  menu-expanded" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  <?php echo $__env->make('backend.includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('backend.includes.leftmenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-body">
        <?php echo $__env->yieldContent('content'); ?>
      </div>
    </div>
  </div>

  <?php echo $__env->make('backend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->make('frontend.includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\wamp64\www\eureka\resources\views/backend/layouts/app.blade.php ENDPATH**/ ?>