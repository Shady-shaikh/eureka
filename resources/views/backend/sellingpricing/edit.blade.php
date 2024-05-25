@extends('backend.layouts.app')
@section('title', 'Edit Sale Pricelist')

@section('content')
@php
use App\Models\backend\Company;

$status = ['No' => 'No', 'Yes' => 'Yes'];
$product_types = ['simple' => 'Simple', 'configurable' => 'Configurable'];

@endphp
<style>

</style>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Sale Pricelist</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.sellingpricing') }}">Sale Pricelist</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Sale Pricelist</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.sellingpricing') }}">
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
                    <h4 class="card-title">Edit Sale Pricelist</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @include('backend.includes.errors')
                        {!! Form::model($pricings, [
                        'method' => 'POST',
                        'url' => ['admin/sellingpricing/update'],
                        'class' => 'form',
                        'enctype' => 'multipart/form-data',
                        ]) !!}
                        {{ Form::hidden('pricing_master_id', null, ['required' => true, 'id' => 'pricing_master_id']) }}
                        <div class="form-body">
                            <!-- <h2>General</h2> -->
                            <div class="row">

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('pricing_name', 'Name ') }}
                                        {{ Form::text('pricing_name', null, ['class' => 'form-control',
                                        'placeholder' =>'Enter Name', 'required' => true, 'id' => 'sku_description']) }}
                                    </div>
                                </div>


                                {{ Form::select('pricing_type',['sale'=>'Sale','purchase'=>'Purchase'],null, ['class' =>
                                'form-control d-none']) }}

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('company_id', 'Distributor') }}
                                        {{ Form::select('company_id',Company::pluck('name','company_id'),null, ['class' =>
                                        'form-control ','placeholder'=>'Select Distributor','required'=>true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('status', 'Pricing Status') }}
                                        {{ Form::select('status',['1'=>'Active','0'=>'In-Active'],null, ['class' =>
                                        'form-control']) }}
                                    </div>
                                </div>


                                <div class="col-12 d-flex justify-content-start">
                                    {{ Form::submit('Update', ['class' => 'btn btn-primary mr-1 mb-1']) }}
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