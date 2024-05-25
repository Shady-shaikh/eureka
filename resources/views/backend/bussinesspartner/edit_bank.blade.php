@extends('backend.layouts.app')
@section('title', 'Edit Bank Details')
@php
use Spatie\Permission\Models\Role;
@endphp
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Edit Bank Details</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ url()->previous() }}">
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
                            @php
                                $role = Role::get(['id', 'name'])->toArray();
                            @endphp
                            @include('backend.includes.errors')
                            {{ Form::open(['url' => 'admin/bussinesspartner/storebank']) }}
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::hidden('banking_details_id', $id, ['class' => 'form-control']) }}
                                            {{ Form::label('bank_name', 'Bank Name *') }}
                                            {{ Form::text('bank_name', $bankdetails->bank_name, ['class' => 'form-control', 'placeholder' => 'Enter Bank Name', 'required' => true]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('bank_branch', 'Branch Name *') }}
                                            {{ Form::text('bank_branch', $bankdetails->bank_branch, ['class' => 'form-control', 'placeholder' => 'Enter Branch Name', 'required' => true]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('acc_holdername', 'Account Holder *') }}
                                            {{ Form::text('acc_holdername', $bankdetails->acc_holdername, ['class' => 'form-control', 'placeholder' => 'Enter Account Holder', 'required' => true]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('ifsc', 'IFSC Code *') }}
                                            {{ Form::text('ifsc', $bankdetails->ifsc, ['class' => 'form-control', 'placeholder' => 'Enter IFSC Code', 'required' => true]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('ac_number', 'Account No *') }}
                                            {{ Form::text('ac_number', $bankdetails->ac_number, ['class' => 'form-control', 'placeholder' => 'Enter Account No.', 'required' => true]) }}
                                        </div>
                                    </div>

            
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('bank_address', 'Bank Address *') }}
                                            {{ Form::text('bank_address', $bankdetails->bank_address, ['class' => 'form-control', 'placeholder' => 'Enter Bank Address.', 'required' => true, 'rows' => 2, 'cols' => 5, 'id' => 'bank_address', 'style' => "resize:none"  ]) }}
                                        </div>
                                    </div>
                                    </div>

                                <div class="row">
                                    <div class="col md-12">
                                        {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                        <button type="reset" class="btn btn-dark mr-1 mb-1">Reset</button>
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
