@extends('backend.layouts.app')
@section('title', 'Create Goods Issue')

@section('content')
@php
use App\Models\backend\Company;
use App\Models\backend\Products;
use App\Models\backend\BussinessPartnerMaster;
@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Goods Issue</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Create Goods Issue</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="{{ url()->previous() }}">
                    Back
                </a>
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
                            {{ Form::model([], ['url' => 'admin/goodsissue/update', 'class' => 'w-100']) }}
                            {{ Form::hidden('bill_to_state', null, ['class' => 'bill_to_state']) }}
                            {{ Form::hidden('party_state', null, ['class' => 'party_state']) }}
                            {{ Form::hidden('bill_to_gst_no', null, ['class' => 'bill_to_gst_no']) }}
                            <div class="form-body">


                                <div class="row">

                                    @if(session('company_id') != 0)
                                    <input type="hidden" name="company_id" id="company_id"
                                        value="{{ session('company_id') }}">
                                    @else
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            {{ Form::label('company_id', 'Distributor *') }}
                                            {{ Form::select('company_id', Company::pluck('name','company_id'), null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Distributor',
                                            'required'=>true
                                            ]) }}
                                        </div>
                                    </div>

                                    @endif

                                    @php
                                    $party = BussinessPartnerMaster::where('is_converted',
                                    1)->when(session('company_id') != 0, function ($query) {
                                    return $query->where('company_id', session('company_id'));
                                    })->get();

                                    $party = $party->filter(function ($item) {
                                    $data = $item->getpartnertypecustomer;

                                    return $data;
                                    })->mapWithKeys(function ($item) {
                                    return [$item['business_partner_id'] => $item['bp_name']];
                                    });

                                    $first_customer =
                                    BussinessPartnerMaster::where(['business_partner_type'=>1,'is_converted'=>1])->first();
                                    @endphp
                                    <div class="col-md-12 col-sm-12 d-none">
                                        <div class="form-group">
                                            {{ Form::label('party_id', 'Customer *') }}
                                            {{ Form::select('party_id', $party,
                                            $first_customer,
                                            [
                                            'class' => 'form-control d-none',
                                            ]) }}
                                        </div>
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <section id="form-repeater-wrapper">
                                            <!-- form default repeater -->
                                            <div class="row">
                                                <div class="col-12">


                                                    {{-- ********************************** --}}
                                                    {{-- ********************************** --}}

                                                    {{-- This is Repeater --}}
                                                    <div class="conatiner-fluid table-responsive repeater">
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
                                                                            {{ Form::label('item_name', 'Item
                                                                            Description') }}
                                                                        </td>
                                                                        <td class="adjust_col">
                                                                            {{ Form::label('hsn_sac', 'HSN/SAC') }}
                                                                        </td>
                                                                        <td class="adjust_col">
                                                                            {{ Form::label('uom',
                                                                            'UOM',['style'=>'display:
                                                                            flex;justify-content: center;']) }}
                                                                            {{ Form::select('uom',
                                                                            ['units'=>'Units','case'=>'Case'],
                                                                            null,
                                                                            [
                                                                            'class' => 'form-control uom',
                                                                            'data-name' => 'uom',
                                                                            'data-group' => 'invoice_items',
                                                                            ])
                                                                            }}
                                                                        </td>
                                                                        </td>
                                                                        <td>{{ Form::label('qty',
                                                                            'Quantity',['id'=>'qty_label']) }}
                                                                        </td>
                                                                        <td>{{ Form::label('final_qty', 'Quantity
                                                                            (Units)') }}
                                                                        </td>
                                                                        <td>{{ Form::label('taxable_amount', 'Unit
                                                                            Price') }}
                                                                        </td>
                                                                        {{-- <td>{{ Form::label('discount', 'Disocunt')
                                                                            }}
                                                                        </td> --}}
                                                                        {{-- <td>{{ Form::label('price_af_discount',
                                                                            'Price After Disocunt') }}
                                                                        </td> --}}
                                                                        <td>{{ Form::label('total', 'Total INR') }}
                                                                        </td>
                                                                        <td class="adjust_col">
                                                                            {{ Form::label('GST', 'GST (%)') }}</td>
                                                                        <td>{{ Form::label('CGST', 'CGST (%)') }}</td>
                                                                        <td>{{ Form::label('SGST', 'SGST (%)') }}</td>
                                                                        <td>{{ Form::label('IGST', 'IGST (%)') }}</td>
                                                                        <td>{{ Form::label('Amount', 'GST Amount') }}
                                                                        </td>
                                                                        <td>{{ Form::label('gross_total', 'Gross Total')
                                                                            }}
                                                                        </td>
                                                                        <td>{{ Form::label('storage_location_id',
                                                                            'Warehouse') }}
                                                                        </td>
                                                                        <td class="batch_details">{{
                                                                            Form::label('bacth_id', 'Batch Details')
                                                                            }}
                                                                        </td>
                                                                        <td></td>

                                                                    </tr>
                                                                </thead>
                                                                <tbody data-repeater-list="old_invoice_items">
                                                                    {{-- @if (isset($model->purchaseorder_items) &&
                                                                    count($model->purchaseorder_items) > 0) --}}
                                                                    <?php
                                                                  
                                                                        for ($i=0; $i < count(old('invoice_items')??['1']); $i++){ 
                                                                        ?>
                                                                    <tr data-repeater-item class="item_row item-content"
                                                                        id="old_row_{{ $i }}">


                                                                        {{
                                                                        Form::hidden('unit_pack',old('old_invoice_items')[$i]['unit_pack']??null,
                                                                        ['data-group' => 'old_invoice_items']) }}
                                                                        {{
                                                                        Form::hidden('pack_case',old('old_invoice_items')[$i]['pack_case']??null,
                                                                        ['data-group' => 'old_invoice_items']) }}


                                                                        {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][goods_service_receipts_item_id]', null,
                                                                        ['data-name' =>
                                                                        'goods_service_receipts_item_id', 'class' =>
                                                                        'form-control item_name typeahead',
                                                                        'placeholder' => 'Description', 'required' =>
                                                                        true, 'autocomplete' => 'off']) }}


                                                                        {{-- {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][cgst_rate]', $items->cgst_rate, ['class' =>
                                                                        'form-control custom-rate', 'placeholder' =>
                                                                        '%', 'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'cgst_rate', 'data-group' =>
                                                                        'old_invoice_items']) }} --}}
                                                                        {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][cgst_amount]', null, ['class' =>
                                                                        'form-control custom-amount cgst_amount',
                                                                        'placeholder' => 'Amt.', 'onchange' =>
                                                                        'calculategst(this)', 'data-name' =>
                                                                        'cgst_amount', 'data-group' =>
                                                                        'old_invoice_items']) }}

                                                                        {{-- {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][sgst_utgst_rate]', $items->sgst_utgst_rate,
                                                                        ['class' => 'form-control custom-rate',
                                                                        'placeholder' => '%', 'onchange' =>
                                                                        'calculategst(this)', 'data-name' =>
                                                                        'sgst_utgst_rate', 'data-group' =>
                                                                        'old_invoice_items']) }} --}}
                                                                        {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][sgst_utgst_amount]', null, ['class' =>
                                                                        'form-control custom-amount sgst_utgst_amount',
                                                                        'placeholder' => 'Amt.', 'onchange' =>
                                                                        'calculategst(this)', 'data-name' =>
                                                                        'sgst_utgst_amount', 'data-group' =>
                                                                        'old_invoice_items']) }}

                                                                        {{-- {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][igst_rate]', $items->igst_rate, ['class' =>
                                                                        'form-control custom-rate', 'placeholder' =>
                                                                        '%', 'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'igst_rate', 'data-group' =>
                                                                        'old_invoice_items']) }} --}}
                                                                        {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][igst_amount]', null, ['class' =>
                                                                        'form-control custom-amount igst_amount',
                                                                        'placeholder' => 'Amt.', 'onchange' =>
                                                                        'calculategst(this)', 'data-name' =>
                                                                        'igst_amount', 'data-group' =>
                                                                        'old_invoice_items']) }}

                                                                        {{ Form::hidden('old_invoice_items[' . $i .
                                                                        '][sku]',null, ['class' => 'form-control sku',
                                                                        'data-name' => 'sku', 'data-group' =>
                                                                        'old_invoice_items']) }}


                                                                        <td>{{ Form::text('old_invoice_items[' . $i .
                                                                            '][item_code]',null, ['data-name' =>
                                                                            'item_code', 'class' => 'form-control
                                                                            item_code typeahead', 'required' => true])
                                                                            }}
                                                                        </td>

                                                                        <td>{{ Form::text('old_invoice_items[' . $i .
                                                                            '][item_name]',null, ['data-name' =>
                                                                            'item_name', 'class' => 'form-control
                                                                            item_name typeahead', 'required' => true,
                                                                            'oninput' => 'validateInput(this)']) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][hsn_sac]', null, ['class' =>
                                                                            'form-control readonly', 'data-name' =>
                                                                            'hsn_sac']) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('uom',
                                                                            old('old_invoice_items')[$i]['uom'] ?? null,
                                                                            [
                                                                            'class' => 'form-control uom_field',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'uom',
                                                                            'data-group' => 'old_invoice_items',
                                                                            'readonly' => true,
                                                                            ])
                                                                            }}
                                                                        </td>

                                                                        <td> {{ Form::text('qty',
                                                                            old('old_invoice_items')[$i]['qty'] ?? null,
                                                                            [
                                                                            'class' => 'form-control qty',
                                                                            'oninput' => 'handleInput(this)',
                                                                            'data-name' => 'qty',
                                                                            'data-group' => 'old_invoice_items',
                                                                            'required' => true,
                                                                            ]) }}
                                                                        </td>
                                                                        <td> {{ Form::number('final_qty',
                                                                            old('old_invoice_items')[$i]['final_qty'] ??
                                                                            0,
                                                                            [
                                                                            'class' => 'form-control final_qty',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'final_qty',
                                                                            'data-group' => 'old_invoice_items',
                                                                            'readonly' => true,
                                                                            ]) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][taxable_amount]',null, ['class' =>
                                                                            'form-control taxable_amount readonly',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'taxable_amount',
                                                                            'data-group' => 'old_invoice_items',
                                                                            'required' => true]) }}
                                                                        </td>
                                                                        {{-- <td> --}}
                                                                            {{ Form::hidden('old_invoice_items[' . $i .
                                                                            '][discount_item]', null, ['class' =>
                                                                            'form-control discount_item', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'discount_item', 'data-group' =>
                                                                            'old_invoice_items']) }}
                                                                            {{-- </td> --}}
                                                                        {{-- <td>{{ Form::text('old_invoice_items[' . $i
                                                                            . '][price_af_discount]', null, ['class' =>
                                                                            'form-control price_af_discount', 'onchange'
                                                                            => 'calculategst(this)', 'data-name' =>
                                                                            'price_af_discount', 'data-group' =>
                                                                            'old_invoice_items', 'readonly' => true]) }}
                                                                        </td> --}}
                                                                        <td>{{ Form::text('old_invoice_items[' . $i .
                                                                            '][total]', null, ['class' => 'form-control
                                                                            total', 'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'total', 'data-group' =>
                                                                            'old_invoice_items', 'required' => true,
                                                                            'readonly' => true]) }}
                                                                        </td>

                                                                        <td style="width: 130px;">
                                                                            {{ Form::select('old_invoice_items[' . $i .
                                                                            '][gst_rate]', $gst, null, ['class' =>
                                                                            'form-control gst_dropdown readonly',
                                                                            'placeholder' => 'Select GST', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'gst_rate', 'data-group' =>
                                                                            'old_invoice_items', 'required' => true]) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][cgst_rate]',null, ['class' =>
                                                                            'form-control custom-rate all_gst',
                                                                            'placeholder' => '%', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'cgst_rate', 'data-group' =>
                                                                            'old_invoice_items', 'readonly' => true]) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][sgst_utgst_rate]', null, ['class' =>
                                                                            'form-control custom-rate all_gst',
                                                                            'placeholder' => '%', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'sgst_utgst_rate', 'data-group' =>
                                                                            'old_invoice_items', 'readonly' => true]) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][igst_rate]', null, ['class' =>
                                                                            'form-control custom-rate all_gst',
                                                                            'placeholder' => '%', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'igst_rate', 'data-group' =>
                                                                            'old_invoice_items', 'readonly' => true]) }}
                                                                        </td>


                                                                        <td>
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][gst_amount]', null, ['class' =>
                                                                            'form-control readonly', 'placeholder' =>
                                                                            'GST Amount', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'gst_amount', 'data-group' =>
                                                                            'old_invoice_items', 'required' => true]) }}
                                                                        </td>
                                                                        <td>{{ Form::text('old_invoice_items[' . $i .
                                                                            '][gross_total]', null, ['class' =>
                                                                            'form-control gross_total', 'onchange' =>
                                                                            'calculategst(this)', 'data-name' =>
                                                                            'gross_total', 'data-group' =>
                                                                            'old_invoice_items', 'required' => true,
                                                                            'readonly' => true]) }}
                                                                        </td>
                                                                        <td style="width: 210px;">
                                                                            {{ Form::select('old_invoice_items[' . $i .
                                                                            '][storage_location_id]',
                                                                            $storage_locations, null, ['class' =>
                                                                            'form-control storage_locations',
                                                                            'data-name' => 'storage_location_id',
                                                                            'required' => true]) }}

                                                                        </td>

                                                                        <td class="batch_details">
                                                                            {{ Form::text('old_invoice_items[' . $i .
                                                                            '][batch_no]', null,
                                                                            ['class' =>'form-control',
                                                                            'data-name' => 'batch_no',
                                                                            'id'=>'batch_no',
                                                                            'required' => true]) }}
                                                                        </td>





                                                                        <td><button type='button'
                                                                                class='btn btn-danger btn-flat btn-xs old_rep_item_del'
                                                                                id="{{ $i }}" data-repeater-delete><i
                                                                                    class='fa fa-fw fa-remove'></i></button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                        }
                                                        ?>

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
                                            {{ Form::textarea('remarks', null, ['class' => 'form-control remarks',
                                            'placeholder' => 'Remarks', 'style' => 'height:100px;']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <div>
                                            <p>Total Before Discount: <strong><span
                                                        class="total_amount">0</span></strong></p>
                                        </div>
                                        <div>
                                            <p>Discount: <strong><input type="number" name="discount" id="discount"
                                                        style="width:30px;" value="" oninput="calculate_grand_total()">
                                                    %</strong>
                                                <input class="discount_amt w-25" value="">
                                            </p>
                                        </div>
                                        <div>
                                            <p>Total After Discount: <strong><span
                                                        class="total_af_disc"></span>0</strong></p>
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
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1', 'id' =>
                                    'custom_form']) }}
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

