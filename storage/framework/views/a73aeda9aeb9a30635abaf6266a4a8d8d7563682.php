
<?php $__env->startSection('title', 'Internal Users'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Internal User</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Internal User</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create External Users')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.create')); ?>">
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
                                        <th>Sr. No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($adminusers) && count($adminusers) > 0): ?>
                                    <?php $srno = 1; ?>
                                    <?php $__currentLoopData = $adminusers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($users->userrole) && $users->userrole->department_id != 1): ?>
                                    <tr>
                                        <td><?php echo e($srno); ?></td>
                                        <td><?php echo e($users->first_name); ?></td>
                                        <td class="email_text"><?php echo e($users->email); ?></td>
                                        <td><?php echo e($users->userrole->name); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-secondary btn_send_email"><i
                                                    class="feather icon-mail" title="Send Credentials"></i></a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Update External Users')): ?>
                                            <a href="<?php echo e(url('admin/user/edit/' . $users->admin_user_id)); ?>"
                                                class="btn btn-primary"><i class="feather icon-edit-2"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete External Users')): ?>
                                            <?php echo Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/user/delete', $users->admin_user_id],
                                            'style' => 'display:inline',
                                            ]); ?>

                                            <?php echo Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry?')",
                                            ]); ?>

                                            <?php echo Form::close(); ?>

                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                    <?php endif; ?>
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

<script>
    $(document).ready(function(){
        $('.btn_send_email').click(function(e){
            e.preventDefault();
            var swalAlert = Swal.fire({
                title: 'Sending Email',
                text: 'Please wait...',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
            // Get the email address from the current row
            var email = $(this).closest('tr').find('.email_text').text().trim();
    
            // Make AJAX request
            $.get(APP_URL + '/admin/master/send_email', { email: email }, function(response) {
                if(response){
                    swalAlert.close();
                    // Show success alert
                    Swal.fire({
                        icon: "success",
                        title: "Credentials Sent",
                        text: "Email Sent Successfully",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            });
    
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/admin/index.blade.php ENDPATH**/ ?>