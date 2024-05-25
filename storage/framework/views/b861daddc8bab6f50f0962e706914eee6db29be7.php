
<?php $__env->startSection('title', 'Purchase Report'); ?>

<?php $__env->startSection('content'); ?>
<?php
use App\Models\backend\Company;
?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Purchase Report</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Purchase Report</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">

            </div>
        </div>
    </div>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Purchase Report</h4>
                </div>


                <form action="<?php echo e(route('admin.purchase')); ?>" method="get">

                    <div class="row ml-2 ">
                        <?php if(is_superAdmin()): ?>
                        <div class="col-md-3 col-sm-12 ">
                            <div class="form-group">
                                <?php echo e(Form::label('company_id', 'Distributor *')); ?>

                                <?php echo e(Form::select('company_id', Company::pluck('name','company_id'), $_GET['company_id']??null, [
                                'class' => 'form-control ',
                                'placeholder' => 'Select Company',
                                ])); ?>

                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-3 col-sm-12 ">
                            <div class="form-group">
                                <label for="date_range">Filter By Dates: </label>

                                <input type="text" class="form-control" name="daterange" value="" />
                                <input type="hidden" name="from_date" value="" />
                                <input type="hidden" name="to_date" value="" />
                            </div>
                        </div>

                        <div class="" style="height:30px;margin:auto 0px;">
                            <button class="btn-sm btn-secondary reset">Reset</button>
                        </div>
                    </div>


                </form>


                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>From</th>
                                        <th>Bill Doc</th>
                                        
                                        <th>Sold-to Party</th>
                                        <th>Region</th>
                                        <th>Material</th>
                                        <th>Material Description</th>
                                        <th>Unit/Case</th>
                                        <th>Qty(Case)</th>
                                        <th>Qty(Units)</th>
                                        <th>Billing Date</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Format</th>
                                        <th>Variant</th>
                                        <th>Source</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($data) && count($data) > 0): ?>
                                    <?php $srno = 1; ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    // dd( $row->get_goodservice_receipt->get_partyname);
                                    ?>
                                    <tr>
                                        <td><?php echo e($srno); ?></td>
                                        <td><?php echo e($row->created_at); ?></td>
                                        <td><?php echo e($row->created_at->format('M')); ?></td>
                                        <td><?php echo e($row->created_at->format('Y')); ?></td>
                                        <td><?php echo e($row->get_goodservice_receipt->get_partyname->bp_name??''); ?></td>
                                        <td><?php echo e($row->get_goodservice_receipt->bill_no); ?></td>
                                        <td><?php echo e($row->get_goodservice_receipt->get_company->name??''); ?></td>
                                        <td><?php echo e($row->get_goodservice_receipt->get_company->district??''); ?></td>
                                        <td><?php echo e($row->item_code); ?></td>
                                        <td><?php echo e($row->get_product->consumer_desc??''); ?></td>
                                        <td><?php echo e($row->get_product->unit_case??''); ?></td>
                                        <td><?php echo e($row->qty); ?></td>
                                        <td><?php echo e(isset($row->get_product->unit_case)? ((int) $row->get_product->unit_case
                                            * (int) $row->qty) :''); ?></td>
                                        <td><?php echo e($row->created_at->format('D-M-Y')); ?></td>
                                        <td><?php echo e($row->get_product->brand->brand_name??''); ?></td>
                                        <td><?php echo e($row->get_product->category->category_name??''); ?></td>
                                        <td><?php echo e($row->get_product->sub_category->subcategory_name??''); ?></td>
                                        <td><?php echo e($row->get_product->variants->name??''); ?></td>
                                        <td><?php echo e($row->get_product->sourcing); ?></td>


                                    </tr>
                                    <?php $srno++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php echo $__env->make('backend.export_pagination_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    $(document).ready(function() {            

        const from_date_param = new URLSearchParams(window.location.search).get('from_date');
        const to_date_param = new URLSearchParams(window.location.search).get('to_date');

        // Initialize date range picker
        $('input[name="daterange"]').daterangepicker({
            startDate: from_date_param ? moment(from_date_param, 'YYYY-MM-DD') : moment().subtract(1,
                'M'),
            endDate: to_date_param ? moment(to_date_param, 'YYYY-MM-DD') : moment(),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        // Handle form submission when date range is selected
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            // Update the hidden input fields with the selected date range
            $('input[name="from_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            $('input[name="to_date"]').val(picker.endDate.format('YYYY-MM-DD'));

            // Submit the form
            $('form').submit();
        });
        
        $('#company_id').on('change', function() {
            if($(this).val()){
                $('form').submit();
            }
        });

        // Handle cancel button click
        $(".cancelBtn").click(function() {
            window.location.reload();
        });


        $('.reset').on('click', function() {
            // Clear the date range
            $('#company_id').val('');
            $('input[name="daterange"]').val('');

            // Optionally trigger the change event to apply the changes
            $('input[name="daterange"]').trigger('change');
        });
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/reports/purchase.blade.php ENDPATH**/ ?>