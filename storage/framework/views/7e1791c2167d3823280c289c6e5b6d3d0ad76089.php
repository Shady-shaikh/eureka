
<?php $__env->startSection('title', 'Products History'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Products History (Till Last Year)</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Products History</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            </div>
        </div>
    </div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Products History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="no_export">Image</th>
                                            <th>Item Type</th>
                                            <th>Item Code</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Date & Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($products) && count($products) > 0): ?>
                                            <?php $srno = 1; ?>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($srno); ?></td>
                                                    <td class="no_export">
                                                        <?php if(!empty($product->product_thumb)): ?>
                                                            <a href="<?php echo e(asset('public/backend-assets/images/')); ?>/<?php echo e($product->product_thumb); ?>"
                                                                target="_blank"><img class="card-img-top img-fluid mb-1"
                                                                    src="<?php echo e(asset('public/backend-assets/images/')); ?>/<?php echo e($product->product_thumb); ?>"
                                                                    alt="Product Image" style="width:50px"></a>
                                                        <?php endif; ?>
                                                    </td>
                                                    </td>
                                                    <td><?php echo e(isset($product->item_type) ? $product->item_type->item_type_name : ''); ?>

                                                    </td>
                                                    <td><?php echo e($product->item_code); ?></td>
                                                    <td><?php echo e($product->consumer_desc); ?></td>
                                                    <td><?php echo e(isset($product->brand) ? $product->brand->brand_name : ''); ?></td>
                                                    <td><?php echo e(isset($product->category) ? $product->category->category_name : ''); ?>

                                                    </td>

                                                    <td><?php echo e($product->created_at); ?></td>
                                                    <td>
                                                        

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Products History')): ?>
                                                            <a href="<?php echo e(url('admin/productshistory/show/' . $product->product_revision_id)); ?>"
                                                                class="btn btn-primary" title="Edit"><i
                                                                    class="feather icon-eye"></i></a>
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/productshistory/index.blade.php ENDPATH**/ ?>