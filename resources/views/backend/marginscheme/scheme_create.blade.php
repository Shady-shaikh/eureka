@extends('backend.layouts.app')
@section('title', 'Create Promo / Scheme Grid')

@section('content')

@php
use App\Models\backend\Company;

@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Promo / Scheme Grid</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.scheme') }}">Promo / Scheme Grid</a>
                    </li>
                    <li class="breadcrumb-item active">Create Promo / Scheme Grid</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.scheme') }}">
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
                    <h4 class="card-title">Create Promo / Scheme Grid</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @include('backend.includes.errors')
                        {{ Form::open(['url' => 'admin/scheme/store', 'enctype' => 'multipart/form-data']) }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('pricing_name', 'Name *') }}
                                        {{ Form::text('pricing_name', null, ['class' => 'form-control',
                                        'placeholder' =>'Enter Name', 'required' => true, 'id' => 'sku_description']) }}
                                    </div>
                                </div>


                                {{ Form::select('pricing_type',['scheme'=>'Scheme','margin'=>'Margin'],null, ['class' =>
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
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
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


@section('scripts')
<script src="{{ asset('public/backend-assets/js/DynamicDropdown.js') }}"></script>

<script>
    // usama_15-03-2024-fetch bp-group from channel
    $('#bp_channel').change(function() {
        var selectedValue = $(this).val();
        new DynamicDropdown('{{ route('admin.getGroups') }}',
            selectedValue, '#bp_group')
    });
</script>
@endsection