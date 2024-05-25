<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
  <title>@yield('title')</title>
  @include('backend.includes.head')
</head>

<body class="horizontal-layout horizontal-menu horizontal-menu-padding 2-columns  menu-expanded" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  @include('backend.includes.header')
  @include('backend.includes.leftmenu')

  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-body">
        @yield('content')
      </div>
    </div>
  </div>

  @include('backend.includes.footer')

  @include('frontend.includes.alerts')

  @yield('scripts')
</body>

</html>
