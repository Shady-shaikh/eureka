
<?php $__env->startSection('title', 'Update Pricing Ladder'); ?>

<?php $__env->startSection('content'); ?>

<?php
use App\Models\backend\Company;
use App\Models\backend\Pricings;
use App\Models\backend\Subdmargin;

?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Update Pricing Ladder</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.pricingladder')); ?>">Pricing Ladder</a>
                    </li>
                    <li class="breadcrumb-item active">Update Pricing Ladder</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.pricingladder')); ?>">
                    <i class="feather icon-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Pricing Ladder</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <?php echo Form::model($pricings, [
                        'method' => 'POST',
                        'url' => ['admin/pricingladder/modify'],
                        'class' => 'form',
                        'enctype' => 'multipart/form-data',
                        ]); ?>

                        <?php echo e(Form::hidden('pricing_master_id', null, ['required' => true, 'id' => 'pricing_master_id'])); ?>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('pricing_name', 'Name *')); ?>

                                        <?php echo e(Form::text('pricing_name', null, ['class' => 'form-control',
                                        'placeholder' =>'Enter Name', 'required' => true, 'id' => 'sku_description'])); ?>

                                    </div>
                                </div>


                                <?php echo e(Form::select('pricing_type',['ladder'=>'Pricing Ladder'],null, ['class' =>
                                'form-control d-none'])); ?>



                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('bp_channel', 'Business Partner Channel *')); ?>

                                        <?php echo e(Form::select('bp_channel', $business_partner_category,null, [
                                        'class' => 'form-control select2 ',
                                        'placeholder' => 'Business Partner Channel',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('margin', 'Margin Structure *')); ?>

                                        <?php echo e(Form::select('margin',
                                        [],
                                        null,
                                        [
                                        'class' => 'form-control select2 ',
                                        'placeholder'=>'Please Select',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('scheme', 'Promo / Scheme Grid *')); ?>

                                        <?php echo e(Form::select('scheme',
                                        [],null,
                                        [
                                        'class' => 'form-control select2 ',
                                        'placeholder'=>'Please Select',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('subd_margin', 'Sub-D Margin %')); ?>

                                        <?php echo e(Form::select('subd_margin',
                                        Subdmargin::distinct()->pluck('margin','id'),
                                        null,
                                        [
                                        'class' => 'form-control select2 ',
                                        'placeholder'=>'Please Select',
                                        ])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('distributor_margin', 'Distributor Margin *')); ?>

                                        <?php echo e(Form::number('distributor_margin',null, [
                                        'class' => 'form-control ',
                                        'placeholder' => 'Enter Distributor Margin',
                                        'required' => true,
                                        ])); ?>

                                    </div>
                                </div>



                                


                                <div class="col-12 d-flex justify-content-start">
                                    <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1'])); ?>

                                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
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

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('public/backend-assets/js/DynamicDropdown.js')); ?>"></script>

<script>
    var bp_channel = "<?php echo e($pricings->bp_channel); ?>";
    var margin = "<?php echo e($pricings->margin); ?>";
    var scheme = "<?php echo e($pricings->scheme); ?>";
    if(bp_channel){
        new DynamicDropdown('<?php echo e(route('admin.getMargins')); ?>',
        bp_channel, '#margin',margin)

        new DynamicDropdown('<?php echo e(route('admin.getScheme')); ?>',
        bp_channel, '#scheme',scheme)
    }
    // usama_15-03-2024-fetch bp-group from channel
    $('#bp_channel').change(function() {
        var selectedValue = $(this).val();
        new DynamicDropdown('<?php echo e(route('admin.getMargins')); ?>',
            selectedValue, '#margin')

        new DynamicDropdown('<?php echo e(route('admin.getScheme')); ?>',
            selectedValue, '#scheme')
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/marginscheme/pricingladder_edit.blade.php ENDPATH**/ ?>