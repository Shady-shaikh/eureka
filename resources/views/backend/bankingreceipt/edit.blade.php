@extends('backend.layouts.app')
@section('title', 'Edit Beat')
@php
    use Spatie\Permission\Models\Role;
@endphp
@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Edit Beat</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.beat') }}">Beat</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ route('admin.beat') }}">
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
                            {{ Form::open(['url' => 'admin/beat/update']) }}
                            @csrf
                            <div class="form-body">
                                <div class="form-body">
                                    <div class="row">
                                        {{ Form::hidden('beat_id', $model->beat_id, ['class' => 'form-control']) }}
                                      
                                            <div class="col-md-6 col-6">
                                                <div class="form-label-group">
                                                    {{ Form::label('area_id', 'Area *') }}
                                                    {{ Form::select('area_id',$area_data,$model->area_id, array('class' => 'form-control select2','id'=>'area_field','placeholder'=>'Select Area','required'=>true)) }}
                                                </div>
                                            </div>
        
                                              <div class="col-md-6 col-6">
                                                <div class="form-label-group">
                                                    {{ Form::label('route_id', 'Route *') }}
                                                    <select name="route_id" id="routes" class="form-control select2"></select>
                                                </div>
                                            </div>
                    
        
                                        </div>
                                    <div class="row">

                                        <div class="col-md-6 col-6">
                                            <div class="form-label-group">
                                                {{ Form::label('beat_number', 'Beat Number *') }}
                                                {{ Form::text('beat_number', $model->beat_number, ['class' => 'form-control', 'placeholder' => 'Beat Number', 'required' => true]) }}
                                            </div>
                                        </div>

                                    <div class="col-md-6 col-6">
                                        <div class="form-label-group">
                                            {{ Form::label('beat_name', 'Beat Name *') }}
                                            {{ Form::text('beat_name', $model->beat_name, ['class' => 'form-control', 'placeholder' => 'Beat Name', 'required' => true]) }}
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
                            </div>

                        </div>


                        {{ Form::close() }}
                    </div>
                </div>
            </div>
 
        </div>
    </section>
@endsection
