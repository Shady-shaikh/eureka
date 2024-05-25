
<?php $__env->startSection('title', 'Edit Beat Calender'); ?>
<?php
use Spatie\Permission\Models\Role;
use Carbon\CarbonPeriod;
?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Beat Calender</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.beatcalender')); ?>">Beat Calender</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.beatcalender')); ?>">
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
                        <?php echo e(Form::open(['url' => 'admin/beatcalender/update'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">
                                <?php echo e(Form::hidden('beat_cal_id', $model->beat_cal_id, ['class' => 'form-control'])); ?>




                                <div class="col-md-6 col-6">
                                    <div class="form-group">

                                        <?php echo e(Form::label('beat_month', 'Beat Month *')); ?>

                                        <?php echo e(Form::select('beat_month',$all_data['month'],$model->beat_month, ['class' =>
                                        'form-control tags', 'placeholder' => 'Select Beat Month', 'required' => true])); ?>


                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">


                                        <?php echo e(Form::label('beat_week', 'Beat Week *')); ?>

                                        <?php echo e(Form::select('beat_week', $all_data['week'],$model->beat_week, ['class' =>
                                        'form-control ', 'placeholder' => 'Select Beat Week', 'required' => true])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group">

                                        <?php echo e(Form::label('beat_day', 'Beat Day *')); ?>

                                        <?php echo Form::select('beat_day',$all_data['days'] ,
                                        $model->beat_day, ['class' => 'form-control tags']); ?>


                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('beat_id', 'Beat *')); ?>


                                        <?php echo Form::select('beat_id',$beats ,
                                        $model->beat_id, ['class' => 'form-control tags']); ?>

                                    </div>
                                </div>




                                <div class="col-md-6 col-6 d-none">
                                    <div class="form-group">

                                        <?php echo e(Form::label('beat_year', 'Beat Year *')); ?>

                                        <?php echo e(Form::select('beat_year',$all_data['year'],$model->beat_year, ['class' =>
                                        'form-control tags', 'placeholder' => 'Select Beat Year', 'required' => true])); ?>


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
                    </div>

                </div>


                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>

    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/beatcalender/edit.blade.php ENDPATH**/ ?>