@extends('backend.layouts.app')
@section('title', 'Claim')
@php
use Spatie\Permission\Models\Role;
use App\Models\backend\Brands;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\ExpenseTypes;
use App\Models\backend\Gstvalue;


@endphp
@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Claim</h3>
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
                        {{ Form::open(['url' => 'admin/claims/update/' . $model->id]) }}
                        @csrf
                        {{ Form::hidden('id', $model->id, ['class' =>
                        'form-control']) }}

                        <div class="form-body">
                            <div class="row">

                                @if(session('company_id') != 0)
                                <input type="hidden" name="company_id" id="company_id"
                                    value="{{ session('company_id') }}">
                                @else
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('company_id', 'Distributor *') }}
                                        {{ Form::select('company_id', $company, $model->company_id, [ 'class' =>
                                        'form-control',
                                        'placeholder' => 'Select Distributor',
                                        'required'=>true
                                        ]) }}
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        {{ Form::label('doc_date', 'Document Date *') }}
                                        {{ Form::date('doc_date', $model->doc_date, [ 'class' => 'form-control',
                                        'readonly'=>true
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{ Form::label('party_id', 'Customer/ Vendor Name *') }}
                                        {{
                                        Form::select('party_id',[]
                                        ,$model->party_id, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Select Customer/ Vendor Name',
                                        'required' => true,
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 d-none doc_no">
                                    <div class="form-group">
                                        {{ Form::label('doc_no', 'Doc Number')
                                        }}
                                        {{ Form::text('doc_no', $model->doc_no, [
                                        'class' => 'form-control doc_no',
                                        'placeholder' => 'Doc Number',
                                        'readonly' => true,
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 for_customer">
                                    <div class="form-label-group">
                                        {{ Form::label('bp_channel', 'Business Partner Channel *') }}
                                        {{ Form::select('bp_channel',[], $model->bp_channel, [
                                        'class' => 'form-control ',
                                        'placeholder' => 'Select Business Partner Channel',
                                        'readonly'=>true,
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 for_customer">
                                    <div class="form-label-group">
                                        {{ Form::label('bp_group', 'Business Partner group *') }}
                                        {{ Form::select('bp_group',
                                        [],
                                        null, [
                                        'class' => 'form-control ',
                                        'placeholder' => 'Select Business Partner Group',
                                        'readonly'=>true
                                        ]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('retail_dn_date','Retailer/ Vendor DN Date')}}
                                        {{Form::date('retail_dn_date',$model->retail_dn_date,['class'=>'form-control',
                                        'max'=>date('Y-m-d')])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('retail_dn_no','Retailer/ Vendor DN Number')}}
                                        {{Form::text('retail_dn_no',$model->retail_dn_no,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN Number'
                                        ])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('retail_dn_desc','Retailer/ Vendor DN Discription')}}
                                        {{Form::text('retail_dn_desc',$model->retail_dn_desc,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN Discription'
                                        ])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('importer_dn_date','Distributor DN Date *')}}
                                        {{Form::date('importer_dn_date',$model->importer_dn_date,['class'=>'form-control',
                                        'max'=>date('Y-m-d'),
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('importer_dn_desc','Distributor DN Discription *')}}
                                        {{Form::text('importer_dn_desc',$model->importer_dn_desc,['class'=>'form-control',
                                        'placeholder'=>'Distributor DN Discription',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('bar_code','Bar Code')}}
                                        {{Form::text('bar_code',$model->bar_code,['class'=>'form-control',
                                        'placeholder'=>'Bar Code',
                                        ])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('activity_month','Activity Month *')}}
                                        {{Form::month('activity_month',$model->activity_month,['class'=>'form-control',
                                        'placeholder'=>'Activity Month',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('ret_dn_rec_date','Retailer/ Vendor DN received date *')}}
                                        {{Form::date('ret_dn_rec_date',$model->ret_dn_rec_date,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN received date','max'=>date('Y-m-d'),
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('location','Location *')}}
                                        {{Form::text('location',$model->location,['class'=>'form-control',
                                        'placeholder'=>'Location',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('brand','Brand Name')}}
                                        {{Form::select('brand',Brands::pluck('brand_name','brand_id'),
                                        $model->brand,[
                                        'class'=>'form-control select2',
                                        'placeholder'=>'Brand Name'
                                        ])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('expense_type','Expense type *')}}
                                        {{Form::select('expense_type',ExpenseTypes::pluck('expense_type_name','expense_type_id'),
                                        $model->expense_type,['class'=>'form-control',
                                        'placeholder'=>'Select Expense type',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('claim_type','Claim Type *')}}
                                        {{Form::select('claim_type',['tts'=>'TTS'],$model->claim_type,['class'=>'form-control',
                                        'placeholder'=>'Select Claim Type',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{Form::label('importer_deb_note','Description - Distributor Debit Note *')}}
                                        {{Form::text('importer_deb_note',$model->importer_deb_note,['class'=>'form-control',
                                        'placeholder'=>'Select Description - Distributor Debit Note',
                                        'required'=>true])}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{ Form::label('debit_value', 'Debit Value *') }}
                                        {{ Form::number('debit_value', $model->debit_value, ['class' => 'form-control',
                                        'placeholder'
                                        => 'Enter Debit Value', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{ Form::label('gst_value', 'GST Value (%) *') }}
                                        {{ Form::select('gst_value',Gstvalue::pluck('value','value') ,$model->gst_value,
                                        ['class' =>'form-control',
                                        'placeholder' =>'Select GST Value', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        {{ Form::label('total_debit_note', 'Total Debit note value *') }}
                                        {{ Form::number('total_debit_note', $model->total_debit_note, ['class' =>
                                        'form-control',
                                        'placeholder' => 'Enter Total Debit note value', 'readonly' => true]) }}
                                    </div>
                                </div>



                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2">
                                        {{ Form::submit('Save', ['class' => 'btn btn-primary ']) }}
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

@section('scripts')


<script>
    // Get the current date
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth() + 1; // JavaScript months are 0-indexed

    // Set the maximum value for the month input field
    document.getElementById('activity_month').max = currentYear + "-" + ("0" + currentMonth).slice(-2);
</script>



{{-- fetch total debit value,parties and channel group --}}
<script>
    function get_parties(val_id,party_id=null){
        $.ajax({
            url: "{{route('admin.get_companies')}}",
            type: 'get',
            data: {
                company_id: val_id,
            },
            dataType: 'json',
            success: function(data) {
                var html = '<option value="">Select Customer/ Vendor Name</option>';
                $.each(data, function(id, val) {
                    if(party_id == id){
                        html += '<option value="'+id+'" selected>'+val+'</option>'; 
                    }else{
                        html += '<option value="'+id+'">'+val+'</option>'; 
                    }
                })
                $('#party_id').html(html);
                $('#party_id').trigger('change');
            }
        });
    }

    function get_channel_group(val_id){
        if(val_id){

            $.ajax({
                url: "{{route('admin.get_company')}}",
                type: 'get',
                data: {
                    party_id: val_id,
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data.bp_master);
                    if(data.bp_master){
                        if(data.bp_master.business_partner_type == 3){
                            $('.for_customer').addClass('d-none');
                            $('#bp_channel').val(''); 
                            $('#bp_group').val(''); 
                        }else{
                            $('.for_customer').removeClass('d-none');
                            $('#bp_channel').append($('<option>', {
                                value: data.bp_master.bp_channel,
                                text: data.bp_master.get_category.business_partner_category_name,
                                selected: true
                            }));
                            $('#bp_group').append($('<option>', {
                                value: data.bp_master.bp_group,
                                text: data.bp_master.get_group.name,
                                selected: true
                            }));
                        }
                    }
                }
            });

            $('.doc_no').removeClass('d-none');
        }
        
    }
    // Function to calculate GST value and total debit note value
    function calculateValues() {
        var debitValue = parseFloat($('#debit_value').val());
        var gstValue = parseFloat($('#gst_value').val());
        var gstAmount = debitValue*gstValue / 100;
        var totalDebitNoteValue = Math.floor(debitValue + gstAmount);


        // Set the calculated values in the respective input fields
        $('#total_debit_note').val(totalDebitNoteValue);
    }

    // Listen for changes in the debit value input field
    $('#debit_value,#gst_value').on('input', function() {
        calculateValues();
    });

    $('#company_id').on('change', function() {
        get_parties($(this).val());
    });

    $('#party_id').on('change', function() {
        get_channel_group($(this).val());
    });

    $(document).ready(function(){
        var company_id = $('#company_id').val();
        var party_id = $('#party_id').val();
        if(company_id){
            var party_id = "{{$model->party_id}}";
            get_parties(company_id,party_id);
        }
        if(party_id){
            get_channel_group(party_id);
        }
    });
</script>





@endsection