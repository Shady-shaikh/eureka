
<?php $__env->startSection('title', 'Internal Users'); ?>
<?php
use Spatie\Permission\Models\Role;
use App\Models\backend\Beat;
?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Internal User</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.users')); ?>">
                    <i class="feather icon-arrow-left"></i> Back
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
                        <?php echo e(Form::open(['url' => 'admin/user/update'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('fullname', 'First Name *')); ?>

                                        <?php echo e(Form::hidden('admin_user_id', $userdata->admin_user_id, ['class' =>
                                        'form-control'])); ?>

                                        <?php echo e(Form::text('first_name', $userdata->first_name, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter First Name',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('fullname', 'Last Name')); ?>

                                        <?php echo e(Form::text('last_name', $userdata->last_name, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Last Name',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('email', 'Email *')); ?>

                                        <?php echo e(Form::text('email', $userdata->email, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Email',
                                        'required' => true,
                                        ])); ?>


                                    </div>
                                </div>

                                

                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('role_id', 'Role *')); ?>

                                        <select name="role" id="role_id" class='form-control'>
                                            <?php $__currentLoopData = $role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value['id']); ?>" <?php if($value['id']==$userdata->role): ?>
                                                selected <?php endif; ?>>
                                                <?php echo e($value['name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- zones //27-02-2024 -->
                                <div class="col-md-6 col-12 zone_drp">
                                    <div class="form-group">
                                        <?php echo e(Form::label('zone_id', 'Zone *')); ?>

                                        <?php echo e(Form::select('zone_id', $zones, $userdata->zone_id, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Zone',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 company_drp">
                                    <div class="form-group">
                                        <?php echo e(Form::label('company_id', 'Distributor')); ?>

                                        <?php echo e(Form::select('company_id', $company, $userdata->company_id, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Distributor',
                                        'required' => true,
                                        ])); ?>


                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div id="parentRolesContainer">
                                    </div>
                                </div>




                                <div class="col md-12 ">
                                    <br>
                                    <center>
                                        <?php echo e(Form::submit('Update', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                                    </center>
                                </div>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo e(Form::open(['url' => 'admin/user/status'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="col-12">
                                <div class="col-md-12">
                                    <div class="form-label-group">
                                        <?php echo e(Form::label('fullname', 'Status *')); ?>

                                        <?php echo e(Form::hidden('admin_user_id', $userdata->admin_user_id, ['class' =>
                                        'form-control'])); ?>

                                        <?php echo Form::select('account_status', ['0' => 'Inactive', '1' => 'Active'],
                                        $userdata->account_status, [
                                        'class' => 'form-control',
                                        ]); ?>

                                    </div>
                                    <div class="mt-1 text-center">
                                        <?php echo e(Form::submit('Update', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                                        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div>

    </div>

</section>



<script>
    function get_parent_roles(role_id) {

            if (role_id == 17) {
                $('.company_drp').remove();
                $('.zone_drp').remove();
            }
            $('.company_drp').hide(); //show company only for salesman, distributor // 28-02-2024
            if (role_id == 37 || role_id == 41) {
                $('.company_drp').show();
                // $('.zone_drp').remove();
            }
            $.ajax({
                url: "<?php echo e(route('admin.get_parent_roles')); ?>", // Replace with your actual endpoint
                method: 'GET',
                data: {
                    role_id: role_id,
                    admin_user_id: <?php echo e(request('id')); ?>

                },
                success: function(response) {
                    // Handle the response from the server
                    $('#parentRolesContainer').html(response);
                },
                error: function(error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }


        $(document).ready(function() {
            var role_id = <?php echo e($userdata->role ?? ''); ?>;
            $('.company_drp').hide(); //show company only for salesman, distributor
            $('#company_id').removeAttr('required');

            get_parent_roles(role_id);
        });

        $('#role_id').on('change', function() {
            get_parent_roles($(this).val());
        });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/admin/edit.blade.php ENDPATH**/ ?>