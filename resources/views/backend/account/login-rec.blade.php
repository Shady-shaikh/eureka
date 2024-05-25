@extends('backend.layouts.empty')
@section('title', 'Admin Login')
@section('content')


<div class="container">
    <div class="row g-0">
        <div class="col-lg-10 mx-auto mt-5">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-md-6">
                            <div class="image">
                                <img src="{{ asset('public/backend-assets/images/banner/panel-img.jpg') }}" class="img-fluid" />
                            </div>
                        </div>
                        <div class="col-md-6" style="border-left: 2px solid var(--secondary)">
                            <div class="d-flex flex-column justify-content-center h-100 p-2">
                            <h3 class="heading mb-3">EUREKA Panel</h3>
                            <form method="POST" action="{{ route('admin.login.submit') }}">
                                @csrf
                                <div class="form-group mb-2">
                                  <label class="form-label">Username</label>
                                  <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}"
                                  placeholder="Enter Email"/>
                                </div>
                                <div class="form-group mb-2">
                                  <label class="form-label">Password</label>
                                  <input type="password" class="form-control" name="password" email="password" value="{{ old('password') }}"
                                  placeholder="Enter  Password"/>
                                </div>

                                <button class="btn btn-secondary">Login</button>
                              </form>
                        </div>
                    </div></div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
