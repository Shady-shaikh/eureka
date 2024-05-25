@extends('backend.layouts.app')
@section('title', 'Edit Beat Calender')
@php
use Spatie\Permission\Models\Role;
use Carbon\CarbonPeriod;
@endphp
@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Beat Calender</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.beatcalender') }}">Beat Calender</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.beatcalender') }}">
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
                        {{ Form::open(['url' => 'admin/beatcalender/update']) }}
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                {{ Form::hidden('beat_cal_id', $model->beat_cal_id, ['class' => 'form-control']) }}



                                <div class="col-md-6 col-6">
                                    <div class="form-group">

                                        {{ Form::label('beat_month', 'Beat Month *') }}
                                        {{ Form::select('beat_month',$all_data['month'],$model->beat_month, ['class' =>
                                        'form-control tags', 'placeholder' => 'Select Beat Month', 'required' => true])
                                        }}

                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">


                                        {{ Form::label('beat_week', 'Beat Week *') }}
                                        {{ Form::select('beat_week', $all_data['week'],$model->beat_week, ['class' =>
                                        'form-control ', 'placeholder' => 'Select Beat Week', 'required' => true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group">

                                        {{ Form::label('beat_day', 'Beat Day *') }}
                                        {!! Form::select('beat_day',$all_data['days'] ,
                                        $model->beat_day, ['class' => 'form-control tags']) !!}

                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('beat_id', 'Beat *') }}

                                        {!! Form::select('beat_id',$beats ,
                                        $model->beat_id, ['class' => 'form-control tags']) !!}
                                    </div>
                                </div>




                                <div class="col-md-6 col-6 d-none">
                                    <div class="form-group">

                                        {{ Form::label('beat_year', 'Beat Year *') }}
                                        {{ Form::select('beat_year',$all_data['year'],$model->beat_year, ['class' =>
                                        'form-control tags', 'placeholder' => 'Select Beat Year', 'required' => true])
                                        }}

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
                    </div>

                </div>


                {{ Form::close() }}
            </div>
        </div>
    </div>

    </div>
</section>
@endsection