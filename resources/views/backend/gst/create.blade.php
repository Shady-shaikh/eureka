@extends('backend.layouts.app')
@section('title', 'Create Gst')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create GST</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">GST</li>
                    <li class="breadcrumb-item active">Create GST</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.gst.index') }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="app-content content">
    <div class="content-overlay"></div>
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            @include('backend.includes.errors')
                            {{ Form::open(array('url' => 'admin/gst/store')) }}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            {{ Form::label('gst_name', 'GST Name *') }}
                                            {{ Form::text('gst_name', null, ['class' => 'form-control', 'placeholder' => 'Enter GST Name', 'required' => true]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            {{ Form::label('gst_percent', 'GST Percent (%) | (Only numbers accepted)') }}
                                            {{ Form::text('gst_percent', null, ['class' => 'form-control', 'placeholder' => 'Enter GST Percent (%)']) }}
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                    </div>

                                    <div class="col-12 d-flex justify-content-center">
                                        {{ Form::submit('Save', array('class' => 'btn btn-primary mr-1 mb-1')) }}
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
</div>
@endsection