@include('backend.autocomplete_typeahead_script')




<script>
    function handleInput(elem) {
            calculategst(elem);
            validateQty(elem);
        }

</script>

<script>
    function get_company_data(company_id) {

            // alert(customer_id);
            $.ajax({
                url: "{{route('admin.get_company_details')}}",
                type: 'get',
                data: {
                    company_id: company_id,
                },
                dataType: 'json',
                success: function(data) {
                    if(data.batch_system){
                        $('.batch_details').removeClass('d-none');
                    }else{
                        $('.batch_details').addClass('d-none');
                        $('#batch_no').removeAttr('required');
                    }
                
                }
            });

        }
        $(document).ready(function() {

            $('.batch_details').addClass('d-none');
            $('#batch_no').removeAttr('required');
            var company_id = $('#company_id option:selected').val();
            if (company_id != "") {
                get_company_data(company_id);
            }
            $("#company_id").on('change', function() {
                var company_id = $(this).val();
                // alert(company_id);
                if (company_id != '') {
                    get_company_data(company_id);
                }
            });

            $(document).on('click', '.batch-details-button', function(e) {
                $(this).closest('.item_row').find('.modal').modal('show');
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


        });

        $("#discount").on('change', function() {
            calculate_grand_total();
        });
</script>

<script>
    function validateInputZero(input) {
            // Get the entered value
            var value = input.value;

            // If the value is 0, prevent it and set the value to an empty string
            if (value === '0') {
                input.value = '';
                return false;
            }

            return true;
        }
</script>







@endsection