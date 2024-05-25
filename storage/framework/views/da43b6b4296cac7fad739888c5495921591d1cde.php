
<?php $__env->startSection('title', 'Incentives'); ?>

<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Incentives</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Incentives</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Beat Calender Master')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.incentives.create')); ?>">
                    <i class="feather icon-plus"></i> Add
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Incentives</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Brand</th>
                                        <th>Format</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Month</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <?php echo Form::text('brand_id', null, [
                                                'id' => 'brand_id',
                                                ]); ?>

                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <?php echo Form::text('format_id', null, [
                                                'id' => 'format_id',
                                                ]); ?>

                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <?php echo Form::text('product_id', null, [
                                                'id' => 'product_id',
                                                ]); ?>

                                            </div>
                                        </th>

                                        <th>
                                            <div class="my-1">
                                                <?php echo Form::text('amount', null, [
                                                'id' => 'amount',
                                                ]); ?>

                                            </div>
                                        </th>

                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    <?php echo Form::select('month', $all_data['month'], null, [
                                                    'id' => 'month',
                                                    'placeholder' => 'Select Month',
                                                    ]); ?>

                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
                                        </th>

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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>


<script>
    $(function() {
            var monthsOrder = <?php echo json_encode(array_values($all_data['month']), 15, 512) ?>;

            $('.dropdown-toggle').dropdown();

            var table = $('#tbl-datatable').DataTable({
                orderable: true,
                searchable: true,
                ajax: "<?php echo e(route('admin.incentives')); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'brand_id',
                        name: 'brand_id'
                    },
                    {
                        data: 'format_id',
                        name: 'format_id'
                    },
                    {
                        data: 'product_id',
                        name: 'product_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: [0,1, 2, 3, 4,5],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'Incentives';
                                return pageTitle
                            }
                        },
                       

                    ]
                }],
                dom: 'lBfrtip',
                select: true,
                columnDefs: [
                    {
                        targets: 4, // Column index for "Month"
                        type: 'months-order' // Custom sorting type for months
                    }
                ]
            });

            // Custom sorting functions for days and months
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                
                'months-order-pre': function(a) {
                    // Define the sorting order for months
                    return monthsOrder.indexOf(a);
                },
                'months-order-asc': function(a, b) {
                    return a - b;
                },
                'months-order-desc': function(a, b) {
                    return b - a;
                }
            });

            function applySearch(columnIndex, value) {
                table.column(columnIndex).search(value).draw();
            }

            $('#brand_id,#format_id,#product_id,#amount').on('keyup', function() {
                var columnIndex = $(this).closest('th').index();
                applySearch(columnIndex, this.value);
            });


            $('#month').on('change', function() {
                var columnIndex = $(this).closest('th').index(); // Get the column index of the changed dropdown
                var filterValue = $(this).find(':selected').text();
                table.column(columnIndex).search(filterValue).draw();
            });
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/incentives/index.blade.php ENDPATH**/ ?>