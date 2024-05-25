@extends('backend.layouts.app')
@section('title', 'Bussiness Partner Contact')
@php
use Spatie\Permission\Models\Role;
@endphp
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Create Bussiness Partner Contact</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
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
    @if (isset($contact) &&  count($contact->toArray() ) > 0)
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            @include('backend.includes.errors')
                            {{ Form::open(['url' => 'admin/bussinesspartner/storecontact']) }}
                            @csrf
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-12 col-12">
                                        <h4><br>Address Details<br></h4>
                                    </div>

                                    <div class="col-md-6 col-12">

                                        <div class="form-label-group">
                                            {{ Form::label('type', 'Address Type') }}
                                            {{ Form::hidden('contact_details_id', $contact->contact_details_id, ['class' => 'form-control', 'placeholder' => 'Address Type', 'required' => true]) }}
                                            <select name="type" id="address_type" class='form-control'>
                                                
                                                <option value="Bill-To/ Bill-From" @if ($contact->type == "Bill-To/ Bill-From")
                                                    selected
                                                @endif>Bill-To/ Bill-From</option>
                                                <option value="Ship-To/ Ship-From" @if ($contact->type == "Ship-To/ Ship-From")
                                                    selected
                                                @endif>Ship-To/ Ship-From</option>
                                            </select>
                                  
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            {{ Form::label('contact_person', 'Contact Person Name *') }}
                                            {{ Form::text('contact_person', $contact->contact_person, ['class' => 'form-control', 'placeholder' => 'Contact Person Name', 'required' => true]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            {{ Form::label('email_id', 'Enter Email *') }}
                                            {{ Form::text('email_id', $contact->email_id, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) }}
                                    
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            {{ Form::label('mobile_no', 'Enter Mobile No *') }}
                                            {{ Form::text('mobile_no', $contact->mobile_no, ['class' => 'form-control', 'placeholder' => 'Enter Mobile No', 'required' => true]) }}
                                        
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            {{ Form::label('landline', 'Enter landline No') }}
                                            {{ Form::text('landline', $contact->landline, ['class' => 'form-control', 'placeholder' => 'Enter Landline Number Landline']) }}
                                      
                                        </div>
                                    </div>
                                </div> {{-- main row --}}

                            </div>
                            <div class="col md-12 center mt-3">
                                <center>
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                                </center>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    @endif

@endsection
