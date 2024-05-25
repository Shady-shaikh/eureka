<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <title>@yield('title')</title>
      <link rel="apple-touch-icon" href="{{ asset('public/backend-assets/images/ico/apple-icon-120.html')}}">
      <link rel="shortcut icon" type="image/x-icon" href="https://www.pixinvent.com/demo/frest-clean-bootstrap-admin-dashboard-template/app-assets/images/ico/favicon.ico">
      <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
      <!-- BEGIN: Vendor CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/vendors.min.css') }}">
      <!-- END: Vendor CSS-->
      <!-- BEGIN: Theme CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/bootstrap-extended.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/colors.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/components.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/dark-layout.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/themes/semi-dark-layout.min.css') }}">
      <!-- END: Theme CSS-->
      <!-- BEGIN: Page CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/pages/authentication.css') }}">
      <!-- END: Page CSS-->
      <!-- BEGIN: Custom CSS-->
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/style.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/jquery-ui.css') }}">

      <!-- END: Custom CSS-->
   </head>
   <!-- END: Head-->
   <!-- BEGIN: Body-->
    <body  data-open="click" data-col="1-column">
      <!-- BEGIN: Content-->
      <div class="app-content content">
         <div class="content-overlay"></div>
         <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
							@yield('content')
						</div>
         </div>
      </div>
      <!-- END: Content-->
      <!-- BEGIN: Vendor JS-->
			@yield('scripts')
            <script src="{{ asset('public/backend-assets/js/scripts/pages/bootstrap.bundle.js')}}"></script>
            <script src="{{ asset('public/backend-assets/js/scripts/pages/bootstrap.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/vendors/js/vendors.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js')}}"></script>
      <!-- BEGIN Vendor JS-->
      <!-- BEGIN: Page Vendor JS-->
      <!-- END: Page Vendor JS-->
      <!-- BEGIN: Theme JS-->
      <script src="{{ asset('public/backend-assets/js/scripts/configs/vertical-menu-light.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/core/app-menu.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/core/app.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/scripts/components.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/scripts/footer.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/scripts/fontawesome/all.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/jquery-3.6.1.min.js')}}"></script>
      <script src="{{ asset('public/backend-assets/js/jquery-ui.js')}}"></script>

      <!-- END: Theme JS-->
      <!-- BEGIN: Page JS-->
      <!-- END: Page JS-->
   </body>

</html>
