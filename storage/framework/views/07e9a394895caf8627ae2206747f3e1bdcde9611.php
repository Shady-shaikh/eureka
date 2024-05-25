
<?php $__env->startSection('title', 'Create Roles'); ?>

<?php $__env->startSection('content'); ?>
<?php

use App\Models\backend\BackendMenubar;
use Spatie\Permission\Models\Role;
use App\Models\backend\BackendSubMenubar;
use Spatie\Permission\Models\Permission;


$user_id = Auth()->guard('admin')->user()->id;

$backend_menubar = BackendMenubar::Where(['visibility'=>1])->orderBy('sort_order')->get();
?>
<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title">Add Roles</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Add Roles</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary" href="<?php echo e(route('admin.roles')); ?>">
          Back
        </a>
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
            <form method="POST" action="<?php echo e(route('admin.roles.store')); ?>" class="form">
              <?php echo e(csrf_field()); ?>

              <div class="form-body">
                <div class="row">
                  <div class="col-md-12 col-12">
                    <div class="form-label-group">
                      <?php echo e(Form::label('name', 'Role Name *')); ?>

                      <?php echo e(Form::select('name', $departments,null, ['class' => 'form-control', 'placeholder' => 'Select
                      Role Name',
                      'required' => true])); ?>

                    </div>
                  </div>

                  <div class="col-md-12 col-12">
                    <div class="form-label-group">
                      <?php echo e(Form::label('parent_roles', 'Parent Role (If Required) ')); ?>

                      <?php echo e(Form::select('parent_roles', Role::where('department_id','!=',1)->pluck('name', 'id'), null, ['class' => 'form-control
                      select2','placeholder'=>'Select Parent Role'])); ?>

                    </div>
                  </div>


                  <div class="col-md-12 col-12 mt-2 mb-2">
                    <div class="form-label-group">
                      <?php echo e(Form::checkbox('readonly', null, null, ['id'=>'readonly'])); ?>

                      <?php echo e(Form::label('readonly', 'Select All For Read Only *')); ?>

                      <span class="px-2">
                        <?php echo e(Form::checkbox('readwrite', null, null, ['id'=>'readwrite'])); ?>

                        <?php echo e(Form::label('readwrite', 'Select All For Read/Write *')); ?>

                      </span>

                    </div>
                  </div>



                  <?php
                  // dd($has_permissions);

                  foreach($backend_menubar as $menu)
                  {
                  ?>
                  <div class="col-md-12 col-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card " style="background-color: #babfc73d !important;">
                        <?php
                        if($menu->has_submenu == 1)
                        {

                        $backend_submenubar = BackendSubMenubar::Where(['menu_id'=>$menu->menu_id])->get();
                        if($backend_submenubar)
                        {
                        ?>
                        <!-- <h4 class="card-title"><?php echo e($menu->menu_name); ?></h4> -->
                        <div class="card-header " style="background-color: #babfc73d !important;">
                          <h4 class="card-title">
                            <div class="checkbox checkbox-primary">
                              <?php echo e(Form::checkbox('menu_id[]', $menu->menu_id, null, ['id'=>'menu['.$menu->menu_id.']'])); ?>

                              <?php echo e(Form::label('menu['.$menu->menu_id.']', $menu->menu_name)); ?>

                            </div>
                          </h4>
                        </div>
                        <div class="card-body">
                        <div class="row">
                        <?php
                        foreach($backend_submenubar as $submenu)
                        {
                        ?>
                        <div class="col-md-6 col-12">
                          <div class="card-group">
                            <div class="card">
                              <div class="card-body">
                                <!-- <h5 class=""><?php echo e($submenu->submenu_name); ?></h5> -->
                                <h3 class="card-title">
                                  <div class="checkbox checkbox-primary">
                                    <?php echo e(Form::checkbox('submenu_id[]', $submenu->submenu_id, null,
                                    ['id'=>'submenu['.$menu->menu_id.']['.$submenu->submenu_id.']'])); ?>

                                    <?php echo e(Form::label('submenu['.$menu->menu_id.']['.$submenu->submenu_id.']',
                                    $submenu->submenu_name)); ?>

                                  </div>
                                </h3>
                                <div class="col-md-12 col-12 mt-2 menu_permissions">
                                  <ul class="list-unstyled mb-0">
                                    <?php
                                    $backend_permission = explode(',',$submenu->submenu_permissions);
                                    $permissions =
                                    Permission::where('menu_id',$menu->menu_id)->where('submenu_id',$submenu->submenu_id)->get();
                                    $permissions = collect($permissions)->mapWithKeys(function ($item, $key) {
                                    return [$item['base_permission_id'] => ['id'=>$item['id'],'name'=>$item['name']]];
                                    });
                                    // dd($permissions);
                                    ?>
                                    <?php $__currentLoopData = $backend_permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!$permissions->isEmpty()): ?>
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="checkbox checkbox-primary">
                                          <?php
                                          $class='';
                                          if($permission == '7'){
                                          $class = 'viewonly';
                                          }
                                          ?>
                                          <?php echo e(Form::checkbox('permissions['.$permissions[$permission]['id'].']',
                                          $permissions[$permission]['id'], null,
                                          ['id'=>'permissions['.$permissions[$permission]['id'].']','class'=>$class])); ?>

                                          <?php echo e(Form::label('permissions['.$permissions[$permission]['id'].']',
                                          $permissions[$permission]['name'])); ?>

                                        </div>
                                      </fieldset>
                                    </li>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php

                        }
                        echo "</div>
                        </div>";
                        }
                        }
                        else
                        {

                        //$backend_menu_permission = explode(',',$role->menu_ids);
                        ?>
                        <div class="col-md-12">
                          <h4 class="card-title">
                            <div class="checkbox checkbox-primary">
                              <?php echo e(Form::checkbox('menu_id[]', $menu->menu_id, null, ['id'=>'menu['.$menu->menu_id.']'])); ?>

                              <?php echo e(Form::label('menu['.$menu->menu_id.']', $menu->menu_name)); ?>

                            </div>
                          </h4>
                        </div>
                        <div class="col-md-6 col-12 mt-2 menu_permissions">
                          <ul class="list-unstyled mb-0">
                            <?php
                            $backend_permission = explode(',',$menu->permissions);
                            $permissions = Permission::where('menu_id',$menu->menu_id)->get();
                            $permissions = collect($permissions)->mapWithKeys(function ($item, $key) {
                            return [$item['base_permission_id'] => ['id'=>$item['id'],'name'=>$item['name']]];
                            });
                            // dd($permissions);
                            ?>
                            <?php $__currentLoopData = $backend_permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if(isset($permissions[$permission])): ?>
                            <li class="d-inline-block mr-2 mb-1">
                              <fieldset>
                                <div class="checkbox checkbox-primary">
                                  <?php
                                  $class='';
                                  if($permission == '7'){
                                  $class = 'viewonly';
                                  }
                                  ?>

                                  <?php echo e(Form::checkbox('permissions['.$permissions[$permission]['id'].']',
                                  $permissions[$permission]['id'], null,
                                  ['id'=>'permissions['.$permissions[$permission]['id'].']','class'=>$class])); ?>

                                  <?php echo e(Form::label('permissions['.$permissions[$permission]['id'].']',
                                  $permissions[$permission]['name'])); ?>

                                </div>
                              </fieldset>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </ul>
                        </div>
                        <?php
                        }
                        ?>
                      </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="col-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
</div>

<script>
  $("#readwrite").on("click", function(){
        if (this.checked) { 
          $('input:checkbox').not('#readonly').prop('checked', true);
          $('#readonly').prop('checked', false);                    
          }else{
            $('input:checkbox').not('#readonly').prop('checked', false);    
          }
      });

      $("#readonly").on("click", function(){
        if (this.checked) { 
          $('.viewonly').not('#readonly').prop('checked', true); 
          $('input:checkbox').not('.viewonly').not('#readonly').prop('checked', false); 
          $('#readwrite').prop('checked', false);                   
          }else{
            $('.viewonly').not('#readonly').prop('checked', false);    
          }
      });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/roles/create.blade.php ENDPATH**/ ?>