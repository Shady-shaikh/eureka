
<?php $__env->startSection('title', 'Gst Values'); ?>

<?php $__env->startSection('content'); ?>
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0">Gst Values</h5>
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb p-0 mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active">Gst Values
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
              <h4 class="card-title">Gst Values</h4>
            </div>
            <div class="card-content">
              <div class="card-body card-dashboard">
                <div class="col-12 text-right mb-2">
                  <a href="<?php echo e(route('gst_value.create')); ?>" class="btn btn-primary"><i class="bx bx-plus"></i>
                    Add </a>
                </div>
                <div class="table-responsive">
                  <table class="table zero-configuration" id="tbl-datatable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Value</th>
                        <th class="no_export">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($data) && count($data)>0): ?>
                      <?php $srno = 1; ?>
                      <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($srno); ?></td>
                        <td><?php echo e($row->value); ?></td>
                        <td class="no_export">

                          <a class="btn btn-sm btn-primary" title="Edit"
                            href="<?php echo e(route('gst_value.edit',['gst_value'=>$row->id])); ?>"><i
                              class="feather icon-edit"></i></a>

                      
                          <form action="<?php echo e(route('gst_value.destroy', ['gst_value' => $row->id])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" onclick="return confirm(`Are you sure you want to Delete this Entry ?`)" class="btn btn-sm btn-danger"><i class="feather icon-trash"></i></button>
                          </form>

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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/gstvalue/index.blade.php ENDPATH**/ ?>