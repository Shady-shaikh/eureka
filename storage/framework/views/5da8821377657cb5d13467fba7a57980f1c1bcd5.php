
<?php $__env->startSection('title', 'MRP'); ?>


<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">MRP</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card">
                <h4 class="px-2 py-1">MRP</h4>

                <div class="card-content">
                    <div class="card-body">

                        <table class="table table-bordered table-striped" id="tbl-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Bar Code</th>
                                    <th>SKU Code</th>
                                    <th>Description</th>
                                    <th>Brand</th>
                                    <th>Format</th>
                                    <th>Variant</th>
                                    <th>PO</th>
                                    <th>MRP</th>
                                </tr>
                            </thead>
                            <tbody>



                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>




<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('public/backend-assets/js/DynamicDropdown.js')); ?>"></script>


<script>
    var table = $('#tbl-datatable').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: "<?php echo e(route('admin.mrp')); ?>", // Replace with your server-side endpoint
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'ean_barcode',
                    name: 'ean_barcode'
                },
                {
                    data: 'sku',
                    name: 'sku'
                },
                {
                    data: 'consumer_desc',
                    name: 'consumer_desc',
                },
                {
                    data: 'brand_id',
                    name: 'brand_id',
                },
                {
                    data: 'sub_category_id',
                    name: 'sub_category_id',
                },
                {
                    data: 'variant',
                    name: 'variant',
                },
                {
                    data: 'combi_type',
                    name: 'combi_type',
                },
                {
                    data: 'mrp',
                    name: 'mrp',
                },
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6,7,8],
                    modifier: {
                        page: 'all',
                        search: 'applied'
                    }
                },
                title: 'MRP Data',

            }],
            dom: 'lBfrtip',
            select: true,
            lengthMenu: [
                [-1],
                ['All']
            ],
            paging: true, // Enable pagination
            pageLength: 10  
    });

   
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/marginscheme/mrp.blade.php ENDPATH**/ ?>