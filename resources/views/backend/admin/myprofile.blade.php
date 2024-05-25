@extends('backend.layouts.app')
@section('title', 'MyProfile')
@section('content')

<div class="content-header row">
  <div class="content-header-left col-12 mb-2">
    <h3 class="content-header-title">My Profile</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">My Profile</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            @include('backend.includes.errors')
            {!! Form::model($adminuser, [
            'method' => 'POST',
            'url' => ['admin/update_profile'],
            'class' => 'form'
            ]) !!}
            <div class="form-body">
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    {{ Form::hidden('admin_user_id', $adminuser->admin_user_id) }}
                    {{ Form::label('first_name', 'First Name *') }}
                    {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'required' => true]) }}
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    {{ Form::label('last_name', 'Last Name *') }}
                    {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name', 'required' => true]) }}
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    {{ Form::label('mobile_no', 'Mobile *') }}
                    {{ Form::text('mobile_no', null, ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number', 'required' => true]) }}
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    {{ Form::label('email', 'Email *') }}
                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'readonly' => 'readonly']) }}
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-start">
                  {{ Form::submit('Update', array('class' => 'btn btn-primary mr-1')) }}
                </div>
              </div>
            </div>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
