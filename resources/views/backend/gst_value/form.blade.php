@extends('backend.layouts.app')
@section('title', Request::segment(4) === 'edit' ? 'Edit Gst Value' : 'Create Gst Value')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ Request::segment(4) === 'edit' ? 'Edit' : 'Create' }} Gst Value</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('gst_value.index')}}">Gst Values</a></li>
                    <li class="breadcrumb-item active">{{ Request::segment(4) === 'edit' ? 'Edit' : 'Create' }} Gst
                        Value</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        @include('backend.includes.errors')
                        @if(Request::segment(4) === 'edit')
                        {{ Form::model($data, ['route' => ['gst_value.update', $data->id], 'method'
                        => 'PUT']) }}
                        @else
                        {{ Form::open(['route' => 'gst_value.store']) }}
                        @endif
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">

                                        {{ Form::label('value', 'Percentage Value *') }}
                                        {{ Form::number('value', Request::segment(4) ===
                                        'edit'?$data->value:null, ['class' => 'form-control',
                                        'placeholder'=> 'Enter Percentage', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-start">
                                    {{ Form::submit('Save', array('class' => 'btn btn-primary mr-1 mb-1')) }}
                                    <button type="reset"
                                        class="btn btn-light-secondary mr-1 mb-1 text-white">Reset</button>
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