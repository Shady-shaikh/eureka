@extends('backend.layouts.empty')
@section('title', 'Admin Login')
@section('content')


<section id="auth-login" class="row flexbox-container">

    <body>
        <main class="">
          <section class="admin-frame">
            <div class="container">
              <div
                class="row d-flex align-items-center justify-content-center text-center pt-3 pb-3"
              >
                <div class="col-md-6 img-div">
                  <img src="{{ asset('public/backend-assets/images/banner/panel-img.jpg') }}" class="img-fluid" />
                </div>

                <div class="col-md-6">
                  <div class="form-content">
                    <h1 class="heading mb-4"><span>3P SAP</span> Admin Panel</h1>
                    <p class="text-center">Please fill out the following fields to login:</p>
                       @include('backend.includes.errors')
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                      <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="email" id="email"
                        value="{{ old('email') }}"
                        placeholder="Email"/>
                      </div>
                      <div class="mb-3 mt-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                        value="{{ old('password') }}"
                                placeholder="Password" />
                      </div>

                      <button class="btn btn-login">Login</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </main>
    </body>



</section>



@endsection
