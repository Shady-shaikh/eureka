
<?php $__env->startSection('title', 'Bussiness Partner Address'); ?>
<?php
    use Spatie\Permission\Models\Role;
    use App\Models\backend\Country;

?>
<?php $__env->startSection('content'); ?>
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Create Bussiness Partner Address</h3>
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
                    <a class="btn btn-outline-primary" href="<?php echo e(route('admin.bussinesspartner.address', ['id' => $id])); ?>">
                        Back
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
                            <?php echo e(Form::open(['url' => 'admin/bussinesspartner/storeaddress'])); ?>

                            <?php echo csrf_field(); ?>
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-12 col-12">
                                        <h4><br>Address Details<br></h4>
                                    </div>
                                    <div class="col-md-6 col-12">

                                        <div class="form-label-group">
                                            <?php echo e(Form::hidden('bussiness_partner_id', $id, ['class' => 'form-control', 'placeholder' => 'Address Type', 'required' => true])); ?>

                                            <?php echo e(Form::label('address_type', 'Address Type')); ?>

                                            <select name="address_type" id="address_type" class='form-control'>
                                                <option value="Official">Official</option>
                                                <option value="Bill-To/ Bill-From">Bill-To/ Bill-From</option>
                                                <option value="Ship-To/ Ship-From">Ship-To/ Ship-From</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('bp_address_name', 'Name ')); ?>

                                            <?php echo e(Form::text('bp_address_name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('building_no_name', 'Building No and Name ')); ?>

                                            <?php echo e(Form::text('building_no_name', null, ['class' => 'form-control', 'placeholder' => 'Building No and Name', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('street_name', 'Street Name ')); ?>

                                            <?php echo e(Form::text('street_name', null, ['class' => 'form-control', 'placeholder' => 'Street Name', 'required' => true])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('landmark', 'Landmark ')); ?>

                                            <?php echo e(Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => 'Landmark', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('country', 'Country')); ?>

                                            <?php echo e(Form::select('country', Country::pluck('name','country_id'), null, ['class' => 'form-control','placeholder'=>'Select Country', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('state', 'State')); ?>

                                            <?php echo e(Form::select('state', [], null, ['class' => 'form-control', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('district', 'District ')); ?>

                                            <?php echo e(Form::select('district', [], null, ['class' => 'form-control', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('city', 'Name of City ')); ?>

                                            <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Name of City', 'required' => true])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-label-group">
                                            <?php echo e(Form::label('pin_code', 'Pin Code ')); ?>

                                            <?php echo e(Form::text('pin_code', null, ['class' => 'form-control', 'placeholder' => 'Pin Code', 'pattern' => '[0-9]{6}', 'required' => true])); ?>

                                        </div>
                                    </div>



                                </div> 

                            </div>
                            <div class="col md-12 center mt-3">
                                <center>
                                    <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

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
    </section>

<script src="<?php echo e(asset('public/backend-assets/js/DynamicDropdown.js')); ?>"></script>

     <script>
     $(document).ready(function() {

        // usama_19-02-2024_get states
        $('#country').change(function() {       
        new DynamicDropdown('<?php echo e(route('admin.getStates')); ?>', 
        $(this).val(), '#state',null,'#district');
        });

        $('#state').change(function() {       
        new DynamicDropdown('<?php echo e(route('admin.getCities')); ?>', 
        $(this).val(), '#district',null);
        });

    });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/bussinesspartner/add_address.blade.php ENDPATH**/ ?>