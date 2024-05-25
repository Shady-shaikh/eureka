<?php
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\Company;
$categories = BusinessPartnerCategory::pluck('business_partner_category_name');
?>

<?php $__env->startSection('title', 'Claims'); ?>

<style>
    tr td {
        background-color: #ffffff;
    }

    tr.odd td {
        background-color: transparent;
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
        <h3 class="content-header-title">Claims</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Claims</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Claims')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.claims.create')); ?>">
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
                    <h4 class="card-title">Claims</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Document Date</th>
                                        <th>Document Number</th>
                                        <th>Customer/ Vendor Name</th>
                                        <th>Status</th>
                                        <th>Expense type</th>
                                        <th>Claim Type</th>
                                        <th class="no_export">Action</th>
                                    </tr>
                                </thead>
                                <thead id="search_input">
                                    <tr>
                                        <th></th>
                                        <th><input type="date" id="doc_date"></th>
                                        <th><input type="text" id="doc_no"></th>
                                        <th><input type="text" id="party_id"></th>
                                        <th><input type="text" id="status"></th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="expense_type"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $expense_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="claim_type"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $claim_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                       

                                        <th class="no_export"></th>
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
<?php $__env->startSection('scripts'); ?>

<script>
    var allClaimsData = <?php echo json_encode($allClaimsData); ?>;

    $(function() {

            var table = $('#tbl-datatable').DataTable({



                processing: true,
                serverSide: false,
                ajax: "<?php echo e(route('admin.claims')); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'doc_date',
                        name: 'doc_date'
                    },
                    
                    {
                        data: 'doc_no',
                        name: 'doc_no'
                    },
                
                    {
                        data: 'party_id',
                        name: 'party_id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'expense_type',
                        name: 'expense_type'
                    },
                    {
                        data: 'claim_type',
                        name: 'claim_type'
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
                            action: function(e, dt, button, config) {
                                // Convert the array to a CSV string
                                // allBussinessPartnerData = Json.parse(allBussinessPartnerData);
                                var csv = Papa.unparse(allClaimsData);
                                // Create a Blob containing the CSV data
                                var blob = new Blob([csv], {
                                    type: 'text/csv;charset=utf-8;'
                                });
                                // Trigger a download of the Blob
                                saveAs(blob, 'Claims.csv');
                            },
                            title: function() {
                                var pageTitle = 'Claims';
                                return pageTitle
                            }
                        },

                    ]
                }],
                dom: 'lBfrtip',
                select: true,
                paging: true,
                pageLength:10,
                
            });

            function applySearch(columnIndex, value) {
                table.column(columnIndex).search(value).draw();
            }

            $('#expense_type,#claim_type').on('change', function() {
                var columnIndex = $(this).closest('th').index();
                var filterValue = $(this).find(':selected').val();
                applySearch(columnIndex, filterValue);
            });

            
            $('#doc_date', this).on('keyup change', function() {
                var columnIndex = $(this).closest('th').index();
                var val = $(this).val();
                var date = new Date(val);
                var newDate = date.toString('dd-MM-yy');
                table.column(columnIndex).search(this.value).draw();

            });

            $('#party_id,#doc_no,#status').on('keyup', function() {
                var columnIndex = $(this).closest('th').index();
                applySearch(columnIndex, this.value);
            });

        });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/claims/index.blade.php ENDPATH**/ ?>