
<?php $__env->startSection('title', 'Roles'); ?>
<?php $__env->startSection('content'); ?>
<?php
use Spatie\Permission\Models\Role;
?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Roles</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Roles')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.roles.create')); ?>">
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
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Parent Role</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($roles) && count($roles) > 0): ?>
                                    <?php $srno = 1; ?>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($srno); ?></td>
                                        <td><?php echo e($role->name); ?></td>
                                        <?php
                                        $role_data = Role::where('id', $role->parent_roles)->first();
                                        // dd($role_data);
                                        ?>
                                        <td><?php echo e($role_data->name ?? ''); ?></td>
                                        <td>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Update Roles')): ?>
                                            <a href="<?php echo e(url('admin/roles/edit/' . $role->id)); ?>"
                                                class="btn btn-primary"><i class="feather icon-edit-2"></i></a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Roles')): ?>
                                            <?php echo Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/roles/delete', $role->id],
                                            'style' => 'display:inline',
                                            ]); ?>

                                            <?php echo Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn
                                            btn-danger',
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
</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/roles/index.blade.php ENDPATH**/ ?>