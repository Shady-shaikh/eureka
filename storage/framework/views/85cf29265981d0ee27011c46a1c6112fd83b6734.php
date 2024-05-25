
<?php $__env->startSection('title', 'Promo / Scheme Grid'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Promo / Scheme Grid</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Promo / Scheme Grid</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create PROMO / SCHEME GRID')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.scheme.create')); ?>">
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
                    <h4 class="card-title">Promo / Scheme Grid</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        
                                        <th>Name</th>
                                        <th class="no_export">Price Data</th>
                                        <?php if(Auth()->guard('admin')->user()->role != 41): ?>
                                        <th></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($pricings) && count($pricings) > 0): ?>
                                    <?php $srno = 1; ?>
                                    <?php $__currentLoopData = $pricings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pricing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($srno); ?></td>
                                        
                                        <td><?php echo e($pricing->pricing_name); ?></td>
                                        <td class="no_export"><a
                                                href="<?php echo e(route('admin.scheme.form', ['id' => $pricing->pricing_master_id])); ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <?php if(Auth()->guard('admin')->user()->role != 41): ?>
                                                Update
                                                <?php else: ?>
                                                View
                                                <?php endif; ?>
                                            </a></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Update PROMO / SCHEME GRID')): ?>
                                            <a href="<?php echo e(url('admin/scheme/edit/' . $pricing->pricing_master_id)); ?>"
                                                class="btn btn-primary" title="Edit"><i
                                                    class="feather icon-edit"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete PROMO / SCHEME GRID')): ?>
                                            <?php echo Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/scheme/delete', $pricing->pricing_master_id],
                                            'style' => 'display:inline',
                                            ]); ?>

                                            <?php echo Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'title' => 'Delete',
                                            'class' => 'btn btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry
                                            ?')",
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/marginscheme/scheme.blade.php ENDPATH**/ ?>