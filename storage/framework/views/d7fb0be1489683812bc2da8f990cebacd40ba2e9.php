
<?php $__env->startSection('title', 'Purchase Order'); ?>


<style>
    tr td {
        background-color: #ffffff;
    }

    tr.odd td {
        background-color: #f9f9f9;
    }

    thead th {
        background-color: white;
    }

    #tbl-datatable {
        font-size: 12px;
    }

    .headings {
        font-size: 13px !important;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Purchase Order</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase Order</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Purchase Order')): ?>
                        <a href="<?php echo e(url('admin/purchaseorder/create')); ?>" class="btn  btn-outline-primary"><i
                                class="feather icon-plus"></i> Add</a>
                    <?php endif; ?>

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
                            <div class="row">
                            </div>
                            <div class="table-responsive">
                                <table class="table zero-configuration " id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Purchase Order No</th>
                                            <th>Vendor PO Refrence Number</th>
                                            <th>Purchase Order To</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <div class="my-1">
                                                    <input type="date" id="date">
                                                </div>
                                            </th>
                                            
                                            <th>
                                                <div class="my-1">
                                                    <input type="text" id="purchase_order_no">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="my-1">
                                                    <input type="text" id="vendor_po_ref_num">
                                                </div>
                                            </th>

                                            <th>
                                                <div class="my-1">
                                                    <input type="text" id="purchase_order_to">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="my-1">
                                                    <div class="dropdown">
                                                        <select name="" id="status"
                                                            class="btn btn-sm border border-dark dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <option value="">Select</option>
                                                            <option value="open">Open</option>
                                                            <option value="close">Close</option>
                                                        </select>
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

                                    <tbody>

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
<?php $__env->startSection('scripts'); ?>



    <script>
        $(function() {
            var table = $('#tbl-datatable').DataTable({


                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('admin.purchaseorder')); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'bill_date',
                        name: 'bill_date'
                    },

                    {
                        data: 'bill_no',
                        name: 'bill_no'
                    },
                    {
                        data: 'vendor_ref_no',
                        name: 'vendor_ref_no'
                    },

                    {
                        data: 'get_partyname.bp_name',
                        name: 'get_partyname.bp_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: false
                    }
                ],

                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: [0,1, 2, 3, 4, 5],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'Purchase Order';
                                return pageTitle
                            }
                        },
                        

                    ]
                }],
                dom: 'lBfrtip',
                select: true

            });



            $('#purchase_order_no').on('keyup', function() {
                table.column(2).search(this.value).draw();
            });

            $('#vendor_po_ref_num').on('keyup', function() {
                table.column(3).search(this.value).draw();
            });

            $('#date', this).on('keyup change', function() {
                var val = $(this).val();
                var date = new Date(val);
                var newDate = date.toString('dd-MM-yy');
                console.log(newDate);
                table.column(1).search(this.value).draw();

            });

            $('#purchase_order_to').on('keyup', function() {
                table.column(4).search(this.value).draw();
            });

            $('#status').on('change', function() {
                table.column(5).search(this.value).draw();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/purchaseorder/index.blade.php ENDPATH**/ ?>