@extends('backend.layouts.app')
@section('title', 'Edit Area')
@php
    use Spatie\Permission\Models\Role;
@endphp
@section('content')
    {{-- {{ dd($bussinesspartner->toArray()) }} --}}

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Edit Area</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.area') }}">Area</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ route('admin.area') }}">
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
                            {{ Form::open(['url' => 'admin/area/update']) }}
                            @csrf
                            <div class="form-body">
                                <div class="form-body">
                                    <div class="row">
                                        {{ Form::hidden('area_id', $model->area_id, ['class' => 'form-control']) }}
                     
                                        <div class="col-md-6 col-6">
                                            <div class="form-label-group">
                                                {{ Form::label('area_name', 'Area Name *') }}
                                                {{ Form::text('area_name', $model->area_name, ['class' => 'form-control', 'placeholder' => 'Area Name', 'required' => true]) }}
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
