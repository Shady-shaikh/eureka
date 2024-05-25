
<?php $__env->startSection('title', 'Set Margin Scheme'); ?>


<?php $__env->startSection('content'); ?>

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Set Margin Scheme</li>
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
                <div class="card-content">
                    <div class="card-body">


                        <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo e(Form::open(['url' => 'admin/subdmargin/update', 'class' => 'w-100', 'enctype' =>
                        'multipart/form-data'])); ?>



                        <div class="row">





                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php echo e(Form::label('file', 'Import Data From File')); ?>

                                    <input type="file" name="file" class="form-control">

                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <br style="margin-top:5px;">
                                    <?php echo e(Form::submit('Import', ['class' => 'btn btn-primary '])); ?>


                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                            </div>

                        </div>

                        <?php echo e(Form::close()); ?>

                        <hr>


                        <table class="table table-bordered table-striped" id="tbl-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Location</th>
                                    <th>Margin</th>

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
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('admin.subdmargin.form')); ?>", // Replace with your server-side endpoint
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'customer_code',
                    name: 'customer_code'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'margin',
                    name: 'margin'
                },
                
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3,4],
                    modifier: {
                        page: 'all',
                        search: 'applied'
                    }
                },
                title: 'Sub-D Margin Data',
                customize: function (xlsx) {
                   
                }

            }],
            dom: 'lBfrtip',
            select: true,
            lengthMenu: [
                [-1],
                ['All']
            ],
    });

    function get_data(){
        $.get('<?php echo e(route('admin.subdmargin.fetch_subdmargin')); ?>', {
            }, function(data) {
                var jsonData = JSON.parse(data);
                if (jsonData.subdmargin_data.length > 0) {
                    // Loop through the received data and update margin text
                    jsonData.subdmargin_data.forEach(function(item) {
                        setTimeout(() => {
                            // Find the corresponding row with matching brand and sub-category IDs
                            var row = $('#tbl-datatable tbody').find('tr').filter(function() {
                                return $(this).find('td:eq(1)').text().trim() == item.customer_code;
                            });
                            // Update the margin text in the corresponding cell
                            row.find('td:eq(4)').text(item.margin);
                        }, 500);

                    });
                    
                }else{
                    //reset all text of margins
                    $('#tbl-datatable tbody td:nth-child(5)').text(0);
                }
        });
    }

    get_data();

            
   
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/marginscheme/subdmargin.blade.php ENDPATH**/ ?>