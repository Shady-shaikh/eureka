
<?php $__env->startSection('title', 'Series Master'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title"> Series Master</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active"> Series Master</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Series Master')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.seriesmaster.create')); ?>">
                    <i class="feather icon-plus"></i> Add
                </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Series Master </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        
                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Series Abbreviation</th>
                                        <th>Transaction Type</th>
                                        <th>Module Name</th>
                                        <th>Sample Doc Number</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($details) && count($details) > 0): ?>
                                    <?php $srno = 1; ?>
                                    <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($srno); ?></td>

                                        <td><?php echo e($data->series_number); ?></td>
                                        <td><?php echo e($data->transaction_type); ?></td>
                                        <?php
                                        $modules = DB::table('modules')
                                        ->where('id', $data->module)
                                        ->pluck('name', 'id');
                                        ?>
                                        <td><?php echo e($modules[$data->module]); ?></td>
                                        <td>
                                            <?php echo e($data->series_number); ?>-2023-24-1
                                        </td>
                                        <td>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Update Series Master')): ?>
                                            <a href="<?php echo e(url('admin/seriesmaster/edit/' . $data->id)); ?>"
                                                class="btn btn-primary" title="Edit"><i
                                                    class="feather icon-edit"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Series Master')): ?>
                                            <?php echo Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/seriesmaster/delete', $data->id],
                                            'style' => 'display:inline',
                                            ]); ?>

                                            <?php echo Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'title' => 'Delete',
                                            'class' => 'btn btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry?')",
                                            ]); ?>

                                            <?php echo Form::close(); ?>

                                            <?php endif; ?>

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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/seriesmaster/index.blade.php ENDPATH**/ ?>