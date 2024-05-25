
<?php $__env->startSection('title', 'Claim'); ?>
<?php
use Spatie\Permission\Models\Role;
use App\Models\backend\Brands;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\ExpenseTypes;
use App\Models\backend\Gstvalue;


?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Claim</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.claims')); ?>">
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
                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo e(Form::open(['url' => 'admin/claims/store'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">

                                <?php if(session('company_id') != 0): ?>
                                <input type="hidden" name="company_id" id="company_id"
                                    value="<?php echo e(session('company_id')); ?>">
                                <?php else: ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('company_id', 'Distributor *')); ?>

                                        <?php echo e(Form::select('company_id', $company, null, [ 'class' => 'form-control',
                                        'placeholder' => 'Select Distributor',
                                        'required'=>true
                                        ])); ?>

                                    </div>
                                </div>

                                <?php endif; ?>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('doc_date', 'Document Date *')); ?>

                                        <?php echo e(Form::date('doc_date', date('Y-m-d'), [ 'class' => 'form-control',
                                        'readonly'=>true
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('party_id', 'Customer/ Vendor Name *')); ?>

                                        <?php echo e(Form::select('party_id',[]
                                        ,null, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Select Customer/ Vendor Name',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 d-none doc_no">
                                    <div class="form-group">
                                        <?php echo e(Form::label('doc_no', 'Doc Number')); ?>

                                        <?php echo e(Form::text('doc_no', null, [
                                        'class' => 'form-control doc_no',
                                        'placeholder' => 'Doc Number',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 for_customer">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_channel', 'Business Partner Channel *')); ?>

                                        <?php echo e(Form::select('bp_channel',[], null, [
                                        'class' => 'form-control ',
                                        'placeholder' => 'Select Business Partner Channel',
                                        'readonly'=>true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 for_customer">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bp_group', 'Business Partner group *')); ?>

                                        <?php echo e(Form::select('bp_group',[],
                                        null, [
                                        'class' => 'form-control ',
                                        'placeholder' => 'Select Business Partner Group',
                                        'readonly'=>true
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('retail_dn_date','Retailer/ Vendor DN Date')); ?>

                                        <?php echo e(Form::date('retail_dn_date',null,['class'=>'form-control','max' =>
                                        date('Y-m-d')])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('retail_dn_no','Retailer/ Vendor DN Number')); ?>

                                        <?php echo e(Form::text('retail_dn_no',null,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN Number'
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('retail_dn_desc','Retailer/ Vendor DN Discription')); ?>

                                        <?php echo e(Form::text('retail_dn_desc',null,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN Discription'
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('importer_dn_date','Distributor DN Date *')); ?>

                                        <?php echo e(Form::date('importer_dn_date',null,['class'=>'form-control','max' =>
                                        date('Y-m-d'),
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('importer_dn_desc','Distributor DN Discription *')); ?>

                                        <?php echo e(Form::text('importer_dn_desc',null,['class'=>'form-control',
                                        'placeholder'=>'Distributor DN Discription',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('bar_code','Bar Code')); ?>

                                        <?php echo e(Form::text('bar_code',null,['class'=>'form-control',
                                        'placeholder'=>'Bar Code',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('activity_month','Activity Month *')); ?>

                                        <?php echo e(Form::month('activity_month',null,['class'=>'form-control',
                                        'placeholder'=>'Activity Month',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('ret_dn_rec_date','Retailer/ Vendor DN received date *')); ?>

                                        <?php echo e(Form::date('ret_dn_rec_date',null,['class'=>'form-control',
                                        'placeholder'=>'Retailer/ Vendor DN received date','max' => date('Y-m-d'),
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('location','Location *')); ?>

                                        <?php echo e(Form::text('location',null,['class'=>'form-control',
                                        'placeholder'=>'Location',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('brand','Brand Name')); ?>

                                        <?php echo e(Form::select('brand',Brands::pluck('brand_name','brand_id'),null,[
                                        'class'=>'form-control select2',
                                        'placeholder'=>'Brand Name',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('expense_type','Expense Type *')); ?>

                                        <?php echo e(Form::select('expense_type',ExpenseTypes::pluck('expense_type_name','expense_type_id'),null,['class'=>'form-control',
                                        'placeholder'=>'Select Expense type',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('claim_type','Claim Type *')); ?>

                                        <?php echo e(Form::select('claim_type',['tts'=>'TTS'],null,['class'=>'form-control',
                                        'placeholder'=>'Select Claim Type',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('importer_deb_note','Description - Distributor Debit Note *')); ?>

                                        <?php echo e(Form::text('importer_deb_note',null,['class'=>'form-control',
                                        'placeholder'=>'Select Distributor - Importer Debit Note',
                                        'required'=>true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('debit_value', 'Debit Value *')); ?>

                                        <?php echo e(Form::number('debit_value', null, ['class' => 'form-control', 'placeholder'
                                        => 'Enter Debit Value', 'required' => true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('gst_value', 'GST Value (%) *')); ?>

                                        <?php echo e(Form::select('gst_value',Gstvalue::pluck('value','value') ,null, ['class' =>
                                        'form-control', 'placeholder' =>
                                        'Select GST Value', 'required' => true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('total_debit_note', 'Total Debit note value *')); ?>

                                        <?php echo e(Form::number('total_debit_note', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Total Debit note value', 'readonly' => true])); ?>

                                    </div>
                                </div>



                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2">
                                        <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary '])); ?>

                                        <button type="reset" class="btn btn-secondary ">Reset</button>

                                    </div>
                                </div>

                            </div>
                        </div>


                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script>
    // Get the current date
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth() + 1; // JavaScript months are 0-indexed

    // Set the maximum value for the month input field
    document.getElementById('activity_month').max = currentYear + "-" + ("0" + currentMonth).slice(-2);
</script>



<script>
    function get_parties(val_id){
        $.ajax({
            url: "<?php echo e(route('admin.get_companies')); ?>",
            type: 'get',
            data: {
                company_id: val_id,
            },
            dataType: 'json',
            success: function(data) {
                var html = '<option value="">Select Customer/ Vendor Name</option>';
                $.each(data, function(id, val) {
                    html += '<option value="'+id+'">'+val+'</option>'; 
                })
                $('#party_id').html(html);

                $('#party_id').trigger('change');
            }
        });
    }

    function get_channel_group(val_id){
        if(val_id){
            $.ajax({
                url: "<?php echo e(route('admin.get_company')); ?>",
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
            $.ajax({
                method: 'post',
                headers: {
                    'X-CSRF-Token': '<?php echo e(csrf_token()); ?>',
                },
                url: '<?php echo e(route('admin.get_doc_number')); ?>',
                data: {
                    id: '<?php echo e($series_no); ?>',
                    party_id: val_id,
                },
                // dataType: 'json',
                success: function(data) {
                    var matches = data.match(/(\d+)$/);
                    var currentNumber = matches ? parseInt(matches[1], 10) : 0;
                    var newNumber = currentNumber + 1;
                    var newDocNumber = data.replace(/\d+$/, newNumber);
                    $('#doc_no').val(newDocNumber);
                }
            });
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
            get_parties(company_id);
        }
        if(party_id){
            get_channel_group(party_id);
        }
    });
</script>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/claims/create.blade.php ENDPATH**/ ?>