<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

<!-- CSRF Token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(config('app.name', 'SkinClinic MLM')); ?></title>

<!-- Styles -->
<link
    href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
    rel="stylesheet">
<link rel="apple-touch-icon" href="<?php echo e(asset('public/backend-assets/images/ico/apple-icon-120.html')); ?>">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('public/backend-assets/images/logo-demo.png')); ?>">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/vendors/css/vendors.min.css')); ?>">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backend-assets/app-assets/css/bootstrap.css')); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/css/bootstrap-extended.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backend-assets/app-assets/css/colors.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backend-assets/app-assets/css/components.css')); ?>">
<!-- END: Theme CSS-->

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/css/core/menu/menu-types/vertical-menu-modern.css')); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/css/core/colors/palette-gradient.css')); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/fonts/simple-line-icons/style.min.css')); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/css/pages/card-statistics.css')); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/css/pages/vertical-timeline.css')); ?>">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel=" stylesheet" type="text/css" href="<?php echo e(asset('public/backend-assets/assets/css/style.css')); ?>">
<!-- END: Custom CSS-->



<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<link rel="stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/vendors/css/forms/selects/select2.min.css')); ?>">
<link rel=" stylesheet" type="text/css" href="<?php echo e(asset('public/backend-assets/css/toastr.min.css')); ?>">
<link rel=" stylesheet" type="text/css"
    href="<?php echo e(asset('public/backend-assets/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')); ?>">


<script src="<?php echo e(asset('public/frontend-assets/js/jquery-3.6.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('public/backend-assets/js/bootstrap3-typeahead.min.js')); ?>"></script>
<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>


<script type="text/javascript">
    var APP_URL = <?php echo json_encode(url('/')); ?>

</script><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/includes/head.blade.php ENDPATH**/ ?>