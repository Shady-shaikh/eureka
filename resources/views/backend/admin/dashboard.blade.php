@extends('backend.layouts.app')
@section('title', 'Admin-Dashboard')
@section('content')

@php
use Spatie\Permission\Models\Role;

$role = Role::where('id',$user_id = Auth()->guard('admin')->user()->role)->first();
@endphp

<div class="card">
    <div class="card-body">
        <h3 class="mb-2">Welcome to Dashboard</h3>
        @if($role->department_id == 1)
        <h6><a href="https://drive.google.com/file/d/1Zgaypf4Fo531YruSxB4tpJ_6IbW3xXQr/view?usp=sharing"
                target="_blank">Click Here</a> to get Eureka mobile application now !</h6>
        @endif
        {{-- <a href="{{ url('/') }}/admin/logout" class="btn btn-secondary">Logout</a> --}}
    </div>
</div>

@endsection