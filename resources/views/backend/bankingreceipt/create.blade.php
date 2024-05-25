@extends('backend.layouts.app')
@section('title', 'Create Banking Receipt ')

@section('content')
<style>
    .adjust_col {
        width: 115px;
    }
</style>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title"> Banking Receipt </h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active"> Banking Receipt </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.bankingreceipt') }}" class="btn btn-outline-secondary float-right"><i
                        class="bx bx-arrow-back"></i><span class="align-middle ml-25">Back</span></a>
            </div>
        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card ">
                <div class="card-content">
                    <div class="card-body ">


                        @include('backend.includes.errors')
                        {{ Form::open(['url' => 'admin/bankingreceipt/store']) }}

                        <div class="form-body ">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="col-md-12 col-sm-12">

                                                {{-- {{ Form::label('bill_date', 'PurchaseOrder Date *') }} --}}
                                                {{ Form::hidden('bill_date', date('Y-m-d'), ['class' => ' form-control
                                                digits', 'placeholder' => 'PurchaseOrder Date', 'required' => true,
                                                'readonly' => true]) }}

                                            </div>

                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('transaction_type', 'Transaction Type *') }}
                                                    {{ Form::select('transaction_type', ['neft' => 'NEFT', 'imps' =>
                                                    'IMPS', 'rtgs' => 'RTGS'], null, ['class' => 'form-control',
                                                    'placeholder' => 'Select Transaction Type', 'required' => true]) }}
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('overdue_range', 'Overdue Range') }}
                                                    {{ Form::text('overdue_range', null, ['class' => 'form-control
                                                    remarks', 'placeholder' => 'Overdue Range', 'required' => true]) }}
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('payment_type', 'Payment Type') }}
                                                    {{ Form::select('payment_type', ['regular' => 'Regular', 'urgent' =>
                                                    'Urgent'], null, ['class' => 'form-control remarks', 'required' =>
                                                    true]) }}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">

                                            <div class="col-sm-12">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('vendor_id', 'Business Partner Code *') }}
                                                        {{ Form::select('vendor_id', $party_code, null, ['class' =>
                                                        'form-control tags', 'id' => 'party_code', 'placeholder' =>
                                                        'Select
                                                        Business Partner Code', 'required' => true]) }}
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-sm-12">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{ Form::label('vendor_id', 'Business Partner Type *') }}
                                                        {{ Form::select('vendor_id', $party, null, ['class' =>
                                                        'form-control tags', 'id' => 'party_id', 'placeholder' =>
                                                        'Select
                                                        Business Partner Type', 'required' => true]) }}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>
         


                                    <div class="col-md-3 col-sm-3">


                                    </div>

                                    <div class="col-md-3 col-sm-3">
                                      

                                        @php
                                        use App\Models\backend\Company;
                                        $company = Company::where('company_id',session('company_id'))->first();
                                        @endphp
                                        @if($company->is_backdated_date)
                                        <div class="form-group">
                                            {{ Form::label('bill_date', 'Date *') }}
                                            {{ Form::date('bill_date', date('Y-m-d'), ['class' => 'form-control ',
                                            'placeholder' => 'Date', 'required' => true]) }}
                                        </div>
                                        @endif

                                        <div class="form-group">

                                            {{ Form::label('posting_date', 'Posting Date') }}

                                            {{ Form::date('posting_date', date('Y-m-d'), ['class' => 'form-control
                                            ship_from', 'placeholder' => 'Posting Date', 'required' => true,
                                            'readonly' => true]) }}

                                        </div>
                                    </div>

                                    {{--
                                </div> --}}
                            </div>


                            <hr>
                            <div class="col-sm-12">
                                <section class="">

                                    <div class="row ">

                                        <table class="table table-bordered " id="dynamic-table" style="font-size:12px;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <td><input type="checkbox" id="select-all-checkbox" /></td>
                                                    <td>{{ Form::label('sr_no', 'Sr No.') }}</td>
                                                    <td>{{ Form::label('doc_no', 'Doc No.') }}</td>
                                                    <td>{{ Form::label('item_name', 'Description') }}</td>
                                                    <td>{{ Form::label('item_name', 'Particular') }}</td>
                                                    <td>{{ Form::label('hsn_sac', 'Expense Type') }}</td>
                                                    <td>{{ Form::label('qty', 'Expense Sub-Type') }}</td>
                                                    <td>{{ Form::label('taxable_amount', 'GL Code') }}</td>
                                                    <td>{{ Form::label('discount', 'Amount') }}</td>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="table-body">

                                            </tbody>
                                        </table>



                                    </div>
                                </section>

                            </div>

                            <div class="row">


                                <div class="col-md-2 col-sm-12">
                                    <p>Net Total <strong><span class="netTotal">0</span></strong></p>
                                    <input type="text" name="net_total" value="0" class="netTotal d-none">
                                </div>
                    
                            </div>


                            <hr>
                            <h3>Banking Details</h3>
                            <br>


                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('bank', 'Select Bank') }}
                                    {{ Form::select('bank', [], null, ['class' => 'form-control', 'id' => 'bank_id',
                                    'data-name' => 'hsn_sac', 'required' => true]) }}
                                </div>
                            </div>



                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('account_no', 'Account Number') }}
                                    <input type="text" name="account_no" id="account_no" class="form-control"
                                        placeholder="Account Number" readonly>
                                </div>
                            </div>


                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('bank_branch', 'Bank Branch') }}
                                    <input type="text" name="bank_branch" id="bank_branch" class="form-control"
                                        placeholder="Bank Branch" readonly>
                                </div>
                            </div>


                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('bank_ifsc', 'Bank IFSC') }}
                                    <input type="text" name="bank_ifsc" id="bank_ifsc" class="form-control"
                                        placeholder="Bank IFSC" readonly>

                                 
                                </div>
                            </div>







                            <div class="col-sm-12">
                                {{--
                                <hr> --}}
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1', 'id' => 'submit-button'])
                            }}
                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
