<?php
use App\Models\backend\BusinessPartnerCategory;
use App\Models\backend\Company;
$categories = BusinessPartnerCategory::pluck('business_partner_category_name');
?>

<?php $__env->startSection('title', 'Business Master'); ?>

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
        <h3 class="content-header-title">Business Master</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Business Master</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Business Master')): ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('admin.bussinesspartner.create')); ?>">
                    <i class="feather icon-plus"></i> Add
                </a>
                <?php endif; ?>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.outlet')); ?>">
                    <i class="feather icon-trending-down"></i> Pending Requests
                </a>
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
                <?php echo e(Form::open(['url' => 'admin/updateBpmaster/update', 'class' => 'w-100', 'enctype' =>
                'multipart/form-data'])); ?>

                <div class="form-group ">
                    <select name="import_type" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="cus">Customer</option>
                        <option value="ven">Vendor</option>
                    </select>
                </div>

                <div class="form-group ">
                    <label for="company_id">Select Distributor *</label>
                    <?php echo e(Form::select('company_id',Company::pluck('name','company_id'),null, ['class' => 'form-control',
                    'placeholder' => 'Select Distributor', 'required' => true])); ?>

                </div>

                <div class="form-group mb-3">
                    <?php echo e(Form::label('file', 'Select File')); ?>

                    <input type="file" name="file" class="form-control">
                </div>
                <div class="d-flex mb-1">
                    <a href="<?php echo e(asset('public/sheets/sample-customer.xlsx')); ?>"
                        class="btn btn-sm btn-outline-primary mr-1">Sample Sheet
                        (Custmer)</a>
                    <a href="<?php echo e(asset('public/sheets/sample-vendor.xlsx')); ?>"
                        class="btn btn-sm btn-outline-primary">Sample Sheet
                        (Vendor)</a>
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
                    <h4 class="card-title">Business Partners</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        
                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        
                                        <th>Business Partner Type</th>
                                        
                                        <th>Partner Name</th>
                                        <th>Organization Type</th>
                                        <th>Category</th>
                                        <th>Channel</th>
                                        <th>Group</th>
                                        <th>Salesman</th>
                                        <th>Beat</th>
                                        <th>Payment Terms</th>
                                        <th>credit Limit</th>
                                        <th>Gst details</th>
                                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <thead id="search_input">
                                    <tr>
                                        <th></th>
                                        
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="bussines_partner_type"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $bussinesstype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th><input type="text" id="partner_name"></th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="organization_type"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $bpOrgType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="bp_category"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $bpCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="category"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="group"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $bpGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>

                                        <th><input type="text" id="salesman"></th>
                                        <th><input type="text" id="beat_id"></th>

                                        <th>
                                            <div class="dropdown">
                                                <select name="" id="payment_term"
                                                    class="btn btn-sm border border-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $termPayment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category); ?>"><?php echo e($category); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </th>
                                        <th><input type="text" id="credit_limit"></th>
                                        <th><input type="text" id="gst_details"></th>
                                        <th></th>
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
    var allBussinessPartnerData = <?php echo json_encode($allBussinessPartnerDataArray); ?>;
    
        $(function() {

            var table = $('#tbl-datatable').DataTable({



                processing: true,
                serverSide: false,
                ajax: "<?php echo e(route('admin.bussinesspartner')); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'get_partner_type_name.bussiness_master_type',
                        name: 'get_partner_type_name.bussiness_master_type'
                    },
                    {
                        data: 'bp_name',
                        name: 'bp_name'
                    },
                    {
                        data: 'bp_organisation_type',
                        name: 'bp_organisation_type'
                    },
                    {
                        data: 'bp_category',
                        name: 'bp_category'
                    },
                    {
                        data: 'bp_channel',
                        name: 'bp_channel'
                    },
                    {
                        data: 'bp_group',
                        name: 'bp_group'
                    },
                    {
                        data: 'salesman',
                        name: 'salesman'
                    },
                    {
                        data: 'beat_id',
                        name: 'beat_id'
                    },
                    {
                        data: 'term_type',
                        name: 'term_type'
                    },
                    {
                        data: 'credit_limit',
                        name: 'credit_limit'
                    },
                    {
                        data: 'gst_details',
                        name: 'gst_details'
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
                                var csv = Papa.unparse(allBussinessPartnerData);
                                // Create a Blob containing the CSV data
                                var blob = new Blob([csv], {
                                    type: 'text/csv;charset=utf-8;'
                                });
                                // Trigger a download of the Blob
                                saveAs(blob, 'BUSINESS_PARTNER.csv');
                            },
                            title: function() {
                                var pageTitle = 'BUSSINESS PARTNER';
                                return pageTitle
                            }
                        },

                    ]
                }],
                dom: 'lBfrtip',
                select: true,
                rowCallback: function(row, data, index) {
                    // Example condition: If credit limit is greater than 10000, make the row background green
                    if (data.is_converted == 0) {
                        $(row).css('background-color', '#f7f7ac');
                        $()
                    } else {
                        $(row).css('background-color', ''); // Reset background color
                    }
                }
            });

            function applySearch(columnIndex, value) {
                table.column(columnIndex).search(value).draw();
            }

            $('#bussines_partner_type, #category, #organization_type,#bp_category,#group, #payment_term').on('change', function() {
                var columnIndex = $(this).closest('th').index();
                var filterValue = $(this).find(':selected').val();
                applySearch(columnIndex, filterValue);
            });

            $('#partner_name,#gst_details, #credit_limit,#beat_id,#salesman').on('keyup', function() {
                var columnIndex = $(this).closest('th').index();
                applySearch(columnIndex, this.value);
            });

        });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/bussinesspartner/index.blade.php ENDPATH**/ ?>