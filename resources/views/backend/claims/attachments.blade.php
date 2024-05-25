@extends('backend.layouts.app')
@section('title', 'Attachments')
@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Attachments</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ route('admin.claims') }}">Claims</a>
                    </li>
                    <li class="breadcrumb-item active">Attachments</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.claims') }}">
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
                        {{ Form::open(['url' => 'admin/claims/attachments_store','enctype'=>'multipart/form-data']) }}
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{$id}}">
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        {{ Form::label('retailer_vendor_dn', 'Retailer/ Vendor DN *') }}
                                        {{ Form::file('retailer_vendor_dn', [ 'class' =>'form-control','accept' =>
                                        '.doc,.docx, .xls, .xlsx, .pdf, .csv','required'=>true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        {{ Form::label('distributor_dn', 'Distributor DN *') }}
                                        {{ Form::file('distributor_dn', [ 'class' => 'form-control', 'accept' => '.doc,
                                        .docx, .xls, .xlsx, .pdf, .csv','required'=>true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        {{ Form::label('invoice_supp_docs', 'Invoice Supporting Docs *') }}
                                        {{ Form::file('invoice_supp_docs', [ 'class' => 'form-control', 'accept' =>
                                        '.doc, .docx, .xls, .xlsx, .pdf, .csv','required'=>true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        {{ Form::label('approval_supp_docs', 'Approval Supproting Docs *') }}
                                        {{ Form::file('approval_supp_docs', [ 'class' => 'form-control', 'accept' =>
                                        '.doc, .docx, .xls, .xlsx, .pdf, .csv','required'=>true]) }}
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2">
                                        {{ Form::submit('Upload', ['class' => 'btn btn-primary']) }}
                                        <button type="reset" class="btn btn-secondary ">Reset</button>

                                    </div>
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