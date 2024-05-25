
<?php $__env->startSection('title', 'Bin Transfer History'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Bin Transfer History</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Bin Transfer History</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="<?php echo e(route('admin.stockmanagement')); ?>">
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
                    <div class="card-header">
                        <h4 class="card-title">Bin Transfer History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>From (Warehouse/Bin)</th>
                                            <th>To (Warehouse/Bin)</th>
                                            <th>Base Pack</th>
                                            <th>Item Description</th>
                                            <th>Item Code</th>
                                            <th>Available Quantity</th>
                                            <th>Transferred Quantity</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($data) && count($data) > 0): ?>
                                            <?php $srno = 1; ?>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                // dd($row->get_from_bin_name->get_bin->name);
                                            ?>
                                                <tr>
                                                    <td><?php echo e($srno); ?></td>
                                                    <td><?php echo e($row->get_from_warehouse_name->storage_location_name); ?> / <?php echo e($row->get_from_bin_name->get_bin->name??''); ?></td>
                                                    <td><?php echo e($row->get_to_warehouse_name->storage_location_name); ?> / <?php echo e($row->get_to_bin_name->get_bin->name??''); ?></td>
                                                    <td><?php echo e($row->sku); ?></td>
                                                    <td><?php echo e($row->item_name); ?></td>
                                                    <td><?php echo e($row->item_code); ?></td>
                                                    <td><?php echo e($row->from_qty); ?></td>
                                                    <td><?php echo e($row->qty); ?></td>
                                                    <td><?php echo e($row->created_at); ?></td>

                                              
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
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/stockmanagement/bin_history.blade.php ENDPATH**/ ?>