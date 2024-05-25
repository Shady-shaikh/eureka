@php
use App\Models\backend\FrontendImages;

$favicon = FrontendImages::where('image_code','favicon')->first();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Dadreeios') }}</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!--Roboto font link-->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/parasight_dadreeios.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/mega_menu.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/font/flaticon.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/chosen.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend-assets/css/slick.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend-assets/css/slick-theme.css') }}"/>

<link rel="stylesheet" href="{{ asset('public/frontend-assets/css/toastr.min.css') }}">

@if(isset($favicon->image_url))
	<link rel="icon" href="{{ asset('public/backend-assets/uploads/frontend_images/') }}/{{ $favicon->image_url }}" />
@else
	<link rel="icon" href="{{ asset('public/favicon1.png')}}" />
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
