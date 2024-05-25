
<?php $__env->startSection('title', 'Add Sales Return'); ?>

<?php $__env->startSection('content'); ?>
<?php
use App\Models\backend\Company;
use App\Models\backend\Products;
?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Add Sales Return</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Add Sales Return</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.returninvoice')); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">


                        <div class="content-header row">
                            <?php echo $__env->make('backend.includes.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo e(Form::model([], ['url' => 'admin/returninvoice/update', 'class' => 'w-100'])); ?>

                            <?php echo e(Form::hidden('bill_to_state', null, ['class' => 'bill_to_state'])); ?>

                            <?php echo e(Form::hidden('party_state', null, ['class' => 'party_state'])); ?>

                            <?php echo e(Form::hidden('bill_to_gst_no', null, ['class' => 'bill_to_gst_no'])); ?>

                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('bp_id', 'Business Partner *')); ?>

                                            <?php echo e(Form::select('bp_id', $bp_data, null, [
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Select Business Partner',
                                            // 'required' => true,
                                            ])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>

                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('doc_date', 'Document Date *')); ?>

                                            <?php echo e(Form::date('doc_date', date('Y-m-d'), [
                                            'class' => 'form-control',
                                            'required' => true,
                                            ])); ?>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('inv_no', 'Invoice *')); ?>

                                            <?php echo e(Form::select('inv_no',[], null, [
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Select Invoice',
                                            'required' => true,
                                            ])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>

                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('return_no', 'Credit Invoice Number *')); ?>

                                            <?php echo e(Form::text('return_no', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Invoice Number',
                                            'required' => true,
                                            ])); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-4 d-none">
                                        <div class="form-group">
                                            <?php echo e(Form::label('t_type', 'Transaction Type *')); ?>

                                            <?php echo e(Form::select('t_type', ['with_inv'=>'With Inventory'], null, [
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            ])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4 d-none"></div>

                                    <div class="col-md-4 col-sm-4 d-none doc_no">
                                        <div class="form-group">
                                            <?php echo e(Form::label('doc_no', 'Doc Numer *')); ?>

                                            <?php echo e(Form::text('doc_no', null, [
                                            'class' => 'form-control ',
                                            'readonly' => true,
                                            ])); ?>

                                        </div>
                                    </div>
                                </div>


                                <h5 class="text-center">Invoice Items</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <section id="form-repeater-wrapper">
                                            <!-- form default repeater -->
                                            <div class="row">
                                                <div class="col-12">


                                                    
                                                    <div class="conatiner-fluid table-responsive repeater">

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered " id="repeater"
                                                                style="width:100%;">
                                                                <thead class="bg-light" style="font-size:10px;">
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo e(Form::label('item_code', 'Item Code')); ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php echo e(Form::label('item_name', 'Item
                                                                            Description')); ?>

                                                                        </td>
                                                                        <td>
                                                                            <?php echo e(Form::label('hsn_sac', 'HSN/SAC')); ?>

                                                                        </td>
                                                                        
                                                                        </td>
                                                                        <td><?php echo e(Form::label('qty',
                                                                            'Quantity',['id'=>'qty_label'])); ?>

                                                                        </td>
                                                                        
                                                                        <td><?php echo e(Form::label('taxable_amount', 'Unit
                                                                            Price')); ?>

                                                                        </td>
                                                                        <td><?php echo e(Form::label('total', 'Total INR')); ?>

                                                                        </td>
                                                                        <td><?php echo e(Form::label('GST', 'GST (%)')); ?></td>
                                                                        <td><?php echo e(Form::label('CGST', 'CGST (%)')); ?></td>
                                                                        <td><?php echo e(Form::label('SGST', 'SGST (%)')); ?></td>
                                                                        <td><?php echo e(Form::label('IGST', 'IGST (%)')); ?></td>
                                                                        <td><?php echo e(Form::label('Amount', 'GST Amount')); ?>

                                                                        </td>
                                                                        <td><?php echo e(Form::label('gross_total', 'Gross Total')); ?>

                                                                        </td>
                                                                        <td><?php echo e(Form::label('storage_location_id',
                                                                            'Warehouse')); ?>

                                                                        </td>
                                                                        <td><?php echo e(Form::label('bacth_id', 'Batch Details')); ?>

                                                                        </td>
                                                                        <td></td>


                                                                    </tr>
                                                                </thead>
                                                                <tbody data-repeater-list="old_invoice_items">

                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>

                                        </section>

                                    </div>




                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                </div>
                                

                                <div class="row">


                                    <div class="col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <?php echo e(Form::label('remarks', 'Remarks')); ?>

                                            <?php echo e(Form::textarea('remarks', null, ['class' => 'form-control remarks',
                                            'placeholder' => 'Remarks', 'style' => 'height:100px;'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                    </div>

                                </div>

                                <div class="col-sm-12 d-flex justify-content-center">
                                    <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1', 'id' =>
                                    'custom_form'])); ?>

                                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                                </div>
                                <?php echo e(Form::close()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>

<?php echo $__env->make('backend.autocomplete_typeahead_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('public/backend-assets/js/DynamicDropdown.js')); ?>"></script>




<script>
    $(document).ready(function() {

            // usama_13-03-2024-fetch inv items
            $('#inv_no').change(function() {

                $.get(APP_URL + '/admin/returninvoice/inv_data/' + $(this).val(), {}, function(
                    response) {
                    var data = $.parseJSON(response);
                    var invoice_data = data.inv_data;
                    var invoice_items = data.inv_items;
                    $('tbody[data-repeater-list="old_invoice_items"]').empty();
                    $.each(invoice_items, function(index, item) {
                        // console.log(item.get_product);
                    var newRow = '<tr data-repeater-item class="item_row item-content" id="old_row_'+index+'">';
                    newRow += '<td><input type="text" name="old_invoice_items[' + index + '][item_code]" value="' + item.item_code + '" class="form-control item_code typeahead " data-name = "item_code"  readonly></td>';
                    newRow += '<td><input type="text" name="old_invoice_items[' + index + '][item_name]" value="' + item.item_name + '" class="form-control item_name typeahead " data-name = "item_name"  readonly></td>';
                    newRow += '<td><input type="text" name="old_invoice_items[' + index + '][hsn_sac]" value="' + item.hsn_sac + '" class="form-control "  readonly></td>';
                    // newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][uom]" value="' + (item.uom || 'units') + '" class="form-control uom_field"  onchange="calculategst(this)" , data-name = "uom", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="text" name="old_invoice_items[' + index + '][qty]" value="' + item.qty + '" class="form-control qty" oninput = "handleInput(this)" , data-name = "qty", data-group = "old_invoice_items" required></td>';
                    // newRow += '<td><input type="number" name="old_invoice_items[' + index + '][final_qty]" value="' + (item.final_qty || item.qty) + '" class="form-control final_qty" oninput = "validateInputZero(this)" onchange="calculategst(this)" , data-name = "final_qty", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][taxable_amount]" value="' + item.taxable_amount + '" class="form-control taxable_amount " onchange="calculategst(this)" , data-name = "taxable_amount", data-group = "old_invoice_items"  readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][total]" value="' + item.total + '" class="form-control total " onchange="calculategst(this)" , data-name = "total", data-group = "old_invoice_items"  readonly></td>';                    
                    // hidden fields
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][cgst_amount]" value="' + item.cgst_amount + '" class="form-control cgst_amount typeahead" onchange="calculategst(this)" , data-name = "cgst_amount", data-group = "old_invoice_items"  readonly></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][sgst_utgst_amount]" value="' + item.sgst_utgst_amount + '" class="form-control sgst_utgst_amount typeahead" onchange="calculategst(this)" , data-name = "sgst_utgst_amount", data-group = "old_invoice_items"  readonly></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][igst_amount]" value="' + item.igst_amount + '" class="form-control igst_amount typeahead" onchange="calculategst(this)" , data-name = "igst_amount", data-group = "old_invoice_items"  readonly></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][sku]" value="' + item.sku + '" class="form-control sku typeahead" , data-name = "sku", data-group = "old_invoice_items"  readonly></td>';
                    
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][unit_pack]" value="' + item.get_product.dimensions_unit_pack + '"  data-group = "old_invoice_items" ></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][pack_case]" value="' + item.get_product.unit_case + '"  data-group = "old_invoice_items" ></td>';

                    var gst_rate= item.gst_rate??''; 
                    var gstData = <?php echo $encoded_gst; ?>;
                    newRow += '<td><input type="text" name="gst" value="'+gstData[gst_rate]+'" class="form-control" readonly></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][gst_rate]" value="'+gst_rate+'" class="form-control gst_rate" onchange="calculategst(this)" , data-name = "gst_rate", data-group = "old_invoice_items" readonly></td>';
                    
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][cgst_rate]" value="' + item.cgst_rate + '" class="form-control cgst_rate " onchange="calculategst(this)" , data-name = "cgst_rate", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][sgst_utgst_rate]" value="' + item.sgst_utgst_rate + '" class="form-control sgst_utgst_rate " onchange="calculategst(this)" , data-name = "sgst_utgst_rate", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][igst_rate]" value="' + item.igst_rate + '" class="form-control igst_rate " onchange="calculategst(this)" , data-name = "igst_rate", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][gst_amount]" value="' + item.gst_amount + '" class="form-control gst_amount "  onchange="calculategst(this)" , data-name = "gst_amount", data-group = "old_invoice_items" readonly></td>';
                    newRow += '<td><input type="number" name="old_invoice_items[' + index + '][gross_total]" value="' + item.gross_total + '" class="form-control gross_total "  onchange="calculategst(this)" , data-name = "gross_total", data-group = "old_invoice_items" readonly></td>';
                    
                    var wh_id= item.storage_location_id??''; 
                    var whData = <?php echo $encoded_wh; ?>;
                    newRow += '<td><input type="text" name="storage" value="' + whData[wh_id] + '" class="form-control "  readonly></td>';
                    newRow += '<td class="d-none"><input type="hidden" name="old_invoice_items[' + index + '][storage_location_id]" value="' + wh_id + '" class="form-control storage_location_id "  readonly></td>';
                    
                    newRow += '<td><input type="text" name="old_invoice_items[' + index + '][batch_no]" value="' + item.batch_no + '" class="form-control batch_no "  readonly></td>';
                    // Add more columns as needed
                    newRow += `<td><button type='button' class='btn btn-danger btn-flat btn-xs old_rep_item_del' data-repeater-delete><i class='fa fa-fw fa-remove'></i></button> </td>`;

                    // Append the new row to the tbody
                    $('tbody[data-repeater-list="old_invoice_items"]').append(newRow);

                    // $('#bp_id').val(invoice_data.party_name);
                });
                
                });
            });
       

            let itemIndex;

            $('#custom_form').click(function(e) {
                let items = $('.item_row');
                let flag;

                for (let i = 0; i < items.length; i++) {
                    var batchNo = document.querySelector(`#old_invoice_items_${i}_batch_no`);
                    var manufacturing_date = document.querySelector(
                        `#old_invoice_items_${i}_manufacturing_date`);
                    var expiry_date = document.querySelector(`#old_invoice_items_${i}_expiry_date`);

                    if (batchNo || manufacturing_date || expiry_date) {
                        if (batchNo.value === '') {
                            flag = 'Batch Number';
                            itemIndex = i;
                            break;
                        } else if (manufacturing_date.value === '') {
                            flag = 'Manufacturing Date';
                            itemIndex = i;
                            break;
                        } else if (expiry_date.value === '') {
                            flag = 'Expiry Date';
                            itemIndex = i;
                            break;
                        }
                    }
                }
                ++itemIndex;
                if (flag) {
                    // alert(`${flag} required for item ${itemIndex}`);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: `${flag} required for item ${itemIndex}`,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

        });
</script>


<script>

    
    function handleInput(elem) {
        calculategst(elem);
        validateQty(elem);
    }

    function get_data_display(customer_id) {

            // alert(customer_id);

            $.get(APP_URL + '/admin/goodsservicereceipts/partydetails/' + customer_id, {}, function(
                response) {
                var customer_details = $.parseJSON(response);
                // console.log(customer_details);
                if (customer_details) {
                    $(".party").html(customer_details.party_detail);
                    $(".party_input").val(customer_details.party_detail);
                    $(".bill_to_state").val(customer_details.bill_to_state);
                    $(".party_state").val(customer_details.party_state);
                    $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);
                } else {
                    alert('nop res');
                    $(".pary").html("");
                    $(".party_input").val('');
                    $(".bill_to_state").val('');
                    $(".party_state").val('');
                }
            });

        }
        $(document).ready(function() {

            var customer_id = $('#bp_id option:selected').val();
            // alert(customer_id);
            if (customer_id) {
                get_data_display(customer_id);
            }
            $("#bp_id").on('change', function() {

                // usama_18-03-2024-fethc invoice details
                new DynamicDropdown('<?php echo e(route('admin.get_ar_invoices')); ?>',
                    $(this).val(), '#inv_no');

                 // usama_12-03-2024-fetch company of party and then make doc number
                $('.doc_no').removeClass('d-none');
                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '<?php echo e(csrf_token()); ?>',
                    },
                    url: '<?php echo e(route('admin.get_doc_number')); ?>',
                    data: {
                        id: '<?php echo e($series_no); ?>',
                                party_id: $(this).val(),
                    },
                    // dataType: 'json',
                    success: function(data) {
                        var matches = data.match(/(\d+)$/);
                        var currentNumber = matches ? parseInt(matches[1], 10) : 0;
                        var newNumber = currentNumber + 1;
                        var newDocNumber = data.replace(/\d+$/, newNumber);
                        $('#doc_no').val(newDocNumber);

                    }
                });

                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
            });

            $(document).on('click', '.batch-details-button', function(e) {
                $(this).closest('.item_row').find('.modal').modal('show');
            });


        });
</script>



<script>
    var generateId = function(string) {
            return string
                .replace(/\[/g, '_')
                .replace(/\]/g, '')
                .toLowerCase();
        };

        function getFirstNumber(string) {
            // alert(string);
            const regex = /[1-9]/; // Regular expression to match any number from 1 to 9
            const match = string.match(regex);

            if (match) {
                return match[0];
            }

            return null; // If no match found
        }


        function get_invoice_itemnames() {
            setTimeout(() => {
                var itemContent = $('.modal');
                // var group = itemContent.data("group");

                var item = itemContent;
                var input = item.find('.modal_items');

                // console.log(input);
                input.each(function(index, el) {
                    // console.log(el.name);
                    var attrName = $(el).attr('name');
                    var dataName = $(el).data('name');
                    // alert(attrName);
                    // console.log(attrName);
                    var key = attrName.match(/\d+/)[0];
                    // alert(key);
                    var skipName = $(el).data('skip-name');
                    // console.log('attrName',attrName);
                    var group = $(el).data("group");
                    // alert(group);


                    if (key == 0) {
                        $(el).attr("name", group + "[" + key + "]" + "[" + dataName + "]");
                    } else {
                        var key = getFirstNumber(attrName);
                        $(el).attr("name", group + "[" + key + "]" + "[" + dataName + "]");
                    }


                    $(el).attr('id', generateId($(el).attr('name')));
                    $(el).parent().find('label').attr('for', generateId($(el).attr('name')));
                });
            }, 200);
        }




        $(document).ready(function() {
            /* Create Repeater */
            calculate_grand_total();

            $('.repeater').repeater({
                isFirstItemUndeletable: false,
                // initEmpty: false,
            });

            // console.log('test');

            //practice code
            get_invoice_itemnames();

            $('.add_btn_rep').click(function() {
                get_invoice_itemnames();
                var lastIndex = $('.repeater tbody tr').length - 1;
                $("input[name='old_invoice_items[" + lastIndex + "][uom]']").val($("input[name='old_invoice_items[0][uom]']").val());
            });





        });

        $("#discount").on('change', function() {
            calculate_grand_total();
        });
</script>

<script>
    function validateInputZero(input) {
            // Get the entered value
            var value = input.value;

            // If the value is 0, prevent it and set the value to an empty string
            if (value === '0') {
                input.value = '';
                return false;
            }

            return true;
        }
</script>







<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/returninvoice/goodsreceipt_create.blade.php ENDPATH**/ ?>