@extends('backend.layouts.app')
@section('title', 'Edit State')

@section('content')

<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title">Edit State</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>

          <li class="breadcrumb-item">
            <a href="{{ route('admin.state',['id'=>request('state_id')])}}">States</a>
          </li>

          <li class="breadcrumb-item">
            <a href="{{ route('admin.city',[$city->state_id])}}">Cities</a>
          </li>

          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div>
    </div>
  </div>

  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary add-btn" href="{{ route('admin.city',[$city->state_id]) }}">
          <i class="feather icon-arrow-left"></i> Back
        </a>
      </div>
    </div>
  </div>
</div>


<section id="basic-datatable">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body card-dashboard">
                @include('backend.includes.errors')
                {{ Form::model($city,['url' => 'admin/states/city/update']) }}
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('name', 'City Name *') }}
                                {{ Form::text('city_name', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Enter City Name']) }}
                                {{ Form::hidden('city_id', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Enter City Name']) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-12 pt-2">
                            {{ Form::submit('Update', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                            <input type="reset" name='Reset' value="Reset" class='btn btn-secondary mr-1 mb-1'>
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

