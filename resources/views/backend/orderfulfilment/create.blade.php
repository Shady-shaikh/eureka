@extends('backend.layouts.app')
@section('title', 'Create Goods & Service Receipts')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Goods Service Receipts</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Goods Service Receipts</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.goodsservicereceipts') }}" class="btn btn-outline-secondary float-right"><i
                            class="bx bx-arrow-back"></i><span class="align-middle ml-25">Back</span></a>
                </div>
            </div>
        </div>
    </div>

    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="content-header row">

                                @include('backend.includes.errors')
                                {{ Form::open(['url' => 'admin/goodsservicereceipts/store', 'files' => true]) }}
                                {{ Form::hidden('financial_year', $fyear) }}
                                {{ Form::hidden('bill_to_state', '', ['class' => 'bill_to_state']) }}
                                {{ Form::hidden('party_state', '', ['class' => 'party_state']) }}
                                {{ Form::hidden('bill_to_gst_no', '', ['class' => 'bill_to_gst_no']) }}
                                <div class="form-body">
                                    <div class="row">

                                        <div class="col-sm-3">
                                            {{ Form::label('bill_no', 'Goods/Service Receipt No') }}
                                            <h4>EUREKA/{{ $fyear }}/{{ $purchase_order_counter }}</h4>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                {{ Form::label('bill_date', 'PurchaseOrder Date *') }}
                                                {{ Form::text('bill_date', date('Y-m-d'), ['class' => ' form-control digits', 'placeholder' => 'PurchaseOrder Date', 'required' => true, 'readonly' => true]) }}
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-12 d-none">
                                            <div class="form-group">
                                                {{ Form::label('company_gstin', 'GSTIN *') }}
                                                {{ Form::text('company_gstin', null, ['class' => 'form-control', 'placeholder' => 'GSTIN']) }}
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                {{ Form::label('receipt_type', 'Receipt Type *') }}
                                                {{ Form::select('receipt_type', ['goods' => 'Goods', 'service' => 'Service'], '', ['class' => 'form-control select2', 'id' => 'bill_to', 'placeholder' => 'Select Receipt Type', 'required' => true]) }}
                                            </div>
                                        </div>



                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('party_id', 'Bill To *') }}
                                                            {{ Form::select('party_id', $party, '', ['class' => 'form-control select2', 'id' => 'bill_to', 'placeholder' => 'Select Bill To', 'required' => true]) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('bill_to_gst_no', 'GSTIN/UIN') }}
                                                            {{ Form::text('bill_to_gst_no', '', ['class' => 'form-control bill_to_gst_no', 'placeholder' => 'GSTIN/UIN']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="col-sm-12">
                                                        <h5>Bill Details</h5>
                                                        <p class="party">No Details To Display</p>
                                                        <textarea name="party_details" class="party_input d-none"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">

                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('po_document', 'Upload Goods Service Receipts Photocopy') }}
                                                    {{ Form::file('po_document', ['class' => 'form-control', 'id' => 'po_document']) }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-12">
                                            <hr>
                                        </div>

                                        <div class="col-sm-12">
                                            <section id="form-repeater-wrapper">
                                                <!-- form default repeater -->
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="card-title">
                                                            Particulars Detail
                                                        </h5>

                                                        {{-- This is Repeater --}}
                                                        <div id="repeater">
                                                            <!-- Repeater Heading -->
                                                            <div class="repeater-heading">
                                                                {{--  <h5 class="pull-left">Repeater</h5>  --}}
                                                                <span class="btn btn-primary pull-right repeater-add-btn">
                                                                    Add
                                                                </span>
                                                            </div>

                                                            <!-- Repeater Items -->
                                                            <div class="row w-100">
                                                                <div class="items" data-group="invoice_items">
                                                                    <!-- Repeater Content -->
                                                                    <div class="item-content">
                                                                        <div class="form repeater-default">
                                                                            <div data-repeater-list="invoice_items">
                                                                                <div data-repeater-item>
                                                                                    <div
                                                                                        class="row justify-content-between">
                                                                                        <div
                                                                                            class="col-md-2 col-sm-12 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('item_name', 'Description') }}
                                                                                                {{ Form::text('item_name', null, ['data-name' => 'item_name', 'class' => 'form-control item_name typeahead', 'placeholder' => 'Description', 'required' => true, 'autocomplete' => 'off']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-2 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('hsn_sac', 'HSN/SAC') }}
                                                                                                {{ Form::text('hsn_sac', null, ['class' => 'form-control', 'placeholder' => 'HSN/SAC', 'data-name' => 'hsn_sac']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('storage_location_id', 'Warehouse') }}
                                                                                                {{ Form::select('storage_location_id', $storage_locations, null, ['class' => 'form-control storage_locations', 'placeholder' => 'Warehouse', 'data-name' => 'storage_location_id']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('qty', 'Quantity') }}
                                                                                                {{ Form::text('qty', null, ['class' => 'form-control qty', 'placeholder' => 'Quantity', 'onchange' => 'calculategst(this)', 'data-name' => 'qty']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('taxable_amount', 'Rate') }}
                                                                                                {{ Form::text('taxable_amount', null, ['class' => 'form-control taxable_amount', 'placeholder' => 'Rate', 'onchange' => 'calculategst(this)', 'data-name' => 'taxable_amount']) }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('total', 'Total INR') }}
                                                                                                {{ Form::text('total', null, ['class' => 'form-control total', 'placeholder' => 'Total', 'onchange' => 'calculategst(this)', 'data-name' => 'total']) }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('cgst_rate', 'CGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('cgst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_rate']) }}
                                                                                                {{ Form::text('cgst_amount', null, ['class' => 'form-control custom-amount cgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('sgst_utgst_rate', 'SGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('sgst_utgst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'sgst_utgst_rate']) }}
                                                                                                {{ Form::text('sgst_utgst_total', null, ['class' => 'form-control custom-amount sgst_utgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount', 'data-name' => 'sgst_utgst_amount']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('igst_rate', 'IGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('igst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_rate']) }}
                                                                                                {{ Form::text('igst_amount', null, ['class' => 'form-control custom-amount igst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_amount']) }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 d-flex align-items-center pl-0 pr-0">
                                                                                            <div
                                                                                                class="pull-right repeater-remove-btn">
                                                                                                <button
                                                                                                    class="btn btn-danger remove-btn">
                                                                                                    Delete
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr>

                                                                                    {{--  Modal starts Here  --}}
                                                                                    <div class="modal fade text-left"
                                                                                        tabindex="-1" role="dialog"
                                                                                        aria-labelledby="myModalLabel1"
                                                                                        aria-hidden="true">
                                                                                        <div class="modal-dialog modal-lg"
                                                                                            role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h4 class="modal-title"
                                                                                                        id="myModalLabel1">
                                                                                                        Assign Batch</h4>
                                                                                                    <button type="button"
                                                                                                        class="close"
                                                                                                        data-dismiss="modal"
                                                                                                        aria-label="Close">
                                                                                                        <span
                                                                                                            aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <div class="row">
                                                                                                        <div
                                                                                                            class="col-md-12 table-responsive">
                                                                                                            <table
                                                                                                                class="table table-sm">
                                                                                                                <!-- innner repeater -->

                                                                                                                <tr>
                                                                                                                    <td>Sr.
                                                                                                                        No
                                                                                                                    </td>
                                                                                                                    <td>Batch
                                                                                                                        no
                                                                                                                    </td>
                                                                                                                    <td>Mfg.
                                                                                                                        Date
                                                                                                                    </td>
                                                                                                                    <td>Exp.
                                                                                                                        Date
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>1
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][0][batch_no"
                                                                                                                            data-name="batches][0][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][0][manufacturing_date"
                                                                                                                            data-name="batches][0][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][0][expiry_date"
                                                                                                                            data-name="batches][0][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>2
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][1][batch_no"
                                                                                                                            data-name="batches][1][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][1][manufacturing_date"
                                                                                                                            data-name="batches][1][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][1][expiry_date"
                                                                                                                            data-name="batches][1][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>3
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][2][batch_no"
                                                                                                                            data-name="batches][2][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][2][manufacturing_date"
                                                                                                                            data-name="batches][2][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][2][expiry_date"
                                                                                                                            data-name="batches][2][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>4
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][3][batch_no"
                                                                                                                            data-name="batches][3][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][3][manufacturing_date"
                                                                                                                            data-name="batches][3][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][3][expiry_date"
                                                                                                                            data-name="batches][3][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>5
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][4][batch_no"
                                                                                                                            data-name="batches][4][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][4][manufacturing_date"
                                                                                                                            data-name="batches][4][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][4][expiry_date"
                                                                                                                            data-name="batches][4][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>6
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][5][batch_no"
                                                                                                                            data-name="batches][5][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][5][manufacturing_date"
                                                                                                                            data-name="batches][5][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][5][expiry_date"
                                                                                                                            data-name="batches][5][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>7
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][6][batch_no"
                                                                                                                            data-name="batches][6][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][6][manufacturing_date"
                                                                                                                            data-name="batches][6][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][6][expiry_date"
                                                                                                                            data-name="batches][6][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>8
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][7][batch_no"
                                                                                                                            data-name="batches][7][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][7][manufacturing_date"
                                                                                                                            data-name="batches][7][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][7][expiry_date"
                                                                                                                            data-name="batches][7][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>9
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][8][batch_no"
                                                                                                                            data-name="batches][8][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][8][manufacturing_date"
                                                                                                                            data-name="batches][8][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][8][expiry_date"
                                                                                                                            data-name="batches][8][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                                <tr>
                                                                                                                    <td>10
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="text"
                                                                                                                            name="batches][9][batch_no"
                                                                                                                            data-name="batches][9][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            name="batches][9][manufacturing_date"
                                                                                                                            data-name="batches][9][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            name="batches][9][expiry_date"
                                                                                                                            data-name="batches][9][expiry_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                </tr>

                                                                                                            </table>


                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="modal-footer">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn grey btn-outline-secondary"
                                                                                                            data-dismiss="modal">Ok</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Repeater End --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>



                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>Sub Total <strong><span class="sub_total">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>CGST Total: <strong><span class="cgst_total">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>SGST Total: <strong><span class="sgst_utgst_total">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>IGST Total: <strong><span class="igst_total">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>GST Total: <strong><span class="gst_total">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>Total Amount: <strong><span class="total_amount">0</span></strong></p>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <p>Total Amount In Words: <strong><span
                                                        class="total_amount_words">-</span></strong></p>
                                        </div>

                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 d-flex justify-content-center">
                                        {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    </div>
    </div>

    <script>
        $(document).ready(function() {


            $("#bill_to").change(function() {
                var customer_id = $(this).val();
                if (customer_id != '') {
                    $.get(APP_URL + '/admin/goodsservicereceipts/partydetails/' + customer_id, {}, function(
                        response) {
                        var customer_details = $.parseJSON(response);
                        $(".party").html(customer_details.party_detail);

                        $(".party_input").val(customer_details.party_detail);
                        $(".bill_to_state").val(customer_details.bill_to_state);
                        $(".party_state").val(customer_details.party_state);
                        $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);
                    });
                } else {
                    $(".party").html("");

                    $(".party_input").val('');
                    $(".bill_to_state").val('');
                    $(".party_state").val('');
                }
            });
            $(document).on('change', '.storage_locations', function() {
                var parent_div = $(this).parents()[2];
                var suffix = $(this).attr('name');
                // $('.modal').modal('show');
                $(this).closest('.item-content').find('.modal').modal('show');
                console.log($(this).closest('.item-content').find('.modal'));


            });

            ;
        });
    </script>
    <script>
        /* Create Repeater */
        $("#repeater").createRepeater({
            showFirstItemToDefault: true,
        });
    </script>

    @include('backend.goodsservicereceipts.autocomplete_typeahead_script')

@endsection
