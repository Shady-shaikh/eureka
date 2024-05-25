
<?php $__env->startSection('title', Request::segment(4) === 'edit' ? 'Edit Gst Value' : 'Create Gst Value'); ?>


<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo e(Request::segment(4) === 'edit' ? 'Edit' : 'Create'); ?> Gst Value</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('gst_value.index')); ?>">Gst Values</a></li>
                    <li class="breadcrumb-item active"><?php echo e(Request::segment(4) === 'edit' ? 'Edit' : 'Create'); ?> Gst Value</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php if(Request::segment(4) === 'edit'): ?>
                        <?php echo e(Form::model($data, ['route' => ['gst_value.update', $data->id], 'method'
                        => 'PUT'])); ?>

                        <?php else: ?>
                        <?php echo e(Form::open(['route' => 'gst_value.store'])); ?>

                        <?php endif; ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">

                                        <?php echo e(Form::label('value', 'Name *')); ?>

                                        <?php echo e(Form::number('value', Request::segment(4) ===
                                        'edit'?$data->value:null, ['class' => 'form-control',
                                        'placeholder'
                                        => 'Enter Percentage', 'required' => true])); ?>

                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-start">
                                    <?php echo e(Form::submit('Save', array('class' => 'btn btn-primary mr-1 mb-1'))); ?>

                                    <button type="reset"
                                        class="btn btn-light-secondary mr-1 mb-1 text-white">Reset</button>
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/gstvalue/form.blade.php ENDPATH**/ ?>