</section>
</div>
</div>



{{-- for dynamic table --}}
@include('backend.dynamic_list_banking_payment_script')


<script>
    $(document).ready(function() {

            // 08-01-2024 -usama
            $('#party_code').on('change', function() {
                $('#party_id').val($(this).val()).trigger('change.select2');
            });

            $('#party_id').on('change', function() {
                $('#party_code').val($(this).val()).trigger('change.select2');
            });
            // Event listener for the form submission
            $('#submit-button').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission


                var requiredFields = $('form [required]');
                // console.log('Number of required fields:', requiredFields.length);
                var valid = true;

                requiredFields.each(function() {
                    if (!$(this).val()) {
                        valid = false;
                        console.log('Empty field detected:', $(this));
                        return false; // Exit the loop early
                    }
                });

                if (!valid) {
                    console.log('Validation failed');
                    // alert('Please fill out all required fields.');
                    toastr.error('Please fill out all required fields.');
                    return;
                }
                // Serialize the form data
                var formData = $('form').serialize();
                // console.log(formData);

                // Send the form data via an AJAX request
                $.ajax({
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    url: '{{ route('admin.bankingreceipt.store') }}',
                    data: formData,
                    // dataType: 'json',

                    success: function(response) {
                        // Handle the response if needed
                        toastr.success('New Banking Receipt Added');
                        setTimeout(() => {
                            window.location.href =
                                '{{ route('admin.bankingreceipt') }}';
                        }, 800);

                    },
                    error: function(xhr, status, error) {
                        toastr.error('Please Select Atleast One Bill Booking Item.');
                 
                        console.error(xhr, status, error);
                    }
                });
            });
        });
</script>


{{-- dependent dropdown for head,cat,sub-cat,type --}}
<script>
    // area_field
        function get_all_bspl_data(route, id, appendid, placeholder, edit_id = null, old_val = null) {
            // alert(id);

            $('#account_no').val('');
            $('#bank_branch').val('');
            $('#bank_ifsc').val('');
            if (appendid == 'account_no') {

                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    url: route,
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data != '') {
                            // $.each(data, function(key, value) {
                            $('#' + appendid).val(data.ac_number);
                            $('#bank_branch').val(data.bank_branch);
                            $('#bank_ifsc').val(data.ifsc);
                            // });
                        } else {
                            $('#' + appendid).html('<option value="">' + placeholder + '</option>');
                        }

                    }
                });
            } else {
                $('#' + appendid).html('<option value="">' + placeholder + '</option>');
                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    url: route,
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data != '') {
                            $.each(data, function(key, value) {

                                if (old_val == key) {
                                    $("#" + appendid).append('<option value="' + old_val +
                                        '" selected>' + value + '</option>');
                                } else if (edit_id == key) {
                                    $("#" + appendid).append('<option value="' + edit_id +
                                        '" selected>' + value + '</option>');
                                } else {
                                    $("#" + appendid).append('<option value="' + key +
                                        '">' + value + '</option>');
                                }
                            });
                        } else {
                            $('#' + appendid).html('<option value="">' + placeholder + '</option>');
                        }

                    }
                });
            }
        }
        $(document).ready(function() {
            // $('#bank_id').html('<option value="">Select Bank</option>');
            $('#account_no').html('<option value="">Select Acoount Number</option>');


            //on edit data for area and route
            var party_id = $('#party_id :selected').val();
            var bank_id = <?php echo isset($model->bank_id) ? $model->bank_id : 0; ?>;
            var account_no = <?php echo isset($model->account_no) ? $model->account_no : 0; ?>;

            var old_bank_id = <?php echo json_encode(old('bank_id')); ?>;
            var old_account_no = <?php echo json_encode(old('account_no')); ?>;

            var old_val = '';
            var route = '';
            var appendid = '';
            var placeholder = '';
            var edit_id = '';


            if (bank_id || old_account_no) {
                route = '{{ route('admin.bankacc') }}';
                appendid = 'account_no';
                placeholder = 'Select Account Number';
                edit_id = account_no;
                old_val = old_account_no;
                if (account_no == 0) {
                    account_no = old_account_no;
                }

                get_all_bspl_data(route, account_no, appendid, placeholder, edit_id, old_val);
            }

           // Assuming that party_id and party_code are the IDs of your party select elements
            $('#party_id, #party_code').change(function() {
                // Get the selected party ID
                var partyId = $(this).val();

                // Make an Ajax request to fetch bank data
                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    url: '{{ route("admin.bank") }}',
                    data: {
                        id: partyId  // Pass the selected party ID to the server
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            var html = '<option value="">Select Bank</option>';
                            Object.entries(data).forEach(function([key, value]) {
                                html += '<option value="' + value + '" >' + key + '</option>';
                            });
                            $("#bank_id").html(html);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle the error case
                        console.error(xhr.responseText);
                    }
                });
            });




            //on create data 
            $('#bank_id').on('change', function() {
                var id = $(this).val();
                var idname = this.id;

                if (idname == 'party_id') {
                    route = '{{ route('admin.bank') }}';
                    appendid = 'bank_id';
                    placeholder = 'Select Bank';
                } else if (idname == 'bank_id') {
                    route = '{{ route('admin.bankacc') }}';
                    appendid = 'account_no';
                    placeholder = 'Select Account Number';
                }

                // if (id) {
                get_all_bspl_data(route, id, appendid, placeholder);
                // }

            });







        });
</script>




@endsection