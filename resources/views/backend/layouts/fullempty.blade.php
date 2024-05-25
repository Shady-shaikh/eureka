<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <title>@yield('title')</title>
    @include('backend.includes.head')
    <link rel="stylesheet" href="{{ asset('public/backend-assets/login.css') }}">

</head>

<body>

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
