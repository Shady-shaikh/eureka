@extends('backend.layouts.app')
@section('title', 'Focus Pack')
@php
use Spatie\Permission\Models\Role;
use Carbon\CarbonPeriod;
@endphp
@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Focus Pack</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.focusmap') }}">Focus Pack (Must Sell Pack)</a>
                    </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.focusmap') }}">
                    Back
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
                        {{ Form::open(['url' => 'admin/focusmap/store']) }}
                        @csrf
                        <div class="form-body">
                            <div class="row">



                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('brand_id', 'Brand *') }}

                                        {!! Form::select('brand_id',$brands ,
                                        null, ['class' => 'form-control select2','placeholder'=>'Select
                                        Brand','required'=>true]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('format_id', 'Format *') }}

                                        {!! Form::select('format_id',[] ,
                                        null, ['class' => 'form-control select2','placeholder'=>'Select
                                        Format','required'=>true]) !!}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('product_id', 'Product *') }}

                                        {!! Form::select('product_id',[] ,
                                        null, ['class' => 'form-control select2','placeholder'=>'Select
                                        Product','required'=>true]) !!}
                                    </div>
                                </div>


                                <div class="col-md-6 col-6">
                                    <div class="form-group">

                                        {{ Form::label('month', 'Month *') }}
                                        {{ Form::select('month',$all_data['month'],null, ['class' => 'form-control
                                        tags',
                                        'placeholder' => 'Select Month', 'required' => true]) }}

                                    </div>
                                </div>

                            </div>




                            <div class="row mt-3">
                                <div class="col md-12 ">
                                    {{-- <center> --}}
                                        {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                        <button type="reset" class="btn btn-secondary mr-1 mb-1">Reset</button>
                                        {{--
                                    </center> --}}
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
    $('#brand_id').change(function() {
        new DynamicDropdown('{{ route('admin.get_format') }}',
            $(this).val(), '#format_id');
    });

    $('#format_id').change(function() {
        new DynamicDropdown('{{ route('admin.get_product') }}',
            $(this).val(), '#product_id');
    });
</script>
@endsection