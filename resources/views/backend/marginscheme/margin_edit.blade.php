@extends('backend.layouts.app')
@section('title', 'Edit Margin Structure')

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
        <h3 class="content-header-title">Edit Margin Structure</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.pricings') }}">Margin Structure</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Margin Structure</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.margin') }}">
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
                    <h4 class="card-title">Edit Margin Structure</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @include('backend.includes.errors')
                        {!! Form::model($pricings, [
                        'method' => 'POST',
                        'url' => ['admin/margin/modify'],
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


                                {{ Form::select('pricing_type',['margin'=>'Margin','scheme'=>'Scheme'],null, ['class' =>
                                'form-control d-none']) }}


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('bp_category', 'Business Partner Categroy *') }}
                                        {{ Form::select('bp_category',DB::table('bp_category')->pluck('name','id'),null,
                                        [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Business Partner Categroy',
                                        'required' => true,
                                        ]) }}
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('bp_channel', 'Business Partner Channel *') }}
                                        {{ Form::select('bp_channel', $business_partner_category,null, [
                                        'class' => 'form-control select2 ',
                                        'placeholder' => 'Business Partner Channel',
                                        'required' => true,
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('bp_group', 'Business Partner group *') }}
                                        {{ Form::select('bp_group',
                                        [],
                                        null, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Select Business Partner Group',
                                        'required' => true,
                                        ]) }}
                                    </div>
                                </div>

                                {{-- <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('status', 'Pricing Status') }}
                                        {{ Form::select('status',['1'=>'Active','0'=>'In-Active'],null, ['class' =>
                                        'form-control']) }}
                                    </div>
                                </div> --}}


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

@section('scripts')
<script src="{{ asset('public/backend-assets/js/DynamicDropdown.js') }}"></script>

<script>
        
    var bp_channel = '{{$pricings->bp_channel}}';    
    var bp_group = '{{$pricings->bp_group}}';    
    if(bp_channel && bp_group){
        new DynamicDropdown('{{ route('admin.getGroups') }}',
        bp_channel, '#bp_group',bp_group)  
    }
    // usama_15-03-2024-fetch bp-group from channel
    $('#bp_channel').change(function() {
        var selectedValue = $(this).val();
        new DynamicDropdown('{{ route('admin.getGroups') }}',
            selectedValue, '#bp_group')
    });
</script>
@endsection