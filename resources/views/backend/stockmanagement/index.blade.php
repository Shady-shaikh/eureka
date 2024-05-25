@extends('backend.layouts.app')
@section('title', 'Bin Transfer')


@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Bin Transfer</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Bin Transfer</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a href="{{ route('admin.stockmanagement.bin_transfer_history') }}" class="btn btn-secondary">Bin Transfer
                Histroy</a>
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
                            {{ Form::open(['url' => 'admin/stockmanagement/update', 'class' => 'w-100']) }}

                            <div class="form-body">

                                @if (is_superAdmin())
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">


                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('company_id', 'Distributor *') }}
                                                        {{ Form::select('company_id', $company, null, [
                                                        'class' => 'form-control ',
                                                        'placeholder' => 'Select Company',
                                                        'required' => true,
                                                        ]) }}
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @else
                                <input type="hidden" name="company_id" id="company_id"
                                    value="{{session('company_id')}}" />
                                @endif
                                {{--
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('warehouse_id', 'Warehouse *') }}
                                                        {{ Form::select('warehouse_id', $storage_locations, null,
                                                        ['class' => 'form-control select2', 'placeholder' => 'Select
                                                        Warehouse', 'required' => true]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> --}}

                                <div class="row">

                                    <div class="col-sm-12 mb-3">

                                        <section id="form-repeater-wrapper">
                                            <!-- form default repeater -->

                                            <div class="conatiner-fluid  repeater">
                                                <button type="button" data-repeater-create
                                                    class="btn btn-primary pull-right mb-2 add_btn_rep">Add</button>


                                                <table class="table table-bordered " id="repeater">
                                                    <thead class="bg-light" style="font-size:10px;">
                                                        <tr>
                                                            <td class="adjust_col">
                                                                {{ Form::label('from_warehouse', 'From Warehouse') }}
                                                            </td>
                                                            <td class="adjust_col">
                                                                {{ Form::label('from_bin', 'From Bin') }}
                                                            </td>

                                                            <td class="adjust_col">
                                                                {{ Form::label('to_warehouse', 'To Warehouse') }}
                                                            </td>

                                                            <td class="adjust_col">
                                                                {{ Form::label('to_bin', 'To Bin') }}
                                                            </td>

                                                            <td class="adjust_col">{{ Form::label('item_name', 'Item
                                                                Description') }}
                                                            </td>
                                                            <td class="adjust_col">
                                                                {{ Form::label('item_code', 'Item Code') }}
                                                            </td>
                                                            <td style="width:100px;">
                                                                {{ Form::label('from_qty', 'Available Quantity') }}
                                                            </td>
                                                            <td style="width:100px;">
                                                                {{ Form::label('qty', 'Quantity') }}
                                                            </td>
                                                            <td style="width:50px;"></td>


                                                        </tr>
                                                    </thead>
                                                    <tbody data-repeater-list="invoice_items" class="repeater">


                                                        <tr data-repeater-item class="item_row">


                                                            <td>
                                                                {{ Form::select('from_warehouse', $storage_locations,
                                                                null, ['class' => 'form-control from_warehouse',
                                                                'placeholder' => 'Select Warehouse', 'required' =>
                                                                true]) }}
                                                            </td>
                                                            <td>{{ Form::select('from_bin', [], null, ['class' =>
                                                                'form-control from_bin', 'required' => true]) }}
                                                            </td>
                                                            <td>
                                                                {{ Form::select('to_warehouse', $storage_locations,
                                                                null, ['class' => 'form-control to_warehouse',
                                                                'placeholder' => 'Select Warehouse', 'required' =>
                                                                true]) }}

                                                            </td>
                                                            <td>{{ Form::select('to_bin', [], null, ['class' =>
                                                                'form-control to_bin', 'required' => true]) }}
                                                            </td>

                                                            <td>
                                                                {{ Form::text('item_name', null, ['class' =>
                                                                'form-control item_name typeahead', 'data-name' =>
                                                                'item_name','data-id' => 'item_name', 'required' =>
                                                                true]) }}
                                                            </td>
                                                            <td>{{ Form::text('item_code', null, ['class' =>
                                                                'form-control item_code typeahead', 'data-name' =>
                                                                'item_code','data-id' => 'item_code', 'required' =>
                                                                true]) }}
                                                            </td>

                                                            <td>{{ Form::number('from_qty', 0, ['class' => 'form-control
                                                                from_qty', 'data-name' => 'from_qty', 'readonly' =>
                                                                true]) }}
                                                            </td>
                                                            <td>{{ Form::number('qty', null, ['class' => 'form-control
                                                                qty', 'data-name' => 'qty', 'required' => true]) }}
                                                            </td>


                                                            <td><button type='button'
                                                                    class='btn btn-danger btn-flat btn-xs old_rep_item_del'
                                                                    data-repeater-delete><i
                                                                        class='fa fa-fw fa-remove'></i></button>
                                                            </td>

                                                            {{ Form::hidden('batch_no',
                                                            null, [
                                                            'id'=>'def_batch_no',
                                                            'class' => 'form-control def_batch_no',
                                                            'data-name' => 'batch_no',
                                                            'data-group' => 'old_invoice_items',
                                                            ]) }}


                                                        </tr>


                                                    </tbody>
                                                </table>

                                            </div>

                                        </section>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                </div>

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



@include('backend.autocomplete_typeahead_script')


<script>
    $(document).ready(function() {

            //get available quantity for bin
            $(document).on('blur', '.item_code', function() {
                var row = $(this).closest('.item_row');
                var from_warehouse_id = row.find('.from_warehouse').val();
                var from_bin_id = row.find('.from_bin').val();
                var batch_no = row.find('.batch').val();
                var item_code = $(this).val();
                var company_id = $('#company_id').val();
                var avialableQty = row.find('.from_qty');

                // console.log(from_warehouse_id,from_bin_id,batch_no,item_code,avialableQty);

                // Make an AJAX request to get bins for 'from_warehouse'
                $.ajax({
                    url: '{{ route('admin.stockmanagement.get_available_qty') }}',
                    type: 'GET',
                    data: {
                        warehouse_id: from_warehouse_id,
                        from_bin_id: from_bin_id,
                        batch_no: batch_no,
                        item_code: item_code,
                        company_id: company_id
                    },
                    success: function(data) {

                        if (data) {
                            avialableQty.val(data['qty']);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });


    


            // When 'from_warehouse' dropdown changes
            $(document).on('change', '.from_warehouse', function() {
                var fromWarehouseId = $(this).val();
                var fromBinDropdown = $(this).closest('.item_row').find('.from_bin');


                // Make an AJAX request to get bins for 'from_warehouse'
                $.ajax({
                    url: '{{ route('admin.stockmanagement.get_bins') }}',
                    type: 'GET',
                    data: {
                        from_warehouse_id: fromWarehouseId
                    },
                    success: function(data) {
                        // Update the 'from_bin' dropdown with the retrieved data
                        fromBinDropdown.html(data);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // When 'to_warehouse' dropdown changes
            $(document).on('change', '.to_warehouse', function() {
                var toWarehouseId = $(this).val();
                var toBinDropdown = $(this).closest('.item_row').find('.to_bin');

                // Make an AJAX request to get bins for 'to_warehouse'
                $.ajax({
                    url: '{{ route('admin.stockmanagement.get_bins') }}',
                    type: 'GET',
                    data: {
                        to_warehouse_id: toWarehouseId
                    },
                    success: function(data) {
                        // Update the 'to_bin' dropdown with the retrieved data
                        toBinDropdown.html(data);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
</script>




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


            calculate_grand_total();

            $('.repeater').repeater({
                isFirstItemUndeletable: true,
                // initEmpty: false,
            });


            get_invoice_itemnames();

            $('.add_btn_rep').click(function() {
                get_invoice_itemnames();
            });



        });
</script>





@endsection