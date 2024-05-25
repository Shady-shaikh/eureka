@extends('backend.layouts.app')
@section('title', 'Create A/P Invoice')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">A/P Invoice</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">A/P Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.apinvoice') }}" class="btn btn-outline-secondary float-right"><i
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
                                {{ Form::open(['url' => 'admin/apinvoice/store']) }}
                                {{ Form::hidden('financial_year', $fyear) }}
                                {{ Form::hidden('bill_to_state', '', ['class' => 'bill_to_state']) }}
                                {{ Form::hidden('party_name', '', ['class' => 'party_name']) }}
                                {{ Form::hidden('party_state', '', ['class' => 'party_state']) }}
                                {{ Form::hidden('bill_to_gst_no', '', ['class' => 'bill_to_gst_no']) }}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            {{ Form::label('bill_no', 'A/P Invoice No') }}
                                            <h4>EUREKA/{{ $fyear }}/{{ $purchase_order_counter }}</h4>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                {{ Form::label('bill_date', 'A/P Invoice Date *') }}
                                                {{ Form::text('bill_date', date('Y-m-d'), ['class' => ' form-control digits', 'placeholder' => 'A/P Invoice Date', 'required' => true, 'readonly' => true]) }}
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
                                                {{ Form::select('receipt_type', ['goods' => 'Goods', 'service' => 'Service'], null, ['class' => 'form-control select2', 'id' => 'receipt_type', 'placeholder' => 'Select Receipt Type', 'required' => true]) }}
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            {{-- <hr> --}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('party_id', 'Vendor *') }}
                                                            {{ Form::select('party_id', $party, null, ['class' => 'form-control select2', 'id' => 'party_id', 'placeholder' => 'Select Bill To', 'required' => true]) }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('bill_to_gst_no', 'GSTIN/UIN') }}
                                                            {{ Form::text('bill_to_gst_no', null, ['class' => 'form-control bill_to_gst_no', 'placeholder' => 'GSTIN/UIN']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('remarks', 'Remarks') }}
                                                            {{ Form::text('remarks', null, ['class' => 'form-control remarks', 'placeholder' => 'Remarks', 'required' => true]) }}
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
                                     

                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                {{ Form::label('vendor_ref_no', 'Vendor Refrence Number') }}
                                                {{ Form::text('vendor_ref_no', null, ['class' => 'form-control vendor_ref_no', 'placeholder' => 'Vendor Refrence Number', 'required' => true]) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('place_of_supply', 'Place Of Supply *') }}
                                                {{ Form::text('place_of_supply', null, ['class' => 'form-control place_of_supply', 'placeholder' => 'Place Of Supply', 'required' => true]) }}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('posting_date', 'Posting Date *') }}
                                                {{ Form::date('posting_date', null, ['class' => 'form-control posting_date', 'placeholder' => 'Posting Date', 'required' => true]) }}
                                            </div>



                                            <div class="form-group">
                                                {{ Form::label('document_date', 'Document Date') }}
                                                {{ Form::date('document_date', null, ['class' => 'form-control document_date', 'placeholder' => 'Document Date', 'required' => true]) }}
                                            </div>


                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                {{ Form::label('trans_type', 'Transaction Type') }}
                                                {{ Form::text('trans_type', null, ['class' => 'form-control trans_type', 'placeholder' => 'Transaction Type', 'required' => true]) }}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('ship_from', 'Ship From') }}
                                                {{ Form::text('ship_from', null, ['class' => 'form-control ship_from', 'placeholder' => 'Ship From', 'required' => true]) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('due_date', 'Due Date *') }}
                                                {{ Form::date('due_date', null, ['class' => 'form-control due_date', 'placeholder' => 'Due Date', 'required' => true]) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('status', 'Status') }}
                                                {{ Form::select('status', ['open' => 'Open', 'close' => 'Close'], null, ['class' => 'form-control status', 'placeholder' => 'Select Status', 'required' => true]) }}
                                            </div>


                                        </div>

                                        {{-- </div> --}}
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
                                                        {{-- {{dd($goodsservicereceipt)}} --}}
                                                        @if (isset($goodsservicereceipt->goodsservicereceipts_items) &&
                                                                count($goodsservicereceipt->goodsservicereceipts_items) > 0)
                                                            @foreach ($goodsservicereceipt->goodsservicereceipts_items as $items)
                                                               
                                                                {{--  sthis part need for copy start  --}}
                                                                <div data-repeater-item>
                                                                    <div class="row justify-content-between item-content"
                                                                        id="old_row_{{ $loop->index }}"
                                                                        style="border-bottom:1px solid #d3d3d3;margin-bottom:15px;padding-bottom:15px;">
                                                                        <div class="col-md-2 col-sm-12 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('item_name', 'Description') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][item_name]', $items->item_name, ['data-name' => 'item_name', 'class' => 'form-control item_name typeahead', 'placeholder' => 'Description', 'autocomplete' => 'off', 'required' => true]) }}
                                                                                {{ Form::hidden('old_invoice_items[' . $loop->index . '][goods_service_receipts_item_id]', $items->goods_service_receipts_item_id, ['data-name' => 'goods_service_receipts_item_id', 'class' => 'form-control item_name ', 'placeholder' => 'Description', 'required' => true, 'autocomplete' => 'off']) }}
                                                                                {{ Form::hidden('old_invoice_items[' . $loop->index . '][goods_service_receipt_id]', $goodsservicereceipt->goods_service_receipt_id, ['data-name' => 'goods_service_receipt_id', 'class' => 'form-control item_name ', 'placeholder' => 'Description', 'required' => true, 'autocomplete' => 'off']) }}
                                                                                {{--  {{ Form::hidden('goods_service_receipts_item_id[]', $items->goods_service_receipts_item_id, ['data-name' => 'item_name', 'class' => 'form-control item_name typeahead', 'placeholder' => 'Description', 'required' => true, 'autocomplete' => 'off']) }}  --}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('hsn_sac', 'HSN/SAC') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][hsn_sac]', $items->hsn_sac, ['class' => 'form-control', 'placeholder' => 'HSN/SAC', 'data-name' => 'hsn_sac', 'required' => true]) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('storage_location_id', 'Warehouse') }}
                                                                                {{ Form::select('old_invoice_items[' . $loop->index . '][storage_location_id]', $storage_locations, $items->storage_location_id, ['class' => 'form-control storage_locations selected_' . $items->storage_location_id, 'data-selected' => $items->storage_location_id, 'placeholder' => 'Warehouse', 'data-name' => 'storage_location_id']) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('qty', 'Quantity') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][qty]', $items->qty, ['class' => 'form-control qty', 'placeholder' => 'Quantity', 'onchange' => 'calculategst(this)', 'data-name' => 'qty', 'data-group' => 'old_invoice_items', 'required' => true]) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('taxable_amount', 'Rate') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][taxable_amount]', $items->taxable_amount, ['class' => 'form-control taxable_amount', 'placeholder' => 'Rate', 'onchange' => 'calculategst(this)', 'data-name' => 'taxable_amount', 'data-group' => 'old_invoice_items', 'required' => true]) }}
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('total', 'Total INR') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][total]', $items->total, ['class' => 'form-control total', 'placeholder' => 'Total', 'onchange' => 'calculategst(this)', 'data-name' => 'total', 'data-group' => 'old_invoice_items', 'required' => true]) }}
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0 ">
                                                                            <div class="form-group">
                                                                                {{ Form::label('GST', 'GST (%)') }}
                                                                                {{ Form::select('old_invoice_items[' . $loop->index . '][gst_rate]', $gst, $items->gst_rate, ['class' => 'form-control', 'placeholder' => 'Select GST', 'onchange' => 'calculategst(this)', 'data-name' => 'gst_rate', 'data-group' => 'old_invoice_items', 'required' => true]) }}

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                            <div class="form-group">
                                                                                {{ Form::label('GST Amount', 'GST Amount') }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][gst_amount]', $items->gst_amount, ['class' => 'form-control', 'placeholder' => 'GST Amount', 'onchange' => 'calculategst(this)', 'data-name' => 'gst_amount', 'data-group' => 'old_invoice_items', 'required' => true]) }}

                                                                            </div>
                                                                        </div>



                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                            <div class="form-group">
                                                                                {{ Form::label('cgst_rate', 'CGST', ['class' => 'custom-label']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][cgst_rate]', $items->cgst_rate, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_rate', 'data-group' => 'old_invoice_items']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][cgst_amount]', $items->cgst_amount, ['class' => 'form-control custom-amount cgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount', 'data-group' => 'old_invoice_items']) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                            <div class="form-group">
                                                                                {{ Form::label('sgst_utgst_rate', 'SGST', ['class' => 'custom-label']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][sgst_utgst_rate]', $items->sgst_utgst_rate, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'sgst_utgst_rate', 'data-group' => 'old_invoice_items']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][cgst_amount]', $items->cgst_amount, ['class' => 'form-control custom-amount sgst_utgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount', 'data-name' => 'sgst_utgst_amount', 'data-group' => 'old_invoice_items']) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                            <div class="form-group">
                                                                                {{ Form::label('igst_rate', 'IGST', ['class' => 'custom-label']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][igst_rate]', $items->igst_rate, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_rate', 'data-group' => 'old_invoice_items']) }}
                                                                                {{ Form::text('old_invoice_items[' . $loop->index . '][igst_amount]', $items->igst_amount, ['class' => 'form-control custom-amount igst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_amount', 'data-group' => 'old_invoice_items']) }}
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="col-md-1 col-sm-12 d-flex align-items-center pl-0 pr-0">
                                                                            <div class="pull-right repeater-remove-btn">
                                                                                <span
                                                                                    class="btn btn-danger remove-btn old_row_delete"
                                                                                    id="{{ $loop->index }}">
                                                                                    Delete
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        {{--  Modal starts Here  --}}
                                                                        <div class="modal fade text-left" id=""
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
                                                                                                        <td>Sr. No </td>
                                                                                                        <td>Batch no </td>
                                                                                                        <td>Mfg. Date </td>
                                                                                                        <td>Exp. Date </td>
                                                                                                    </tr>
                                                                                                    @php $cnt = 1; @endphp
                                                                                                    {{--  <td>{{ Form::text('old_invoice_items['.$loop->index.'][batches]['.$cnt.'][batch_no]', $batches->batch_no, ['class'=>'form-control']) }}</td>  --}}
                                                                                                    @if (isset($items->goods_service_receipts_batches) && count($items->goods_service_receipts_batches) > 0)
                                                                                                        @for ($i = 0; $i < count($items->goods_service_receipts_batches); $i++)
                                                                                                            {{--  {{ dd($items->goods_service_receipts_batches[$i]->toArray()) }}  --}}
                                                                                                            <tr>
                                                                                                                <td>{{ $cnt }}
                                                                                                                </td>
                                                                                                                <td>

                                                                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index . '][batches][' . $cnt . '][goods_service_receipts_batches_id]', $items->goods_service_receipts_batches[$i]['goods_service_receipts_batches_id'], ['class' => 'form-control']) }}
                                                                                                                    {{ Form::hidden('old_invoice_items[' . $loop->index . '][batches][' . $cnt . '][goods_service_receipts_item_id]', $items->goods_service_receipts_batches[$i]['goods_service_receipts_item_id'], ['class' => 'form-control']) }}
                                                                                                                    {{ Form::text('old_invoice_items[' . $loop->index . '][batches][' . $cnt . '][batch_no]', $items->goods_service_receipts_batches[$i]['batch_no'], ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                                <td>{{ Form::date('old_invoice_items[' . $loop->index . '][batches][' . $cnt . '][manufacturing_date]', $items->goods_service_receipts_batches[$i]['manufacturing_date'], ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                                <td>{{ Form::date('old_invoice_items[' . $loop->index . '][batches][' . $cnt . '][expiry_date]', $items->goods_service_receipts_batches[$i]['expiry_date'], ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            @php $cnt++  @endphp
                                                                                                        @endfor
                                                                                                    @endif
                                                                                                    @if ($cnt < 10)
                                                                                                        @for ($i = $cnt; $i <= 10; $i++)
                                                                                                            <tr>
                                                                                                                <td>{{ $i }}
                                                                                                                </td>
                                                                                                                <td>{{ Form::text('old_invoice_items[' . $loop->index . '][batches][' . $i . '][batch_no]', null, ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                                <td>{{ Form::date('old_invoice_items[' . $loop->index . '][batches][' . $i . '][manufacturing_date]', null, ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                                <td>{{ Form::date('old_invoice_items[' . $loop->index . '][batches][' . $i . '][expiry_date]', null, ['class' => 'form-control']) }}
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        @endfor
                                                                                                    @endif


                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn grey btn-outline-secondary"
                                                                                                data-dismiss="modal">Ok</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{--  Modal ends Here  --}}
                                                                        </div>
                                                                    </div>
                                                                    {{-- <hr> --}}


                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        {{-- ********************************** --}}
                                                        {{-- ********************************** --}}

                                                        {{-- This is Repeater --}}
                                                        <div id="repeater">
                                                            <!-- Repeater Heading -->
                                                            <div class="repeater-heading">
                                                                <h5 class="pull-left">Repeater</h5>
                                                                <span class="btn btn-primary pull-right repeater-add-btn">
                                                                    Add
                                                                </span>
                                                            </div>

                                                            <!-- Repeater Items -->
                                                            <div class="row w-100">

                                                                <div class="items" data-group="invoice_items"
                                                                    data-test="test">
                                                                    <!-- Repeater Content -->
                                                                    <div class="item-content">
                                                                        <div class="form repeater-default">
                                                                            <div data-repeater-list="invoice_items">


                                                                                {{--  sthis part need for copy start  --}}
                                                                                <div data-repeater-item>
                                                                                    <div
                                                                                        class="row justify-content-between">
                                                                                        <div
                                                                                            class="col-md-2 col-sm-12 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('item_name', 'Description') }}
                                                                                                {{ Form::text('item_name', null, ['data-id' => 'item_name', 'id' => 'item_name', 'data-name' => 'item_name', 'class' => 'form-control item_name typeahead', 'placeholder' => 'Description', 'autocomplete' => 'off', 'required' => true]) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-2 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('hsn_sac', 'HSN/SAC') }}
                                                                                                {{ Form::text('hsn_sac', null, ['class' => 'form-control', 'placeholder' => 'HSN/SAC', 'data-name' => 'hsn_sac', 'required' => true]) }}
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
                                                                                                {{ Form::text('qty', null, ['class' => 'form-control qty', 'placeholder' => 'Quantity', 'onchange' => 'calculategst(this)', 'data-name' => 'qty', 'data-group' => 'invoice_items', 'required' => true]) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('taxable_amount', 'Rate') }}
                                                                                                {{ Form::text('taxable_amount', null, ['class' => 'form-control taxable_amount', 'placeholder' => 'Rate', 'onchange' => 'calculategst(this)', 'data-name' => 'taxable_amount', 'data-group' => 'invoice_items', 'required' => true]) }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('total', 'Total INR') }}
                                                                                                {{ Form::text('total', null, ['class' => 'form-control total', 'placeholder' => 'Total', 'onchange' => 'calculategst(this)', 'data-name' => 'total', 'data-group' => 'invoice_items', 'required' => true]) }}
                                                                                            </div>
                                                                                        </div>

                                                                                        {{ Form::hidden('bill_to_state', null, ['class' => 'form-control bill_to_state', 'onchange' => 'calculategst(this)', 'data-name' => 'bill_to_state', 'data-group' => 'invoice_items', 'required' => true]) }}
                                                                                        {{ Form::hidden('party_state', null, ['class' => 'form-control party_state', 'onchange' => 'calculategst(this)', 'data-name' => 'party_state', 'data-group' => 'invoice_items', 'required' => true]) }}


                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('GST', 'GST (%)') }}
                                                                                                {{ Form::select('gst_rate', $gst, null, ['class' => 'form-control gst_type', 'placeholder' => 'Select GST', 'onchange' => 'calculategst(this)', 'data-name' => 'gst_rate', 'data-group' => 'invoice_items', 'required' => true]) }}

                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('Amount', 'GST Amount') }}
                                                                                                {{ Form::text('gst_amount', null, ['class' => 'form-control ', 'placeholder' => 'GST Amount', 'onchange' => 'calculategst(this)', 'data-name' => 'gst_amount', 'data-group' => 'invoice_items', 'required' => true]) }}

                                                                                            </div>
                                                                                        </div>


                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('cgst_rate', 'CGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('cgst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_rate', 'data-group' => 'invoice_items']) }}
                                                                                                {{ Form::text('cgst_amount', null, ['class' => 'form-control custom-amount cgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount', 'data-group' => 'invoice_items']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('sgst_utgst_rate', 'SGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('sgst_utgst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'sgst_utgst_rate', 'data-group' => 'invoice_items']) }}
                                                                                                {{ Form::text('sgst_utgst_rate', null, ['class' => 'form-control custom-amount sgst_utgst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'cgst_amount', 'data-name' => 'sgst_utgst_amount', 'data-group' => 'invoice_items']) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-1 col-sm-12 pl-0 pr-0 d-none">
                                                                                            <div class="form-group">
                                                                                                {{ Form::label('igst_rate', 'IGST', ['class' => 'custom-label']) }}
                                                                                                {{ Form::text('igst_rate', null, ['class' => 'form-control custom-rate', 'placeholder' => '%', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_rate', 'data-group' => 'invoice_items']) }}
                                                                                                {{ Form::text('igst_amount', null, ['class' => 'form-control custom-amount igst_amount', 'placeholder' => 'Amt.', 'onchange' => 'calculategst(this)', 'data-name' => 'igst_amount', 'data-group' => 'invoice_items']) }}
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
                                                                                    {{-- <hr> --}}

                                                                                    {{--  Modal starts Here  --}}
                                                                                    <div class="modal fade text-left"
                                                                                        id="" tabindex="-1"
                                                                                        role="dialog"
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
                                                                                                                            class='form-control'
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][0][manufacturing_date"
                                                                                                                            data-name="batches][0][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][1][batch_no"
                                                                                                                            data-name="batches][1][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][1][manufacturing_date"
                                                                                                                            data-name="batches][1][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][2][batch_no"
                                                                                                                            data-name="batches][2][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][2][manufacturing_date"
                                                                                                                            data-name="batches][2][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][3][batch_no"
                                                                                                                            data-name="batches][3][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][3][manufacturing_date"
                                                                                                    {{--  {{ dd($items->goods_service_receipts_batches->toArray()) }}  --}}
                                                                {{--  {{ dd($items->goods_service_receipts_batches->toArray()) }}  --}}                         data-name="batches][3][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][4][batch_no"
                                                                                                                            data-name="batches][4][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][4][manufacturing_date"
                                                                                                                            data-name="batches][4][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][5][batch_no"
                                                                                                                            data-name="batches][5][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][5][manufacturing_date"
                                                                                                                            data-name="batches][5][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][6][batch_no"
                                                                                                                            data-name="batches][6][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][6][manufacturing_date"
                                                                                                                            data-name="batches][6][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][7][batch_no"
                                                                                                                            data-name="batches][7][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][7][manufacturing_date"
                                                                                                                            data-name="batches][7][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][8][batch_no"
                                                                                                                            data-name="batches][8][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][8][manufacturing_date"
                                                                                                                            data-name="batches][8][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                                                            class='form-control'
                                                                                                                            name="batches][9][batch_no"
                                                                                                                            data-name="batches][9][batch_no"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td><input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
                                                                                                                            name="batches][9][manufacturing_date"
                                                                                                                            data-name="batches][9][manufacturing_date"
                                                                                                                            id="">
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input
                                                                                                                            type="date"
                                                                                                                            class='form-control'
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
                                                                                        {{--  Modal ends Here  --}}
                                                                                    </div>
                                                                                </div>
                                                                                {{--  sthis part need for copy end  --}}

                                                                            </div>
                                                                            <!-- Repeater Remove Btn -->
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                {{-- Repeater End --}}
                                                            </div>
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
                                            <p>Discount: <strong><input type="number" name="discount" placeholder="%"
                                                        class="w-50" id="discount"></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>Total Amount: <strong><span class="total_amount">0</span></strong></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p>Final Amount: <strong><span class="final_amount">0</span></strong></p>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <p>Final Amount In Words: <strong><span
                                                        class="total_amount_words">-</span></strong></p>
                                        </div>


                                        <div class="col-sm-12">
                                            {{-- <hr> --}}
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
        function get_data_display(customer_id) {

            // alert(customer_id);

            $.get(APP_URL + '/admin/apinvoice/partydetails/' + customer_id, {}, function(
                response) {
                var customer_details = $.parseJSON(response);

                if (customer_details) {
                    $(".party").html(customer_details.party_detail);
                    $(".party_input").val(customer_details.party_detail);
                    $(".bill_to_state").val(customer_details.bill_to_state);
                    $(".party_state").val(customer_details.party_state);
                    $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);
                    $(".party_name").val(customer_details.party_name);
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

            var customer_id = $('#party_id option:selected').val();
            // alert(customer_id);
            if (customer_id != "") {
                get_data_display(customer_id);
            }
            $("#party_id").on('change', function() {
                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
            });


            $(document).on('change', '.storage_locations', function() {
                var parent_div = $(this).parents()[2];
                var suffix = $(this).attr('name');
                $(this).closest('.item-content').find('.modal').modal('show');
            });

            //old row delete button
            $('.old_row_delete').click(function() {
                let delete_row_id = $(this).attr('id');
                let cnf = confirm('are you sure, do you want to delete these records');
                if (cnf) {
                    $('#old_row_' + delete_row_id).remove();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            /* Create Repeater */
            $("#repeater").createRepeater({
                showFirstItemToDefault: true,
            });
            console.log('test');
            calculate_grand_total();
        });

        $("#discount").on('change', function() {
            calculate_grand_total();
        });
    </script>
    @include('backend.apinvoice.autocomplete_typeahead_script')

@endsection
