
<?php $__env->startSection('title', 'Gst'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">GST</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">GST</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.gst.create')); ?>">
                    <i class="feather icon-plus"></i> Add
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content content">
    <div class="content-overlay"></div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="content-header row">
                                <div class="col-sm-12 text-end mb-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create GST')): ?>
                                    <a href="<?php echo e(url('admin/gst/create')); ?>" class="btn btn-primary"><i class="icofont icofont-plus"></i> Add </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>GST Percent(%)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($gst) && count($gst)>0): ?>
                                        <?php $srno = 1; ?>
                                        <?php $__currentLoopData = $gst; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($srno); ?></td>
                                            <td><?php echo e($item->gst_name); ?></td>
                                            <td><?php echo e($item->gst_percent); ?></td>
                                            <td>
                                                
                                                <a href="<?php echo e(url('admin/gst/edit/'.$item->gst_id)); ?>" class="btn btn-primary"><i class="feather icon-edit-2" title="Edit"></i></a>
                                                
                                                <?php echo Form::open([
                                                'method'=>'GET',
                                                'url' => ['admin/gst/delete', $item->gst_id],
                                                'style' => 'display:inline'
                                                ]); ?>

                                                
                                                <?php echo Form::button('<i class="feather icon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger','onclick'=>"return confirm('Are you sure you want to Delete this Entry ?')","title"=>"Delete"]); ?>

                                                
                                                <?php echo Form::close(); ?>

                                            </td>
                                        </tr>
                                        <?php $srno++; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/gst/index.blade.php ENDPATH**/ ?>