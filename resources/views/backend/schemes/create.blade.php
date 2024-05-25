@extends('backend.layouts.app')
@section('title')
Create New Scheme
@stop

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Offer</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="{{ route('admin.schemes') }}">
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
          <div class="card-content">
            <div class="card-body">
              @include('backend.includes.errors')

              <form method="POST" action="{{ route('admin.schemes.store') }}" class="form">
                {{ csrf_field() }}
           <div class="row">
            <div class="form-group col-lg-12 {{ $errors->has('scheme_title') ? 'has-error' : ''}}">
                {!! Form::label('scheme_title', 'Scheme Title: ', ['class' => ' control-label']) !!}
                <div class="">
                    {!! Form::text('scheme_title', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('scheme_title', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
                <div class="form-group col-lg-6 col-md-6 {{ $errors->has('min_product_qty') ? 'has-error' : ''}}">
                {!! Form::label('min_product_qty', 'Mininum Product Qty: ', ['class' => ' control-label']) !!}
                <div class="">
                    {!! Form::number('min_product_qty', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('min_product_qty', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group col-lg-6 col-md-6 {{ $errors->has('free_product_qty') ? 'has-error' : ''}}">
                {!! Form::label('free_product_qty', 'Free Product Qty: ') !!}
                <div class="">
                    {!! Form::number('free_product_qty', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('free_product_qty', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group col-lg-2 col-md-2 col-3 col-sm-2 mt-2">
                {!! Form::submit('Create', ['class' => 'btn btn-primary ']) !!}
            </div>
    </div>
    {!! Form::close() !!}

@endsection
