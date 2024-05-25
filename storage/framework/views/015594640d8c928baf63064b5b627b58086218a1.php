
<?php $__env->startSection('title', 'Traffic Source'); ?>

<?php $__env->startSection('content'); ?>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Traffic Source</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i
                                            class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Traffic Source
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Traffic Source
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">

                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="tbl-datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>DATE</th>
                                                <th>EMAIL</th>
                                                <th>IP ADDRESS</th>
                                                <!--<th>HTTP_USER_AGENT</th>-->
                                                <th>OPERATING SYSTEM</th>
                                                <th>DEVICE</th>
                                                <th>BROWSER</th>
                                                <th>LOGIN TYPE</th>



                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if(isset($externaluser) && count($externaluser) > 0): ?>
                                            <?php $srno = 1; ?>
                                            <?php $__currentLoopData = $externaluser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($srno); ?></td>
                                                <td><?php echo e(date('d-m-Y H:i', strtotime($user->created_at))); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->REMOTE_ADDR); ?></td>
                                                <!--<td><?php echo e($user->HTTP_USER_AGENT); ?></td>-->
                                                <td><?php echo e($user->user_os); ?></td>
                                                <td><?php echo e(ucfirst($user->device)); ?></td>
                                                <td><?php echo e($user->browser); ?></td>
                                                <td><?php echo e(strtoupper($user->traffic_source)); ?></td>


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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/trafficsource/index.blade.php ENDPATH**/ ?>