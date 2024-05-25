
<?php $__env->startSection('title', 'Attachments'); ?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Attachments</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="<?php echo e(route('admin.claims')); ?>">Claims</a>
                    </li>
                    <li class="breadcrumb-item active">Attachments</li>
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
                        <?php echo e(Form::open(['url' => 'admin/claims/attachments_store','enctype'=>'multipart/form-data'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">
                                <input type="hidden" name="id" value="<?php echo e($id); ?>">
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('retailer_vendor_dn', 'Retailer/ Vendor DN *')); ?>

                                        <?php echo e(Form::file('retailer_vendor_dn', [ 'class' =>'form-control','accept' =>
                                        '.doc,.docx, .xls, .xlsx, .pdf, .csv','required'=>true])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('distributor_dn', 'Distributor DN *')); ?>

                                        <?php echo e(Form::file('distributor_dn', [ 'class' => 'form-control', 'accept' => '.doc,
                                        .docx, .xls, .xlsx, .pdf, .csv','required'=>true])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('invoice_supp_docs', 'Invoice Supporting Docs *')); ?>

                                        <?php echo e(Form::file('invoice_supp_docs', [ 'class' => 'form-control', 'accept' =>
                                        '.doc, .docx, .xls, .xlsx, .pdf, .csv','required'=>true])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-group">
                                        <?php echo e(Form::label('approval_supp_docs', 'Approval Supproting Docs *')); ?>

                                        <?php echo e(Form::file('approval_supp_docs', [ 'class' => 'form-control', 'accept' =>
                                        '.doc, .docx, .xls, .xlsx, .pdf, .csv','required'=>true])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group mt-2">
                                        <?php echo e(Form::submit('Upload', ['class' => 'btn btn-primary'])); ?>

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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/claims/attachments.blade.php ENDPATH**/ ?>