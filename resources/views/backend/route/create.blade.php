@extends('backend.layouts.app')
@section('title', 'Route')
@php
    use Spatie\Permission\Models\Role;
@endphp
@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Create Route</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.route') }}">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ route('admin.route') }}">
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
                            {{ Form::open(['url' => 'admin/route/store']) }}
                            @csrf
                            <div class="form-body">
                                <div class="row">

                                    {{-- {{dd($area_data)}} --}}
                                    <div class="col-md-6 col-6">
                                        <div class="form-label-group">
                                            {{ Form::label('area_id', 'Area *') }}
                                            {{ Form::select('area_id',$area_data,'', array('class' => 'form-control select2','id'=>'','placeholder'=>'Select Area','required'=>true)) }}
                                        </div>
                                    </div>
                 
                                    <div class="col-md-6 col-6">
                                        <div class="form-label-group">
                                            {{ Form::label('route_name', 'Route Name *') }}
                                            {{ Form::text('route_name', null, ['class' => 'form-control', 'placeholder' => 'Route Name', 'required' => true]) }}
                                        </div>
                                    </div>



                                    
                                </div>
                                <div class="row mt-3">
                                    <div class="col md-12 ">
                                        {{-- <center> --}}
                                            {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                            <button type="reset" class="btn btn-secondary mr-1 mb-1">Reset</button>
                                        {{-- </center> --}}
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
