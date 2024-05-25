@extends('backend.layouts.app')
@section('title', 'Edit PurchaseOrder')

@section('content')
@php
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\Company;

$bp_master = BussinessPartnerMaster::where('business_partner_id',$model->party_id)->first();
$company = Company::where('company_id',$bp_master->company_id)->first();
@endphp

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Purchase Order</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Purchase Order</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.purchaseorder') }}" class="btn btn-outline-secondary float-right"><i
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
                            {{ Form::model($model, ['url' => 'admin/purchaseorder/update', 'files' => true, 'class' =>
                            'w-100']) }}
                            {{ Form::hidden('financial_year', $fyear) }}
                            {{ Form::hidden('purchase_order_id', $model->purchase_order_id) }}
                            {{ Form::hidden('bill_to_state', null, ['class' => 'bill_to_state']) }}
                            {{ Form::hidden('party_name', null, ['class' => 'party_name']) }}
                            {{ Form::hidden('party_state', null, ['class' => 'party_state']) }}
                            {{ Form::hidden('bill_to_gst_no', null, ['class' => 'bill_to_gst_no']) }}
                            {{ Form::hidden('purchase_order_id', null, ['id' => 'purchase_order_id']) }}
                            <div class="form-body ">
                                <div class="row">

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            {{-- {{ Form::label('bill_date', 'PurchaseOrder Date *') }} --}}
                                            {{ Form::hidden('bill_date', date('Y-m-d'), [
                                            'class' => ' form-control
                                            digits',
                                            'placeholder' => 'PurchaseOrder Date',
                                            'required' => true,
                                            'readonly' => true,
                                            ]) }}
                                        </div>
                                    </div>


                                    <div class="col-sm-12">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">

                                                @php
                                                $values = array_keys($party->toArray());
                                                $party_code = array_combine($values, $values);
                                                @endphp


                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('party_code', 'Vendor Code *') }}
                                                        {{ Form::select('party_code', $party_code, $model->party_id, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_code',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('party_id', 'Vendor *') }}
                                                        {{ Form::select('party_id', $party, null, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_id',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{-- {{ Form::label('bill_to_gst_no', 'GSTIN/UIN') }} --}}
                                                        {{ Form::hidden('bill_to_gst_no', null, [
                                                        'class' => 'form-control bill_to_gst_no',
                                                        'placeholder' => 'GSTIN/UIN',
                                                        'readonly' => true,
                                                        ]) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('contact_person', 'Contact Person *') }}
                                                        <select name="contact_person" id="contact_person"
                                                            class="form-control" required></select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('po_temp_no', 'PO Order Number')
                                                        }}
                                                        {{ Form::text('po_temp_no', $model->bill_no, [
                                                        'class' => 'form-control
                                                        po_temp_no',
                                                        'placeholder' => 'PO Number',
                                                        'disabled' => true,
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('vendor_ref_no', 'Vendor PO Refrence Number *')
                                                        }}
                                                        {{ Form::text('vendor_ref_no', null, [
                                                        'class' => 'form-control
                                                        vendor_ref_no',
                                                        'placeholder' => 'Vendor PO Refrence Number',
                                                        'required' => true,
                                                        ]) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('ship_from', 'Ship From *') }}
                                                        <select name="ship_from" id="ship_from" class="form-control"
                                                            required></select>

                                                    </div>
                                                </div>


                                            </div>


                                        </div>

                                    </div>



                                    <div class="col-md-3 col-sm-3">
                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="form-group">
                                            {{ Form::label('status', 'Status *') }}
                                            {{ Form::select('status', ['open' => 'Open', 'close' => 'Close'], null, [
                                            'class' => 'form-control status',
                                            'placeholder' => 'Select Status',
                                            'required' => true,
                                            'readonly' => true,
                                            ]) }}
                                        </div>

                                      
                                        @if (isset($company) && $company->is_backdated_date)
                                        <div class="form-group">
                                            {{ Form::label('bill_date', 'Date *') }}
                                            {{ Form::date('bill_date', null, ['class' => 'form-control ', 'placeholder'
                                            => 'Date', 'required' => true]) }}
                                        </div>
                                        @endif


                                        <div class="form-group">
                                            {{ Form::label('posting_date', 'Posting Date *') }}
                                            {{ Form::date('posting_date', date('Y-m-d'), [
                                            'class' => 'form-control
                                            ',
                                            'placeholder' => 'Posting Date',
                                            'required' => true,
                                            'readonly' => true,
                                            ]) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('delivery_date', 'Delivery Date *') }}
                                            {{ Form::date('delivery_date', null, [
                                            'class' => 'form-control
                                            delivery_date',
                                            'placeholder' => 'Delivery Date',
                                            'required' => true,
                                            ]) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('document_date', 'Document Date *') }}
                                            {{ Form::date('document_date', null, [
                                            'class' => 'form-control
                                            document_date',
                                            'placeholder' => 'Document Date',
                                            'required' => true,
                                            ]) }}
                                        </div>


                                    </div>

                                    {{--
                                </div> --}}
                            </div>


                            <div class="row">

                                <div class="col-sm-12">
                                    <hr>
                                </div>

                                <div class="col-sm-12">
                                    <section id="form-repeater-wrapper">

                                        {{-- Repeater old --}}
                                        <!-- form default repeater -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="card-title">
                                                    Particulars Detail
                                                </h5>


                                                {{-- Repeater new --}}
                                                <!-- Repeater Items -->
                                                <div class="container-fluid table-responsive repeater">
                                                    <button type="button" data-repeater-create
                                                        class="btn btn-primary pull-right mb-2 add_btn_rep">Add</button>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered " id="repeater"
                                                            style="width:120%;">
                                                            <thead class="bg-light" style="font-size:10px;">
                                                                <tr>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label('item_code', 'Item Code') }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label(
                                                                        'item_name',
                                                                        'Item
                                                                        Description',
                                                                        ) }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label('hsn_sac', 'HSN/SAC') }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label('uom', 'UOM',['style'=>'display: flex;justify-content: center;']) }}
                                                                        {{ Form::select('uom',
                                                                        ['units'=>'Units','case'=>'Case'],
                                                                        $model->purchaseorder_items[0]->uom,
                                                                        [
                                                                            'class' => 'form-control uom',
                                                                            'data-name' => 'uom',
                                                                            'data-group' => 'invoice_items',
                                                                        ])
                                                                    }}
                                                                    </td>
                                                                    <td>{{ Form::label('qty', 'Quantity',['id'=>'qty_label']) }}
                                                                    </td>
                                                                    <td>{{ Form::label('final_qty', 'Quantity (Units)') }}
                                                                    </td>
                                                                    <td>{{ Form::label(
                                                                        'taxable_amount',
                                                                        'Unit
                                                                        Price',
                                                                        ) }}
                                                                    </td>

                                                                    <td>{{ Form::label('total', 'Total INR') }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label('GST', 'GST (%)') }}</td>
                                                                    <td>{{ Form::label('CGST', 'CGST (%)') }}</td>
                                                                    <td>{{ Form::label('SGST', 'SGST (%)') }}</td>
                                                                    <td>{{ Form::label('IGST', 'IGST (%)') }}</td>
                                                                    <td>{{ Form::label('Amount', 'GST Amount') }}
                                                                    </td>
                                                                    <td>{{ Form::label('gross_total', 'Gross Total') }}
                                                                    </td>
                                                                    <td>{{ Form::label('storage_location_id',
                                                                        'Warehouse') }}
                                                                    </td>
                                                                    <td></td>

                                                                </tr>
                                                            </thead>
                                                            <tbody data-repeater-list="old_invoice_items">
                                                                {{-- @if (isset($model->purchaseorder_items) &&
                                                                count($model->purchaseorder_items) > 0) --}}
                                                                @foreach ($model->purchaseorder_items as $items)
                                                                {{-- {{dd($items->toArray())}} --}}
                                                                <tr data-repeater-item class="item_row item-content"
                                                                    id="old_row_{{ $loop->index }}">

                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index .'][unit_pack]',$items->get_product->dimensions_unit_pack??null, ['data-group' => 'old_invoice_items']) }}
                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index .'][pack_case]',$items->get_product->unit_case??null, ['data-group' => 'old_invoice_items']) }}
                                                                    
                                                                    {{ Form::hidden(
                                                                    'old_invoice_items[' . $loop->index .
                                                                    '][purchase_order_item_id]',
                                                                    $items->purchase_order_item_id,
                                                                    [
                                                                    'data-name' => 'purchase_order_item_id',
                                                                    'class' => 'form-control item_name typeahead',
                                                                    'placeholder' => 'Description',
                                                                    'required' => true,
                                                                    'autocomplete' => 'off',
                                                                    ],
                                                                    ) }}


                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][cgst_amount]', $items->cgst_amount, [
                                                                    'class' => 'form-control
                                                                    custom-amount cgst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'cgst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ]) }}


                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][sgst_utgst_amount]', $items->sgst_utgst_amount,
                                                                    [
                                                                    'class' => 'form-control custom-amount
                                                                    sgst_utgst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'sgst_utgst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ]) }}


                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][igst_amount]', $items->igst_amount, [
                                                                    'class' => 'form-control
                                                                    custom-amount igst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'igst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ]) }}


                                                                    <td>{{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][item_code]',
                                                                        $items->item_code, [
                                                                        'data-name' => 'item_code',
                                                                        'class' => 'form-control
                                                                        item_code typeahead',
                                                                        'required' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td>{{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][item_name]',
                                                                        $items->item_name, [
                                                                        'data-name' => 'item_name',
                                                                        'class' => 'form-control
                                                                        item_name typeahead',
                                                                        'oninput' => 'validateInput(this)',
                                                                        ]) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][hsn_sac]', $items->hsn_sac, [
                                                                        'class' => 'form-control readonly',
                                                                        'data-name' => 'hsn_sac',
                                                                        ]) }}
                                                                    </td>

                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                            $loop->index . '][uom]',
                                                                            $items->uom,
                                                                            [
                                                                                'class' => 'form-control uom_field',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'uom',
                                                                                'data-group' => 'old_invoice_items',
                                                                                'readonly' => true,
                                                                            ])
                                                                        }}
                                                                    </td>

                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][qty]', $items->qty, [
                                                                        'class' => 'form-control qty',
                                                                        'oninput' => 'handleInput(this)',
                                                                        'data-name' => 'qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td>
                                                                        {{ Form::number('old_invoice_items[' .
                                                                        $loop->index . '][final_qty]', $items->final_qty, [
                                                                        'class' => 'form-control final_qty',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'final_qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        {{ Form::number('old_invoice_items[' .
                                                                        $loop->index . '][taxable_amount]',
                                                                        $items->taxable_amount, [
                                                                        'class' => 'form-control taxable_amount',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'taxable_amount',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        'required' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][discount_item]', $items->discount_item, [
                                                                    'class' => 'form-control
                                                                    discount_item',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'discount_item',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ]) }}

                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][price_af_discount]', $items->price_af_discount,
                                                                    [
                                                                    'class' => 'form-control price_af_discount',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'price_af_discount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    'readonly' => true,
                                                                    ]) }}

                                                                    <td>{{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][total]', $items->total, [
                                                                        'class' => 'form-control total',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'total',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td style="width: 130px;">
                                                                        @php
                                                                        $disable = true;
                                                                        if (old('party_id')) {
                                                                        $disable = false;
                                                                        }
                                                                        @endphp
                                                                        {{ Form::select('old_invoice_items[' .
                                                                        $loop->index . '][gst_rate]', $gst,
                                                                        $items->gst_rate, [
                                                                        'class' => 'form-control
                                                                        gst_dropdown readonly',
                                                                        'placeholder' => 'Select
                                                                        GST',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][cgst_rate]',
                                                                        $items->cgst_rate, [
                                                                        'class' => 'form-control
                                                                        custom-rate all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'cgst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][sgst_utgst_rate]',
                                                                        $items->sgst_utgst_rate, [
                                                                        'class' => 'form-control custom-rate all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'sgst_utgst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][igst_rate]',
                                                                        $items->igst_rate, [
                                                                        'class' => 'form-control
                                                                        custom-rate all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'igst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][gst_amount]',
                                                                        $items->gst_amount, [
                                                                        'class' => 'form-control readonly',
                                                                        'placeholder' => 'GST
                                                                        Amount',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gst_amount',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td>{{ Form::text('old_invoice_items[' .
                                                                        $loop->index . '][gross_total]',
                                                                        $items->gross_total, [
                                                                        'class' => 'form-control gross_total',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gross_total',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td style="width: 210px;">
                                                                        {{ Form::select(
                                                                        'old_invoice_items[' . $loop->index .
                                                                        '][storage_location_id]',
                                                                        $storage_locations,
                                                                        $items->storage_location_id,
                                                                        [
                                                                        'class' => 'form-control storage_locations
                                                                        selected_' . $items->storage_location_id,
                                                                        'data-selected' => $items->storage_location_id,
                                                                        'data-name' => 'storage_location_id',
                                                                        'required' => true,
                                                                        ],
                                                                        ) }}


                                                                    </td>
                                                                    <td><button type='button'
                                                                            class='btn btn-danger btn-flat btn-xs old_rep_item_del'
                                                                            id="{{ $loop->index }}"
                                                                            data-repeater-delete><i
                                                                                class='fa fa-fw fa-remove'></i></button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                                {{-- @endif --}}

                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>

                                    </section>

                                </div>







                                <div class="col-sm-12">
                                    <hr>
                                </div>
                            </div>
                            {{--
                            <hr> --}}

                            <div class="row">


                                <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{ Form::label('remarks', 'Remarks') }}
                                        {{ Form::textarea('remarks', null, [
                                        'class' => 'form-control remarks',
                                        'placeholder' => 'Remarks',
                                        'style' => 'height:100px;',
                                        ]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                </div>

                                <div class="col-md-3 col-sm-3">
                                    <div>
                                        <p>Total Before Discount: <strong><span class="total_amount">0</span></strong>
                                        </p>
                                    </div>
                                    <div>
                                        <p>Discount: <strong><input type="number" name="discount" id="discount"
                                                    style="width:30px;" value="{{ $model->discount }}"
                                                    oninput="calculate_grand_total()">
                                                %</strong>
                                            <input class="discount_amt w-25 readonly" value="">
                                        </p>
                                    </div>
                                    <div>
                                        <p>Total After Discount: <strong><span class="total_af_disc"></span>0</strong>
                                        </p>
                                    </div>

                                    <div>
                                        <p>Tax: <strong><span class="gst_total">0</span></strong></p>
                                    </div>
                                    <div>
                                        <p>Rounding: <strong><span class="rounding"></span></strong></p>
                                    </div>
                                    <div>
                                        <p>Total Payment Due: <strong><span class="final_amount">0</span></strong>
                                        </p>
                                    </div>
                                </div>


                            </div>

                            <div class="col-sm-12 d-flex justify-content-center">
                                {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1 ']) }}
                                <button type="reset" class="btn btn-light-secondary mr-1 mb-1 ">Reset</button>
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

       
    function handleInput(elem) {
        calculategst(elem);
        validateQty(elem);
    }


    function get_data_display(customer_id) {

            // alert(customer_id);

            $.get(APP_URL + '/admin/purchaseorder/partydetails/' + customer_id, {}, function(
                response) {
                var customer_details = $.parseJSON(response);
                // console.log(customer_details);
                if (customer_details) {
                    $(".party").html(customer_details.party_detail);
                    $(".party_input").val(customer_details.party_detail);
                    $(".bill_to_state").val(customer_details.bill_to_state);
                    $(".party_state").val(customer_details.party_state);
                    $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);
                    $(".party_name").val(customer_details.party_name);


                    if (customer_details.bill_to_state != '' && customer_details.party_state != '') {
                        $(".gst_dropdown").prop('disabled', false);
                    }

                } else {
                    // alert('nop res');
                    $(".pary").html("");
                    $(".party_input").val('');
                    $(".bill_to_state").val('');
                    $(".party_state").val('');
                }
            });

        }
        $(document).ready(function() {

            var customer_id = $('#party_id option:selected,#party_code option:selected').val();
            // alert(customer_id);
            if (customer_id != "") {
                get_data_display(customer_id);
            }
            $("#party_id,#party_code").on('change', function() {
                $(".gst_dropdown").val('');
                $(".all_gst").val('');

                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
            });





            // warehouse change event 

            //for new bacthes click
            $(document).on('click', '.storage_locations option:selected', function(e) {
                if ($(this).val() != '') {
                    var parent_div = $(this).parents()[2];
                    var suffix = $(this).attr('name');
                    $(this).closest('.item_row').find('.modal').modal('show');
                }
            });





        });
</script>


{{-- repeater, bactches concept --}}
<script>
    var generateId = function(string) {
            return string
                .replace(/\[/g, '_')
                .replace(/\]/g, '')
                .toLowerCase();
        };

        function getFirstNumber(string) {
            // alert(string);
            const regex = /[1-9]/; // Regular expression to match any number from 1 to 9
            const match = string.match(regex);

            if (match) {
                return match[0];
            }

            return null; // If no match found
        }


        function get_invoice_itemnames() {
            setTimeout(() => {
                var itemContent = $('.modal');
                // var group = itemContent.data("group");

                var item = itemContent;
                var input = item.find('.modal_items');

                // console.log(input);
                input.each(function(index, el) {
                    // console.log(el.name);
                    var attrName = $(el).attr('name');
                    var dataName = $(el).data('name');
                    // alert(attrName);
                    // console.log(attrName);
                    var key = attrName.match(/\d+/)[0];
                    // alert(key);
                    var skipName = $(el).data('skip-name');
                    // console.log('attrName',attrName);
                    var group = $(el).data("group");
                    // alert(group);


                    if (key == 0) {
                        $(el).attr("name", group + "[" + key + "]" + "[" + dataName + "]");
                    } else {
                        var key = getFirstNumber(attrName);
                        $(el).attr("name", group + "[" + key + "]" + "[" + dataName + "]");
                    }


                    $(el).attr('id', generateId($(el).attr('name')));
                    $(el).parent().find('label').attr('for', generateId($(el).attr('name')));
                });
            }, 200);
        }




        $(document).ready(function() {
            /* Create Repeater */
            calculate_grand_total();

            $('.repeater').repeater({
                isFirstItemUndeletable: false,
                // initEmpty: false,
            });

            // console.log('test');

            //practice code
            get_invoice_itemnames();

            $('.add_btn_rep').click(function() {

                //set default first warehouse
                $('.storage_locations').each(function() {
                   $(this).val($(this).find('option:first').val()); // Set the default value to the first option
                });

                get_invoice_itemnames();
                var lastIndex = $('.repeater tbody tr').length - 1;
                $("input[name='old_invoice_items[" + lastIndex + "][uom]']").val($("input[name='old_invoice_items[0][uom]']").val());
            });




            // 08-01-2024 -usama
            $('#party_code').on('change', function() {
                $('#party_id').val($(this).val()).trigger('change.select2');
            });

            $('#party_id').on('change', function() {
                $('#party_code').val($(this).val()).trigger('change.select2');
            });


        });

        $("#discount").on('change', function() {
            calculate_grand_total();
        });
</script>



@include('backend.autocomplete_typeahead_script')

@endsection