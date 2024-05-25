
<?php $__env->startSection('title', 'Admin-Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<?php
use Spatie\Permission\Models\Role;

$role = Role::where('id',$user_id = Auth()->guard('admin')->user()->role)->first();
?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-2">Welcome to Dashboard</h3>
        <?php if($role->department_id == 1): ?>
        <h6><a href="https://drive.google.com/file/d/1Zgaypf4Fo531YruSxB4tpJ_6IbW3xXQr/view?usp=sharing"
                target="_blank">Click Here</a> to get Eureka mobile application now !</h6>
        <?php endif; ?>
        
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/admin/dashboard.blade.php ENDPATH**/ ?>