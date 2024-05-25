
<?php $__env->startSection('title', 'Create Product'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .select2-container {
        display: block;
        width: 100% !important;
    }
</style>
<?php

?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Products</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.products')); ?>">Products</a>
                    </li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.products')); ?>">
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
                    <h4 class="card-title">Create Product</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo e(Form::open(['url' => 'admin/products/store', 'enctype' => 'multipart/form-data'])); ?>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('item_type_id', 'Type ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('item_type_id', $item_types, null, ['class' => 'select2
                                        form-control ', 'placeholder' => 'Please Select Type', 'id' => 'item_type_id'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('item_code', 'Item Code ')); ?>

                                        <?php echo e(Form::text('item_code', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Item Code', 'required' => true, 'id' => 'item_code'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('consumer_desc', 'Consumer Description (Product Name)')); ?>

                                        <?php echo e(Form::text('consumer_desc', null, ['class' => 'form-control', 'placeholder'
                                        => 'Enter Consumer Description', 'id' => 'consumer_desc'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('product_desc', 'Product Description ')); ?>

                                        <?php echo e(Form::text('product_desc', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Product Description', 'id' => 'product_desc'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('brand_id', 'Brand ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('brand_id', $brands, null, ['class' => 'select2 form-control ',
                                        'placeholder' => 'Please Select Brand', 'id' => 'brand_id'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('category_id', 'Category ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('category_id', $categories, null, ['class' => 'select2
                                        form-control category', 'placeholder' => 'Please Select Category', 'id' =>
                                        'product_category_id'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('sub_category_id', 'Format', ['class' => ''])); ?>

                                        <?php echo e(Form::select('sub_category_id', $sub_categories, null, ['class' => 'select2
                                        form-control subcategory', 'placeholder' => 'Please Select Format'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('variant', 'Variant ')); ?>

                                        <?php echo e(Form::select('variant', $variants, null, ['class' => 'select2 form-control
                                        variant', 'placeholder' => 'Select Variant', 'id' => 'variant'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('combi_type', 'Combi Types ')); ?>

                                        <?php echo e(Form::select('combi_type', DB::table('combi_types')->pluck('name','id'), null, ['class' => 'select2 form-control
                                        ', 'placeholder' => 'Select Combi Type'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('combi_type_int', 'Combi Type Int. ')); ?>

                                        <?php echo e(Form::number('combi_type_int', null, ['class' => 'form-control
                                        ', 'placeholder' => 'Enter Combi Type Int.'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('buom_pack_size', 'BUoM Per Pack Size ')); ?>

                                        <?php echo e(Form::text('buom_pack_size', null, ['class' => 'form-control', 'placeholder'
                                        => 'Enter BUoM Per Pack Size', 'id' => 'buom_pack_size'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('uom_id', 'UoM ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('uom_id', $uoms, null, ['class' => 'select2 form-control uom_id
                                        uom_list', 'placeholder' => 'Please Select UoM'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('unit_case', 'Pack Per Case')); ?>

                                        <?php echo e(Form::number('unit_case', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Pack Per Case', 'id' => 'unit_case'])); ?>

                                    </div>
                                </div>




                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('hsncode_id', 'HSN Code ', ['class' => ''])); ?>

                                        <?php echo e(Form::text('hsncode_id', null, ['class' => 'form-control hsncode_id',
                                        'placeholder' =>'Please Enter HSN Code', 'id' => 'hsncode-input', 'list' =>
                                        'hsncodes'])); ?>


                                        <datalist id="hsncodes"></datalist>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('shelf_life', 'Shelf Life ', ['class' => ''])); ?>

                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo e(Form::text('shelf_life_number', null, ['class' => 'form-control',
                                                'placeholder' => 'Enter How Many'])); ?>


                                            </div>
                                            <div class="col-md-6">
                                                <?php echo e(Form::select('shelf_life', ['Month' => 'Month', 'Days' => 'Days'],
                                                null, ['class' => 'form-control select2', 'id' => 'shelf_life'])); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('sourcing', 'Sourcing Unit Per Source ')); ?>

                                        <?php echo e(Form::text('sourcing', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Sourcing Unit Per Source', 'required' => true, 'id' => 'sourcing'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('case_pallet', 'Case Per Pallet ')); ?>

                                        <?php echo e(Form::text('case_pallet', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Case Per Pallet', 'id' => 'case_pallet'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('layer_pallet', 'Layer Per Pallet ')); ?>

                                        <?php echo e(Form::text('layer_pallet', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Layer Per Pallet', 'id' => 'layer_pallet'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12" id="">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_unit_pack', 'Unit Per Pack ')); ?>

                                        <?php echo e(Form::text('dimensions_unit_pack', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Unit Per Pack'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_length', 'Case Length ')); ?>

                                        <?php echo e(Form::text('dimensions_length', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Length'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_width', 'Case Width')); ?>

                                        <?php echo e(Form::text('dimensions_width', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Width'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_height', 'Case Height')); ?>

                                        <?php echo e(Form::text('dimensions_height', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Height'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_length_uom_id', 'Sides UoM ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('dimensions_length_uom_id', $uoms, null, ['class' => 'select2
                                        form-control dimensions_length_uom_id uom_list', 'placeholder' => 'Please Select
                                        Sides UoM'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_net_weight', 'Net Weight')); ?>

                                        <?php echo e(Form::text('dimensions_net_weight', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Net Weight'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_gross_weight', 'Gross Weight')); ?>

                                        <?php echo e(Form::text('dimensions_gross_weight', null, ['class' => 'form-control',
                                        'placeholder' => 'Enter Gross Weight'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('dimensions_net_uom_id', 'Wt. UoM ', ['class' => ''])); ?>

                                        <?php echo e(Form::select('dimensions_net_uom_id', $uoms, null, ['class' => 'select2
                                        form-control dimensions_net_uom_id uom_list', 'placeholder' => 'Please Select
                                        Wt. UoM'])); ?>

                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('ean_barcode', 'EAN/ Barcode')); ?>


                                        <?php echo e(Form::text('ean_barcode', null, ['class' => 'form-control ean_barcode',
                                        'placeholder' =>'Enter EAN/ Barcode','id' => 'ean_barcode-input',
                                        'list' =>'eanbarcodes'])); ?>

                                        <datalist id="eanbarcodes"></datalist>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mrp', 'MRP')); ?>

                                        <?php echo e(Form::number('mrp', null, ['class' => 'form-control', 'placeholder' => 'Enter MRP'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('gst_id', 'GST (%)', ['class' => ''])); ?>

                                        <?php echo e(Form::select('gst_id', $gst, null, ['class' => 'form-control', 'placeholder'
                                        => 'Please Select GST'])); ?>

                                    </div>
                                </div>


                                <div class="col-md-6 col-6">
                                    <?php echo e(Form::label('visibility', 'Active / In-Active')); ?>

                                    <fieldset class="">
                                        <div class="radio radio-success">
                                            <?php echo e(Form::radio('visibility', '1', true, ['id' => 'radioshow'])); ?>

                                            <?php echo e(Form::label('radioshow', 'Yes')); ?>

                                        </div>
                                        <!-- </fieldset>
                                                                                                                        <fieldset> -->
                                        <div class="radio radio-danger">
                                            <?php echo e(Form::radio('visibility', '0', false, ['id' => 'radiohide'])); ?>

                                            <?php echo e(Form::label('radiohide', 'No')); ?>

                                        </div>
                                    </fieldset>
                                </div>






                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <?php echo e(Form::label('product_thumb', 'Product Thumbnail')); ?>

                                        <div class="custom-file">
                                            <?php echo e(Form::file('product_thumb', ['class' => 'custom-file-input', 'id' =>
                                            'product_thumb'])); ?>

                                            <label class="custom-file-label" for="product_thumb">Choose file</label>
                                        </div>
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
<!-- brands modal -->
<div class="modal fade text-left" id="add_brand_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.brands._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_variant_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Variant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.variant._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="add_combi_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Combi Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.products.combi_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>




<!-- uoms modal -->
<div class="modal fade text-left" id="add_uom_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.uoms._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<!-- hsncodes modal -->
<div class="modal fade text-left" id="add_hsncode_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add HSN</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.hsncodes._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="add_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.categories._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="add_sub_category_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Add Format</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $__env->make('backend.subcategories._form', compact('categories'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('public/backend-assets/js/MasterHandler.js')); ?>"></script>



<script>
    $(document).ready(function(){
        $('#hsncode-input').on('input', function(){
            var query = $(this).val();

            if(query.length >= 3){
                $.ajax({
                    url: '<?php echo e(route('admin.getHsnCodes')); ?>',
                    method: 'GET',
                    data: {query: query},
                    success: function(response){
                        $('#hsncodes').empty(); // Clear previous options

                        // Populate datalist with matching HSN codes
                        $.each(response, function(index, value){
                            $('#hsncodes').append('<option value="' + value + '">');
                        });
                    }
                });
            }
        });

        $('#ean_barcode-input').on('input', function(){
            var query = $(this).val();
            if(query.length >= 3){
                $.ajax({
                    url: '<?php echo e(route('admin.getEanBarCodes')); ?>',
                    method: 'GET',
                    data: {query: query},
                    success: function(response){
                        $('#eanbarcodes').empty(); // Clear previous options

                        // Populate datalist with matching HSN codes
                        $.each(response, function(index, value){
                            $('#eanbarcodes').append('<option value="' + value + '">');
                        });
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {


            new MasterHandler('#brand_id', '#add_brand_modal', '#submit_brand',
                '<?php echo e(url('admin/master/store_brand')); ?>', '', '#brand_name');

            new MasterHandler('#product_category_id', '#add_category_modal', '#submit_category',
                '<?php echo e(url('admin/master/store_product_category')); ?>', '', '#category_name',
                'input[name=visibility]:checked');

            new MasterHandler('#sub_category_id', '#add_sub_category_modal', '#submit_sub_category',
                '<?php echo e(url('admin/master/store_product_sub_category')); ?>', '', '#subcategory_name',
                'input[name=visibility]:checked');

            new MasterHandler('#uom_id', '#add_uom_modal', '#submit_uom',
                '<?php echo e(url('admin/uoms/store')); ?>', '', '#uom_name',
                '#uom_desc');

            new MasterHandler('#hsncode_id', '#add_hsncode_modal', '#submit_hsncode',
                '<?php echo e(url('admin/master/store_hsn')); ?>', '', '#hsncode_name',
                '#hsncode_desc');


            new MasterHandler('#variant', '#add_variant_modal', '#submit_variant',
                '<?php echo e(url('admin/master/store_variant')); ?>', '', '#name');

            new MasterHandler('#combi_type', '#add_combi_modal', '#submit_combi',
                '<?php echo e(url('admin/master/store_combitype')); ?>', '', '#name');



        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/products/create.blade.php ENDPATH**/ ?>