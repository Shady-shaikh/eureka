
<?php $__env->startSection('title', 'Create Series Number'); ?>
<?php
use Spatie\Permission\Models\Role;
?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Series Number</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.seriesmaster')); ?>">Series Master</a>
                    </li>
                    <li class="breadcrumb-item active">Create Series Number</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.seriesmaster')); ?>">
                    <i class="feather icon-arrow-left"></i> Back
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
                        <?php echo e(Form::open(['url' => 'admin/seriesmaster/store'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">


                            <div class="row">



                                <?php if(session('company_id') == 0): ?>
                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('company_id', 'Distributor *')); ?>

                                        <?php echo e(Form::select('company_id',$company, null, ['class' => 'form-control',
                                        'placeholder'
                                        => 'Select Distributor', 'required' => true])); ?>

                                    </div>
                                </div>
                                <?php endif; ?>


                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('series_number', 'Series Abbreviation *')); ?>

                                        <?php echo e(Form::text('series_number', null, ['class' => 'form-control', 'placeholder'
                                        => 'Series Abbreviation', 'required' => true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('transaction_type', 'Transaction Type *')); ?>

                                        <?php echo e(Form::select('transaction_type',
                                        DB::table('transaction_types')->pluck('name','name'),null, ['class' => 'form-control',
                                        'placeholder' => 'Select Transaction Type', 'required' => true])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('module', 'Select Module Name *')); ?>

                                        <?php echo e(Form::select('module', $modules,null, ['class' => 'form-control',
                                        'placeholder' => 'Select Module Name', 'required' => true])); ?>

                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <br>
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('sample_doc_no', 'Sample Doc Number :')); ?>

                                        <div class="" id="sample_doc_no"></div>
                                    </div>
                                </div>



                            </div>
                            <div class="row mt-3">
                                <div class="col md-12 ">
                                    <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                                    <button type="reset" class="btn btn-secondary mr-1 mb-1">Reset</button>
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

<script>
    $('#module').on('change',function(){
            var doc_number = $('#series_number').val() + '-' + '2023-24' + '-' + '1';
            $('#sample_doc_no').html(doc_number);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/seriesmaster/create.blade.php ENDPATH**/ ?>