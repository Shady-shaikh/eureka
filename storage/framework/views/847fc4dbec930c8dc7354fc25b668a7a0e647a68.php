
<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Products</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Products')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.products.create')); ?>">
                    <i class="feather icon-plus"></i> Add
                </a>
                <?php endif; ?>
                <?php if(is_superAdmin()): ?>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#importModal">
                    <i class="feather icon-download"></i>
                    Import
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for importing data -->
                <?php echo e(Form::open(['url' => 'admin/updateProduct/update', 'class' => 'w-100', 'enctype' =>
                'multipart/form-data'])); ?>



                <div class="form-group mb-3">
                    <?php echo e(Form::label('file', 'Select File')); ?>

                    <input type="file" name="file" class="form-control">
                </div>
                <div class="d-flex mb-1">
                    <a href="<?php echo e(asset('public/sheets/sample-product.xlsx')); ?>"
                        class="btn btn-sm btn-outline-primary mr-1">Sample Sheet
                        (Product)</a>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>
</div>

<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Products</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="no_export">Image</th>
                                        <th>Type</th>
                                        <!-- <th>Item Type</th> -->
                                        <th>Base pack</th>
                                        <th>Item Code</th>
                                        <th>Consumer Description</th>
                                        <th>Product Description</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Format</th>
                                        <th>Variant</th>
                                        <th>BUoM/ Pack Size</th>
                                        <th>UoM</th>
                                        <th>Pack/Case</th>
                                        <th>HSN Code</th>
                                        <th>Shelf Life</th>
                                        <th>Shelf Life UoM</th>
                                        <th>Sourcing Unit</th>
                                        <th>Case/ Pallet</th>
                                        <th>Layer/ Pallet</th>
                                        <th>Pack/Unit</th>
                                        <th>Case Length (mm)</th>
                                        <th>Case Width (mm)</th>
                                        <th>Case Height (mm)</th>
                                        <th>Sides UoM</th>
                                        <th>Net Weight</th>
                                        <th>Gross Weight</th>
                                        <th>Wt. UoM</th>
                                        <th>EAN/ Barcode</th>
                                        <th>MRP</th>
                                        <th>GST (%)</th>
                                        <th>Show / Hide</th>
                                        <th>Combi Type</th>
                                        <th>Combi Type Int</th>



                                        <th class="no_export">Action</th>
                                    </tr>
                                </thead>
                                <thead id="search_input">
                                    <tr>
                                        <th></th>
                                        <th class="no_export"></th>
                                        <th><input type="text" id="item_type_id"></th>
                                        <th><input type="text" id="sku"></th>
                                        <th><input type="text" id="item_code"></th>
                                        <th><input type="text" id="consumer_desc"></th>
                                        <th><input type="text" id="product_desc"></th>
                                        <th><input type="text" id="brand_id"></th>
                                        <th><input type="text" id="category_id"></th>
                                        <th><input type="text" id="sub_category_id"></th>
                                        <th><input type="text" id="variant"></th>
                                        <th><input type="text" id="buom_pack_size"></th>
                                        <th><input type="text" id="uom_id"></th>
                                        <th><input type="text" id="unit_case"></th>
                                        <th><input type="text" id="hsncode_id"></th>
                                        <th><input type="text" id="shelf_life_number"></th>
                                        <th><input type="text" id="shelf_life"></th>
                                        <th><input type="text" id="sourcing"></th>
                                        <th><input type="text" id="case_pallet"></th>
                                        <th><input type="text" id="layer_pallet"></th>
                                        <th><input type="text" id="dimensions_unit_pack"></th>
                                        <th><input type="text" id="dimensions_length"></th>
                                        <th><input type="text" id="dimensions_width"></th>
                                        <th><input type="text" id="dimensions_height"></th>
                                        <th><input type="text" id="dimensions_length_uom_id"></th>
                                        <th><input type="text" id="dimensions_net_weight"></th>
                                        <th><input type="text" id="dimensions_gross_weight"></th>
                                        <th><input type="text" id="dimensions_net_uom_id"></th>
                                        <th><input type="text" id="ean_barcode"></th>
                                        <th><input type="text" id="mrp"></th>
                                        <th><input type="text" id="gst_id"></th>
                                        <th><input type="text" id="visibility"></th>
                                        <th><input type="text" id="combi_type"></th>
                                        <th><input type="text" id="combi_type_int"></th>


                                        <th class="no_export"></th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->startSection('scripts'); ?>

