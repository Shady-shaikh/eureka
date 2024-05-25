@extends('backend.layouts.app')
@section('title', 'Create Bill Booking ')

@section('content')
    <style>
        .adjust_col {
            width: 115px;
        }
    </style>
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title"> Bill Booking </h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> Bill Booking </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.billbooking') }}" class="btn btn-outline-secondary float-right"><i
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
                                {{ Form::open(['url' => 'admin/billbooking/store']) }}
                                {{ Form::hidden('bill_to_state', '', ['class' => 'bill_to_state']) }}
                                {{ Form::hidden('party_name', '', ['class' => 'party_name']) }}
                                {{ Form::hidden('party_state', '', ['class' => 'party_state']) }}
                                {{ Form::hidden('bill_to_gst_no', '', ['class' => 'bill_to_gst_no']) }}
                                @csrf

                                <div class="form-body">


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">


                                                <div class="col-sm-6">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="col-md-3 col-sm-12">
                                                            <div class="form-group">
                                                                {{ Form::hidden('bill_date', date('Y-m-d'), [
                                                                    'class' => ' form-control digits',
                                                                    'placeholder' => 'PurchaseOrder Date',
                                                                    'required' => true,
                                                                    'readonly' => true,
                                                                ]) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('vendor_id', 'Business Partner Code *') }}
                                                            {{ Form::select('vendor_id', $party_code, null, [
                                                                'class' => 'form-control tags',
                                                                'id' => 'party_code',
                                                                'placeholder' => 'Select Business Partner Code',
                                                                'required' => true,
                                                            ]) }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('vendor_id', 'Business Partner Type *') }}
                                                            {{ Form::select('vendor_id', $party, null, [
                                                                'class' => 'form-control tags',
                                                                'id' => 'party_id',
                                                                'placeholder' => 'Select Business Partner Type',
                                                                'required' => true,
                                                            ]) }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            {{ Form::label('invoice_ref_date', 'Invoice Refrence Date') }}
                                                            {{ Form::date('invoice_ref_date', null, [
                                                                'class' => 'form-control remarks',
                                                                'placeholder' => 'Invoice Refrence Date',
                                                                'required' => true,
                                                            ]) }}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="col-sm-12">


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
                                                    'required' => true,
                                                ]) }}
                                            </div>

                                            @php
                                                use App\Models\backend\Company;
                                                $company = Company::where('company_id', session('company_id'))->first();
                                            @endphp
                                            @if ($company->is_backdated_date)
                                                <div class="form-group">
                                                    {{ Form::label('bill_date', 'Date *') }}
                                                    {{ Form::date('bill_date', date('Y-m-d'), [
                                                        'class' => 'form-control ',
                                                        'placeholder' => 'Date',
                                                        'required' => true,
                                                    ]) }}
                                                </div>
                                            @endif


                                            <div class="form-group">
                                                {{ Form::label('posting_date', 'Posting Date') }}
                                                {{ Form::date('posting_date', date('Y-m-d'), [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Posting Date',
                                                    'required' => true,
                                                    'readonly' => true,
                                                ]) }}

                                            </div>



                                        </div>

                                        {{--
                                    </div> --}}
                                    </div>


                                    <div class="col-sm-12">
                                        <section id="form-repeater-wrapper">

                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <hr>
                                                </div>


                                                <div class="conatiner-fluid table-responsive repeater">
                                                    <button type="button" data-repeater-create
                                                        class="btn btn-primary pull-right mb-2 add_btn_rep">Add</button>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered " id="repeater"
                                                            style="width:120%;">
                                                            <thead class="bg-light" style="font-size:10px;">
                                                                <tr>
                                                                    <td>{{ Form::label('sr_no', 'Sr No.') }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::label('item_name', 'Description') }}
                                                                    </td>

                                                                    <td class="adjust_col">
                                                                        {{ Form::label('invoice_ref_no', 'Invoice Ref No.') }}
                                                                    </td>

                                                                    <td>{{ Form::label('expense_id', 'Expense Name') }}
                                                                    </td>


                                                                    <td>{{ Form::label('bsplstype_id', 'Type') }}
                                                                    </td>
                                                                    <td>{{ Form::label(
                                                                        'bsplsubcat_id',
                                                                        'Expense
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Sub-Category',
                                                                    ) }}
                                                                    </td>
                                                                    <td>{{ Form::label('bsplcat_id', 'Expense Category') }}
                                                                    </td>
                                                                    <td>{{ Form::label('bsplheads_id', 'Heads') }}</td>

                                                                    <td>{{ Form::label('gl_code', 'GL Code') }}
                                                                    </td>
                                                                    <td>{{ Form::label('amount', 'Amount') }}</td>

                                                                    <td></td>

                                                                </tr>
                                                            </thead>
                                                            <tbody data-repeater-list="invoice_items">

                                                                <?php
                                                                  
                                                                for ($i=0; $i < count(old('invoice_items')??['1']); $i++){ 
                                                                ?>

                                                                <tr data-repeater-item class="item_row ">



                                                                    <td class="sr_no" style="width:50px!important;"></td>

                                                                    <td>{{ Form::text('description', old('invoice_items')[$i]['description'] ?? null, [
                                                                        'data-id' => 'description',
                                                                        'id' => 'description',
                                                                        'data-name' => 'description',
                                                                        'class' => 'form-control description ',
                                                                        'autocomplete' => 'off',
                                                                        'required' => true,
                                                                    ]) }}
                                                                    </td>

                                                                    <td class="adjust_col">
                                                                        {{ Form::text('invoice_ref_no', old('invoice_items')[$i]['invoice_ref_no'] ?? null, [
                                                                            'class' => 'form-control invoice_ref_no',
                                                                            'id' => 'invoice_ref_no',
                                                                            'placeholder' => 'Refrence
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Number',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'invoice_ref_no',
                                                                            'data-group' => 'invoice_items',
                                                                        ]) }}
                                                                    </td>

                                                                    <td class="adjust_col">
                                                                        {{ Form::select('expense_id', $expense_data, old('invoice_items')[$i]['expense_id'] ?? null, [
                                                                            'data-id' => 'particular',
                                                                            'id' => 'expense_id',
                                                                            'placeholder' => 'Select Particular',
                                                                            'data-name' => 'particular',
                                                                            'class' => 'form-control particular
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            typeahead',
                                                                            'autocomplete' => 'off',
                                                                            'required' => true,
                                                                        ]) }}
                                                                    </td>


                                                                    <td class="adjust_col">
                                                                        {{ Form::select('bsplstype_id', [], old('invoice_items')[$i]['bsplstype_id'] ?? null, [
                                                                            'class' => 'form-control bsplstype_id',
                                                                            'placeholder' => 'Select Type',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'bsplstype_id',
                                                                            'data-group' => 'invoice_items',
                                                                            'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::select('bsplsubcat_id', [], old('invoice_items')[$i]['bsplsubcat_id'] ?? null, [
                                                                            'class' => 'form-control bsplsubcat_id',
                                                                            'placeholder' => 'Select Expense Sub-Categroy',
                                                                            'data-name' => 'bsplsubcat_id',
                                                                            'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::select('bsplcat_id', [], old('invoice_items')[$i]['bsplcat_id'] ?? null, [
                                                                            'class' => 'form-control bsplcat_id',
                                                                            'placeholder' => 'Select Expense Category',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'bsplcat_id',
                                                                            'data-group' => 'invoice_items',
                                                                            'readonly' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td class="adjust_col">
                                                                        {{ Form::select('bsplheads_id', [], old('invoice_items')[$i]['bsplheads_id'] ?? null, [
                                                                            'class' => 'form-control bsplheads_id',
                                                                            'id' => 'bsplheads_id',
                                                                            'placeholder' => 'Select Head',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'bsplheads_id',
                                                                            'data-group' => 'invoice_items',
                                                                            'readonly' => true,
                                                                        ]) }}
                                                                    </td>

                                                                    <td class="adjust_col">
                                                                        {{ Form::select('gl_code', [], old('invoice_items')[$i]['gl_code'] ?? null, [
                                                                            'class' => 'form-control gl_code',
                                                                            'id' => 'gl_code',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'placeholder' => 'Select GL Code',
                                                                            'data-name' => 'gl_code',
                                                                            'data-group' => 'invoice_items',
                                                                            'readonly' => true,
                                                                        ]) }}
                                                                    </td>
                                                                    <td class="adjust_col">
                                                                        {{ Form::text('amount', old('invoice_items')[$i]['amount'] ?? null, [
                                                                            'class' => 'form-control total',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'total',
                                                                            'data-group' => 'invoice_items',
                                                                            'required' => true,
                                                                        ]) }}
                                                                    </td>


                                                                    <td style="width:50px!important;"><button type='button'
                                                                            id="del_btn"
                                                                            class='btn btn-danger btn-flat btn-xs old_rep_item_del'
                                                                            data-repeater-delete><i
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
                                        </section>

                                    </div>





                                    <div class="col-sm-12">

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
        $(document).on('change', '.item_row .particular', function() {
            var expense_id = $(this).val();
            var $currentRow = $(this).closest('.item_row'); // Get the current row

            // Find the dependent dropdown within the current row
            var $bsplstypeDropdown = $currentRow.find('.bsplstype_id');
            var $bsplsubcatDropdown = $currentRow.find('.bsplsubcat_id');
            var $bsplcatDropdown = $currentRow.find('.bsplcat_id');
            var $bsplheadsDropdown = $currentRow.find('.bsplheads_id');
            var $glCodeDropdown = $currentRow.find('#gl_code');

            // Perform an AJAX request to populate the dependent dropdowns
            $.ajax({
                method: 'post',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                url: '{{ route('admin.get_expense') }}',
                data: {
                    id: expense_id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data != '') {
                        // Populate the dependent dropdowns in the current row
                        $bsplstypeDropdown.html('<option value=' + data.get_type.bsplstype_id +
                            ' selected>' +
                            data.get_type.bspl_type_name + '</option>');
                        $bsplsubcatDropdown.html('<option value=' + data.get_sub_cat.bsplsubcat_id +
                            ' selected>' + data.get_sub_cat.bspl_subcat_name + '</option>');
                        $bsplcatDropdown.html('<option value=' + data.get_cat.bsplcat_id +
                            ' selected>' + data.get_cat.bspl_cat_name + '</option>');
                        $bsplheadsDropdown.html('<option value=' + data.get_heads.bsplheads_id +
                            ' selected>' + data.get_heads.bspl_heads_name + '</option>');
                        $glCodeDropdown.html('<option value=' + data.get_gl.gl_code + ' selected>' +
                            data.get_gl
                            .gl_code + '</option>');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.repeater').repeater({
                isFirstItemUndeletable: true,
                // initEmpty: false,
            });


            // 08-01-2024 -usama
            $('#party_code').on('change', function() {
                $('#party_id').val($(this).val()).trigger('change.select2');
            });

            $('#party_id').on('change', function() {
                $('#party_code').val($(this).val()).trigger('change.select2');
            });

        });
    </script>




    <script>
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


            var customer_id = $('#party_id option:selected').val();
            if (customer_id != "") {
                get_data_display(customer_id);
            }
            $("#party_id").on('change', function() {
                $(".gst_dropdown").val('');
                $(".all_gst").val('');
                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
            });


        });
    </script>

@endsection
