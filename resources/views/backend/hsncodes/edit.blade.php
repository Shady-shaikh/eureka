@extends('backend.layouts.app')
@section('title', 'Update HSNCodes')

@section('content')
@php

@endphp
<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title">HSNCodes</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('admin.hsncodes')}}">HSNCodes</a>
          </li>
          <li class="breadcrumb-item active">Update HSNCode</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary" href="{{ route('admin.hsncodes.create') }}">
          <i class="feather icon-arrow-left"></i> Back
        </a>
      </div>
    </div>
  </div>
</div>
        <section id="multiple-column-form">
          <div class="row match-height">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Update HSNCodes</h4>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    @include('backend.includes.errors')
                    {!! Form::model($hsncode, [
                        'method' => 'POST',
                        'url' => ['admin/hsncodes/update'],
                        'class' => 'form'
                    ]) !!}
                      <div class="form-body">
                        <div class="row">
                          <div class="col-md-12 col-12">
                            <div class="form-group">
                              {{ Form::hidden('hsncode_id', $hsncode->hsncode_id) }}
                              {{ Form::label('hsncode_name', 'HSNCode Name *') }}
                                {{ Form::text('hsncode_name', null, ['class' => 'form-control', 'placeholder' => 'Enter HSNCode Name', 'required' => true]) }}
                              </div>
                            </div>

                            
                            <div class="col-lg-12 col-md-12">
                              <fieldset class="form-group">
                                {{ Form::label('hsncode_desc', 'HSNCode Description') }}
                                  {{ Form::textarea('hsncode_desc', null, ['class' => 'form-control char-textarea', 'placeholder' => 'Enter Description', 'rows'=>3]) }}
                              </fieldset>
                            </div>
                          <div class="col-12 d-flex justify-content-start">
                            {{ Form::submit('Update', array('class' => 'btn btn-primary mr-1 mb-1')) }}
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
      </div>
    </div>
@endsection
