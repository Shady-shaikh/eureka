
<?php $__env->startSection('title', 'MyProfile'); ?>
<?php $__env->startSection('content'); ?>

<div class="content-header row">
  <div class="content-header-left col-12 mb-2">
    <h3 class="content-header-title">My Profile</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">My Profile</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo Form::model($adminuser, [
            'method' => 'POST',
            'url' => ['admin/update_profile'],
            'class' => 'form'
            ]); ?>

            <div class="form-body">
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <?php echo e(Form::hidden('admin_user_id', $adminuser->admin_user_id)); ?>

                    <?php echo e(Form::label('first_name', 'First Name *')); ?>

                    <?php echo e(Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'required' => true])); ?>

                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <?php echo e(Form::label('last_name', 'Last Name *')); ?>

                    <?php echo e(Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name', 'required' => true])); ?>

                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <?php echo e(Form::label('mobile_no', 'Mobile *')); ?>

                    <?php echo e(Form::text('mobile_no', null, ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number', 'required' => true])); ?>

                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <?php echo e(Form::label('email', 'Email *')); ?>

                    <?php echo e(Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'readonly' => 'readonly'])); ?>

                  </div>
                </div>
                <div class="col-12 d-flex justify-content-start">
                  <?php echo e(Form::submit('Update', array('class' => 'btn btn-primary mr-1'))); ?>

                </div>
              </div>
            </div>
            <?php echo e(Form::close()); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/admin/myprofile.blade.php ENDPATH**/ ?>