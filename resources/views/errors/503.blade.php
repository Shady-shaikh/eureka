@php 
use App\Models\frontend\Maintainance;

$maintainance_image = Maintainance::first();
@endphp

<!doctype html>
<html>
<title>Site Maintenance</title>
<head>
<link rel="stylesheet" type="text/css" href="{{ asset('public/backend-assets/css/bootstrap.min.css') }}">
<style>

</style>
</head>



<body >
    <div style="height: 100vh;overflow: hidden;">
        <img class="img-fluid" style="object-fit: contain;object-position: center;width: 100%;height: 100%;" src="{{asset('public/frontend-assets/images')}}/{{ (isset($maintainance_image) && ($maintainance_image->maintainance_image != ''))?$maintainance_image->maintainance_image:'Maintenance-page-image.jpg' }}">
    </div>
</body>
</html>