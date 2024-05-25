@extends('backend.layouts.fullempty')
@section('title', 'Admin Login')
@section('content')

    <style>
        .text-comp {
            margin-top: 60px;
            font-size: 14px;
        }
    </style>


    <main class="">
        <section class="admin-frame">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center text-center pt-2 pb-2">
                    <div class="col-md-6 img-div">
                        <img src="{{ asset('public/backend-assets/images/banner/panel-img.jpg') }}" class="img-fluid" />
                    </div>

                    <div class="col-md-6">
                        <div class="form-content">
                            <h1 class="heading mb-4"><span>EUREKA</span> Admin Panel</h1>
                            @include('backend.includes.errors')

                            <form method="POST" action="{{ route('admin.login.submit') }}">
                                @csrf


                                <div class="">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        value="{{ old('email') }}" placeholder="Enter Email" />
                                </div>
                                <div class="">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" email="password"
                                        value="{{ old('password') }}" placeholder="Enter  Password" />
                                </div>

                               
                                <button class="btn btn-login mt-2">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>



@endsection