<script>
    $(function() {
            var table = $('#tbl-datatable').DataTable({

                processing: true,
                serverSide: false,
                ajax: "<?php echo e(route('admin.products')); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'product_thumb',
                        name: 'product_thumb'
                    },
                    
                    {
                        data: 'item_type_id',
                        name: 'item_type_id'
                    },
                    
                    {
                        data: 'sku',
                        name: 'sku'
                    },
                    
                    {
                        data: 'item_code',
                        name: 'item_code'
                    },
                    {
                        data: 'consumer_desc',
                        name: 'consumer_desc'
                    },
                    {
                        data: 'product_desc',
                        name: 'product_desc'
                    },

                    {
                        data: 'brand_id',
                        name: 'brand_id'
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
                    },
                    {
                        data: 'sub_category_id',
                        name: 'sub_category_id'
                    },
                    {
                        data: 'variant',
                        name: 'variant'
                    },
                    {
                        data: 'buom_pack_size',
                        name: 'buom_pack_size'
                    },
                    {
                        data: 'uom_id',
                        name: 'uom_id'
                    },
                    {
                        data: 'unit_case',
                        name: 'unit_case'
                    },
                    {
                        data: 'hsncode_id',
                        name: 'hsncode_id'
                    },
                    {
                        data: 'shelf_life_number',
                        name: 'shelf_life_number'
                    },
                    {
                        data: 'shelf_life',
                        name: 'shelf_life'
                    },
                    {
                        data: 'sourcing',
                        name: 'sourcing'
                    },
                    {
                        data: 'case_pallet',
                        name: 'case_pallet'
                    },
                    {
                        data: 'layer_pallet',
                        name: 'layer_pallet'
                    },
                    {
                        data: 'dimensions_unit_pack',
                        name: 'dimensions_unit_pack'
                    },
                    {
                        data: 'dimensions_length',
                        name: 'dimensions_length'
                    },
                    {
                        data: 'dimensions_width',
                        name: 'dimensions_width'
                    },
                    {
                        data: 'dimensions_height',
                        name: 'dimensions_height'
                    },
                    {
                        data: 'dimensions_length_uom_id',
                        name: 'dimensions_length_uom_id'
                    },
                    {
                        data: 'dimensions_net_weight',
                        name: 'dimensions_net_weight'
                    },
                    {
                        data: 'dimensions_gross_weight',
                        name: 'dimensions_gross_weight'
                    },
                    {
                        data: 'dimensions_net_uom_id',
                        name: 'dimensions_net_uom_id'
                    },
                    {
                        data: 'ean_barcode',
                        name: 'ean_barcode'
                    },
                    {
                        data: 'mrp',
                        name: 'mrp'
                    },
                    {
                        data: 'gst_id',
                        name: 'gst_id'
                    },
                    {
                        data: 'visibility',
                        name: 'visibility'
                    },
                    {
                        data: 'combi_type',
                        name: 'combi_type'
                    },
                    {
                        data: 'combi_type_int',
                        name: 'combi_type_int'
                    },

                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: false
                    }
                ],

                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            columns: ':visible:not(.no_export)'
                        },
                        autoSize: true // Automatically adjust column width
                    }
                ],
                dom: 'lBfrtip',
                select: true
            });

            function applySearch(columnIndex, value) {
                table.column(columnIndex).search(value).draw();
            }

            $('#item_type_id,#sku,#item_code,#consumer_desc,#product_desc,#brand_id,#category_id,#sub_category_id,#variant,#buom_pack_size,#uom_id,#unit_case,#hsncode_id,#shelf_life_number,#shelf_life,#sourcing,#case_pallet,#layer_pallet,#dimensions_unit_pack,#dimensions_length,#dimensions_width,#dimensions_height,#dimensions_length_uom_id,#dimensions_net_weight,#dimensions_gross_weight,#dimensions_net_uom_id,#ean_barcode,#mrp,#gst_id,#visibility,#combi_type,#combi_type_int').on('keyup', function() {
                var columnIndex = $(this).closest('th').index();
                applySearch(columnIndex, this.value);
            });

        });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/products/index.blade.php ENDPATH**/ ?>