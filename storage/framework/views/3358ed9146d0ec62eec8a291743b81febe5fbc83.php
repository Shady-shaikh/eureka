
<?php $__env->startSection('title', 'View Claim'); ?>
<?php
use Spatie\Permission\Models\Role;
use App\Models\backend\AdminUsers;
use App\Models\backend\Company;
use App\Models\backend\ClaimStatus;
?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">View Claim</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">View</li>
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
                        <div class="form-body">
                            <h4 class="text-center">Claim Details</h4>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('doc_date', 'Document Date')); ?>

                                        <?php echo e(Form::text('doc_date', $claim->doc_date, [ 'class' => 'form-control',
                                        'readonly'=>true
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('party_id', 'Customer/ Vendor Name')); ?>

                                        <?php echo e(Form::text('party_id',$claim->get_party->bp_name, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('doc_no', 'Doc Number')); ?>

                                        <?php echo e(Form::text('doc_no',$claim->doc_no, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('location', 'Location')); ?>

                                        <?php echo e(Form::text('location',$claim->location, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('claim_type', 'Claim Type')); ?>

                                        <?php echo e(Form::text('claim_type',strtoupper($claim->claim_type), [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('expense_type', 'Expense Type')); ?>

                                        <?php echo e(Form::text('expense_type',$claim->expense->expense_type_name, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('debit_value', 'Debit Value')); ?>

                                        <?php echo e(Form::text('debit_value',$claim->debit_value, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('gst_value', 'GST Value (%) ')); ?>

                                        <?php echo e(Form::text('gst_value',$claim->gst_value, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('total_debit_note', 'Total Debit Note Value')); ?>

                                        <?php echo e(Form::text('total_debit_note',$claim->total_debit_note, [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        ])); ?>

                                    </div>
                                </div>

                            </div>
                            <hr>


                            <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo e(Form::open(['url' => 'admin/claims/update_status','enctype'=>'multipart/form-data'])); ?>

                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <input type="hidden" name="id" value="<?php echo e($claim->id); ?>">
                                <input type="hidden" name="role" value="<?php echo e(Auth()->guard('admin')->user()->role); ?>">
                                <?php
                                $status = ClaimStatus::pluck('name','id');
                                if($claim->created_by == Auth()->guard('admin')->user()->admin_user_id){
                                $status[0] = 'Pending';
                                $can_update_status = true;
                                }else if(Auth()->guard('admin')->user()->role == 41 && $claim->is_approved_by_dis){
                                $can_update_status=true;
                                }else if(Auth()->guard('admin')->user()->role == 42 && $claim->is_approved_by_channel){
                                $can_update_status=true;
                                }else if(Auth()->guard('admin')->user()->role == 43 && $claim->is_approved_by_head){
                                $can_update_status=true;
                                }else if(Auth()->guard('admin')->user()->role == 44 && $claim->is_approved_by_finance){
                                $can_update_status=true;
                                }else{
                                $can_update_status=false;
                                }

                                ?>

                                <div class="col-md-6">
                                    <h5>Recent Claim Details</h5>
                                    <?php
                                    $created_by_company =
                                    Company::where('company_id',$claim->get_user->company_id)->first();
                                    ?>
                                    <p>Created By : <span><b><?php echo e($claim->get_user->full_name); ?>

                                                (<?php echo e($created_by_company->is_subd?'Sub
                                                Distributor':$claim->get_user->userrole->name); ?>)</b></span></p>
                                    <?php
                                    $user = AdminUsers::where('admin_user_id',$claim->user)->first();
                                    $company = Company::where('company_id',$user->company_id)->first();
                                    ?>
                                    <p>Last Updated By : <span><b><?php echo e($user->full_name); ?>

                                                (<?php echo e(isset($company)?($company->is_subd?'Sub
                                                Distributor':$user->userrole->name):$user->userrole->name); ?>)</b></span>
                                    </p>
                                    <h4>Timeline</h4>
                                    <ul>
                                        <li>aSD</li>
                                    </ul>
                                    

                                    <br>
                                    <h5>Supproting Attachments</h5>
                                    <br>
                                    <?php if(!empty($claim->supporting_docs)): ?>
                                    <div class="col-md-3">
                                        <label for="supporting_docs">Supporting Docs</label>
                                        <?php
                                        $doc_data = is_image($claim->supporting_docs);
                                        $filePath = $doc_data['path'];
                                        ?>
                                        <div class="form-group">
                                            <?php if(isset($doc_data) && $doc_data['status']): ?>
                                            <a href="<?php echo e($filePath); ?>" id="supporting_docs" target="_blank">
                                                <img src="<?php echo e($filePath); ?>" alt="Supporting Docs"
                                                    style="max-width: 100px; max-height: 100px;">
                                            </a>
                                            <?php else: ?>
                                            <a href="<?php echo e($filePath); ?>" id="supporting_docs" target="_blank">
                                                <i class="feather icon-file" style="font-size: 48px;"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('status', 'Status')); ?>

                                        <?php echo e(Form::select('status',$status
                                        ,$claim->status, [
                                        'class' => 'form-control',
                                        'readonly'=>$can_update_status
                                        ])); ?>

                                    </div>
                                </div>

                                <?php if($claim->created_by != Auth()->guard('admin')->user()->admin_user_id): ?>
                                <div class="col-md-6 col-12">
                                    <br>
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('supporting_docs', 'Attachments')); ?>

                                        <?php echo e(Form::file('supporting_docs', [
                                        'class' => 'form-control',
                                        'accept' =>'.doc,.docx, .xls, .xlsx, .pdf, .csv'
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('remarks', 'Remarks')); ?>

                                        <?php echo e(Form::textarea('remarks',null, [
                                        'class' => 'form-control',
                                        'placeholder'=>'Enter Remakrs',
                                        'rows'=>2,
                                        ])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2">
                                        <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary '])); ?>

                                        <button type="reset" class="btn btn-secondary ">Reset</button>

                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>

                            <hr>
                            <h4 class="text-center">View Claim Attachments</h4>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="retailer_vendor_dn">Retailer/ Vendor DN</label>
                                    <?php
                                    $doc_data = is_image($claim->retailer_vendor_dn);
                                    $filePath = $doc_data['path'];
                                    ?>
                                    <div class="form-group">
                                        <?php if(isset($doc_data) && $doc_data['status']): ?>
                                        <a href="<?php echo e($filePath); ?>" id="retailer_vendor_dn" target="_blank">
                                            <img src="<?php echo e($filePath); ?>" alt="Retailer/ Vendor DN"
                                                style="max-width: 100px; max-height: 100px;">
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e($filePath); ?>" id="retailer_vendor_dn" target="_blank">
                                            <i class="feather icon-file" style="font-size: 48px;"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="distributor_dn">Distributor DN</label>
                                    <?php
                                    $doc_data = is_image($claim->distributor_dn);
                                    $filePath = $doc_data['path'];
                                    ?>
                                    <div class="form-group">
                                        <?php if(isset($doc_data) && $doc_data['status']): ?>
                                        <a href="<?php echo e($filePath); ?>" id="distributor_dn" target="_blank">
                                            <img src="<?php echo e($filePath); ?>" alt="Distributor DN"
                                                style="max-width: 100px; max-height: 100px;">
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e($filePath); ?>" id="distributor_dn" target="_blank">
                                            <i class="feather icon-file" style="font-size: 48px;"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="invoice_supp_docs">Invoice Supporting Docs</label>
                                    <?php
                                    $doc_data = is_image($claim->invoice_supp_docs);
                                    $filePath = $doc_data['path'];
                                    ?>
                                    <div class="form-group">
                                        <?php if(isset($doc_data) && $doc_data['status']): ?>
                                        <a href="<?php echo e($filePath); ?>" id="invoice_supp_docs" target="_blank">
                                            <img src="<?php echo e($filePath); ?>" alt="Invoice Supporting Docs"
                                                style="max-width: 100px; max-height: 100px;">
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e($filePath); ?>" id="invoice_supp_docs" target="_blank">
                                            <i class="feather icon-file" style="font-size: 48px;"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="approval_supp_docs">Approval Supproting Docs</label>
                                    <?php
                                    $doc_data = is_image($claim->approval_supp_docs);
                                    $filePath = $doc_data['path'];
                                    ?>
                                    <div class="form-group">
                                        <?php if(isset($doc_data) && $doc_data['status']): ?>
                                        <a href="<?php echo e($filePath); ?>" id="approval_supp_docs" target="_blank">
                                            <img src="<?php echo e($filePath); ?>" alt="Approval Supproting Docs"
                                                style="max-width: 100px; max-height: 100px;">
                                        </a>
                                        <?php else: ?>
                                        <a href="<?php echo e($filePath); ?>" id="approval_supp_docs" target="_blank">
                                            <i class="feather icon-file" style="font-size: 48px;"></i>
                                        </a>
                                        <?php endif; ?>
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/claims/view.blade.php ENDPATH**/ ?>