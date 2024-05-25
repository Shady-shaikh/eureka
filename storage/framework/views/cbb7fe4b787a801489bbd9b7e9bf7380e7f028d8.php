
<?php $__env->startSection('title', 'Internal Users'); ?>
<?php
use Spatie\Permission\Models\Role;
?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Internal User</h3>
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
                        <?php echo e(Form::open(['url' => 'admin/user/store'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('first_name', 'First Name *')); ?>

                                        <?php echo e(Form::text('first_name', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter First Name',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('last_name', 'Last Name')); ?>

                                        <?php echo e(Form::text('last_name', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Last Name',
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('email', 'Email *')); ?>

                                        <?php echo e(Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true])); ?>

                                        
                                        <?php echo e(Form::hidden('password', 'Pass@123', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter First
                                        Name',
                                        'required' => true,
                                        ])); ?>

                                        <?php echo e(Form::hidden('account_status', 1, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter First
                                        Name',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>


                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('role', 'Role *')); ?>

                                        <select name="role" id="role_id" class='form-control'>

                                            <?php $__currentLoopData = $role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value['id']); ?>"> <?php echo e($value['name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- zones //27-02-2024 -->
                                <div class="col-md-6 col-12 zone_drp">
                                    <div class="form-group">
                                        <?php echo e(Form::label('zone_id', 'Zone *')); ?>

                                        <?php echo e(Form::select('zone_id', $zones, null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Zone',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12 company_drp">
                                    <div class="form-group">
                                        <?php echo e(Form::label('company_id', 'Distributor')); ?>

                                        <?php echo e(Form::select('company_id', $company, null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Distributor',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div id="parentRolesContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="col md-12">
                                <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                                <button type="reset" class="btn btn-dark mr-1 mb-1">Reset</button>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        $('.company_drp').hide(); //show company only for salesman, distributor
        $('#role_id').on('change', function() {
            var role_id = $(this).val();
            // alert(role_id);
            if (role_id == 17) {
                $('.company_drp').remove();
                $('.zone_drp').hide();
            }
            $('.company_drp').hide(); //show company only for salesman, distributor // 28-02-2024
            if (role_id == 37 || role_id == 41) {
                $('.company_drp').show();
                // $('.zone_drp').remove();
            }
            
            // if (role_id != 37 ) {
                $.ajax({
                    url: "<?php echo e(route('admin.get_parent_roles')); ?>", // Replace with your actual endpoint
                    method: 'GET',
                    data: {
                        role_id: role_id
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
            // }

        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/admin/create.blade.php ENDPATH**/ ?>