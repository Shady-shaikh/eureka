
<?php $__env->startSection('title', 'Create PurchaseOrder'); ?>


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
                <a href="<?php echo e(route('admin.purchaseorder')); ?>" class="btn btn-outline-secondary float-right"><i
                        class="bx bx-arrow-back"></i><span class="align-middle ml-25">Back</span></a>
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
                            <?php echo e(Form::open(['url' => 'admin/purchaseorder/store'])); ?>

                            <?php echo e(Form::hidden('bill_to_state', '', ['class' => 'bill_to_state'])); ?>

                            <?php echo e(Form::hidden('party_name', '', ['class' => 'party_name'])); ?>

                            <?php echo e(Form::hidden('party_state', '', ['class' => 'party_state'])); ?>

                            <?php echo e(Form::hidden('bill_to_gst_no', '', ['class' => 'bill_to_gst_no'])); ?>

                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <?php echo e(Form::hidden('bill_date', date('Y-m-d'), [
                                            'class' => ' form-control
                                            digits',
                                            'placeholder' => 'PurchaseOrder Date',
                                            'required' => true,
                                            'readonly' => true,
                                            ])); ?>

                                        </div>
                                    </div>


                                    <div class="col-sm-12">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">

                                                <?php
                                                $values = array_keys($party->toArray());
                                                $party_code = array_combine($values, $values);
                                                ?>



                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('party_code', 'Vendor Code *')); ?>

                                                        <?php echo e(Form::select('party_code', $party_code, null, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_code',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('party_id', 'Vendor *')); ?>

                                                        <?php echo e(Form::select('party_id', $party, null, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_id',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        
                                                        <?php echo e(Form::hidden('bill_to_gst_no', null, [
                                                        'class' => 'form-control bill_to_gst_no',
                                                        'placeholder' => 'GSTIN/UIN',
                                                        'readonly' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('contact_person', 'Contact Person *')); ?>

                                                        <select name="contact_person" id="contact_person"
                                                            class="form-control" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 d-none doc_no">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('po_temp_no', 'PO Order Number')); ?>

                                                        <?php echo e(Form::text('po_temp_no', null, [
                                                        'class' => 'form-control po_temp_no',
                                                        'placeholder' => 'PO Number',
                                                        'disabled' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('vendor_ref_no', 'Vendor PO Refrence Number *')); ?>

                                                        <?php echo e(Form::text('vendor_ref_no', null, [
                                                        'class' => 'form-control
                                                        vendor_ref_no',
                                                        'placeholder' => 'Vendor PO Refrence Number',
                                                        'required' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('ship_from', 'Ship From *')); ?>

                                                        <select name="ship_from" id="ship_from" class="form-control"
                                                            required></select>

                                                    </div>
                                                </div>


                                            </div>


                                        </div>

                                    </div>


                                    <div class="col-md-3 col-sm-3">
                                    </div>
                                    <div class="col-md-3 col-sm-3">


                                        <div class="form-group">
                                            <?php echo e(Form::label('status', 'Status *')); ?>

                                            <?php echo e(Form::select('status', ['open' => 'Open', 'close' => 'Close'], null, [
                                            'class' => 'form-control status',
                                            'required' => true,
                                            'readonly' => true,
                                            ])); ?>

                                        </div>



                                        <div class="form-group d-none back_dated">
                                            <?php echo e(Form::label('bill_date', 'Date *')); ?>

                                            <?php echo e(Form::date('bill_date', date('Y-m-d'), [
                                            'class' => 'form-control ',
                                            'placeholder' => 'Date',
                                            'required' => true,
                                            ])); ?>

                                        </div>



                                        <div class="form-group">
                                            <?php echo e(Form::label('delivery_date', 'Delivery Date *')); ?>

                                            <?php echo e(Form::date('delivery_date', null, [
                                            'class' => 'form-control delivery_date',
                                            'placeholder' => 'Delivery Date',
                                            'required' => true,
                                            ])); ?>

                                        </div>

                                        <div class="form-group">
                                            <?php echo e(Form::label('document_date', 'Document Date *')); ?>

                                            <?php echo e(Form::date('document_date', date('Y-m-d'), [
                                            'class' => 'form-control document_date',
                                            'placeholder' => 'Document Date',
                                            'required' => true,
                                            'readonly' => true,
                                            ])); ?>

                                        </div>


                                    </div>

                                    
                            </div>


                            <div class="row">

                                <div class="col-sm-12">
                                    <hr>
                                </div>

                                <div class="col-sm-12">
                                    <section id="form-repeater-wrapper">
                                        <!-- form default repeater -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="card-title">
                                                    Particulars Detail
                                                </h5>


                                                
                                                
                                                    <div class="repeater-heading">


                                                    </div>

                                                    <!-- Repeater Items -->
                                                    

                                                        <div class="conatiner-fluid  repeater ">
                                                            <button type="button" data-repeater-create
                                                                class="btn btn-primary pull-right mb-2 add_btn_rep">Add</button>

                                                            <div class="table-responsive ">
                                                                <table class="table table-bordered " id="repeater"
                                                                    style="width:120%;">
                                                                    <thead class="bg-light" style="font-size:10px;">
                                                                        <tr>
                                                                            <td class="adjust_col">
                                                                                <?php echo e(Form::label(
                                                                                'item_code',
                                                                                'Item
                                                                                Code',
                                                                                )); ?>

                                                                            </td>
                                                                            <td class="adjust_col">
                                                                                <?php echo e(Form::label(
                                                                                'item_name',
                                                                                'Item
                                                                                Description',
                                                                                )); ?>

                                                                            </td>
                                                                            <td class="adjust_col">
                                                                                <?php echo e(Form::label('hsn_sac', 'HSN/SAC')); ?>

                                                                            </td>
                                                                            <td class="adjust_col">
                                                                                <?php echo e(Form::label('uom',
                                                                                'UOM',['style'=>'display:
                                                                                flex;justify-content: center;'])); ?>

                                                                                <?php echo e(Form::select('uom',
                                                                                ['units'=>'Units','case'=>'Case'],
                                                                                null,
                                                                                [
                                                                                'class' => 'form-control uom',
                                                                                'data-name' => 'uom',
                                                                                'data-group' => 'invoice_items',
                                                                                ])); ?>

                                                                            </td>
                                                                            </td>
                                                                            <td><?php echo e(Form::label('qty',
                                                                                'Quantity',['id'=>'qty_label'])); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('final_qty', 'Quantity
                                                                                (Units)')); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('taxable_amount', 'Unit
                                                                                Price')); ?>

                                                                            </td>

                                                                            <td><?php echo e(Form::label('total', 'Total INR')); ?>

                                                                            </td>
                                                                            <td class="adjust_col">
                                                                                <?php echo e(Form::label('GST', 'GST (%)')); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('CGST', 'CGST (%)')); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('SGST', 'SGST (%)')); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('IGST', 'IGST (%)')); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label(
                                                                                'Amount',
                                                                                'GST
                                                                                Amount',
                                                                                )); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label(
                                                                                'gross_total',
                                                                                'Gross
                                                                                Total',
                                                                                )); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::label('storage_location_id',
                                                                                'Warehouse')); ?>

                                                                            </td>
                                                                            <td></td>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody data-repeater-list="invoice_items"
                                                                        class="repeater">


                                                                        <?php
                                                                  
                                                                    for ($i=0; $i < count(old('invoice_items')??['1']); $i++){ 
                                                                    ?>
                                                                        <tr data-repeater-item class="item_row">

                                                                            <?php echo e(Form::hidden('unit_pack',old('invoice_items')[$i]['unit_pack']??null,
                                                                            ['data-group' => 'invoice_items'])); ?>

                                                                            <?php echo e(Form::hidden('pack_case',old('invoice_items')[$i]['pack_case']??null,
                                                                            ['data-group' => 'invoice_items'])); ?>


                                                                            <?php echo e(Form::hidden('bill_to_state',
                                                                            old('invoice_items')[$i]['bill_to_state'] ??
                                                                            null, [
                                                                            'class' => 'form-control
                                                                            bill_to_state',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'bill_to_state',
                                                                            'data-group' => 'invoice_items',
                                                                            'required' => true,
                                                                            ])); ?>

                                                                            <?php echo e(Form::hidden('party_state',
                                                                            old('invoice_items')[$i]['party_state'] ??
                                                                            null, [
                                                                            'class' => 'form-control
                                                                            party_state',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'party_state',
                                                                            'data-group' => 'invoice_items',
                                                                            'required' => true,
                                                                            ])); ?>



                                                                            <?php echo e(Form::hidden('cgst_amount',
                                                                            old('invoice_items')[$i]['cgst_amount'] ??
                                                                            null, [
                                                                            'class' => 'form-control
                                                                            custom-amount cgst_amount',
                                                                            'placeholder' => 'Amt.',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'cgst_amount',
                                                                            'data-group' => 'invoice_items',
                                                                            ])); ?>


                                                                            <?php echo e(Form::hidden('sgst_utgst_amount',
                                                                            old('invoice_items')[$i]['sgst_utgst_amount']
                                                                            ?? null, [
                                                                            'class' => 'form-control
                                                                            custom-amount sgst_utgst_amount',
                                                                            'placeholder' => 'Amt.',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'sgst_utgst_amount',
                                                                            'data-name' => 'sgst_utgst_amount',
                                                                            'data-group' => 'invoice_items',
                                                                            ])); ?>


                                                                            <?php echo e(Form::hidden('igst_amount',
                                                                            old('invoice_items')[$i]['igst_amount'] ??
                                                                            null, [
                                                                            'class' => 'form-control
                                                                            custom-amount igst_amount',
                                                                            'placeholder' => 'Amt.',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'igst_amount',
                                                                            'data-group' => 'invoice_items',
                                                                            ])); ?>




                                                                            <td><?php echo e(Form::text('item_code',
                                                                                old('invoice_items')[$i]['item_code'] ??
                                                                                null, [
                                                                                'data-id' => 'item_code',
                                                                                'data-name' => 'item_code',
                                                                                'id' => 'auto_product_' . $i,
                                                                                'class' => 'form-control item_code
                                                                                typeahead',
                                                                                'autocomplete' => 'on',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::text('item_name',
                                                                                old('invoice_items')[$i]['item_name'] ??
                                                                                null, [
                                                                                'data-id' => 'item_name',
                                                                                'data-name' => 'item_name',
                                                                                'id' => 'auto_product_' . $i,
                                                                                'class' => 'form-control item_name
                                                                                typeahead',
                                                                                'autocomplete' => 'on',
                                                                                'data-group' => 'invoice_items',
                                                                                'oninput' => 'validateInput(this)',
                                                                                ])); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::text('hsn_sac',
                                                                                old('invoice_items')[$i]['hsn_sac'] ??
                                                                                null, [
                                                                                'class' => 'form-control
                                                                                hsn_sac',
                                                                                'data-name' => 'hsn_sac',
                                                                                'readonly'=>true,
                                                                                ])); ?>

                                                                            </td>

                                                                            <td>
                                                                                <?php echo e(Form::text('uom',
                                                                                old('invoice_items')[$i]['uom'] ?? null,
                                                                                [
                                                                                'class' => 'form-control uom_field',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'uom',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>



                                                                            <td> <?php echo e(Form::text('qty',
                                                                                old('invoice_items')[$i]['qty'] ?? null,
                                                                                [
                                                                                'class' => 'form-control qty',
                                                                                'oninput' => 'handleInput(this)',
                                                                                'data-name' => 'qty',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td> <?php echo e(Form::number('final_qty',
                                                                                old('invoice_items')[$i]['final_qty'] ??
                                                                                0,
                                                                                [
                                                                                'class' => 'form-control final_qty',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'final_qty',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::number('taxable_amount',
                                                                                old('invoice_items')[$i]['taxable_amount']
                                                                                ?? null, [
                                                                                'class' => 'form-control
                                                                                taxable_amount',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'taxable_amount',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                'required' => true,
                                                                                ])); ?>

                                                                            </td>

                                                                            <?php echo e(Form::hidden('discount_item',
                                                                            old('invoice_items')[$i]['discount_item'] ??
                                                                            null, [
                                                                            'class' => 'form-control
                                                                            discount_item',
                                                                            'id' => 'discount_item',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'discount_item',
                                                                            'data-group' => 'invoice_items',
                                                                            ])); ?>

                                                                            <?php echo e(Form::hidden('price_af_discount',
                                                                            old('invoice_items')[$i]['price_af_discount']
                                                                            ?? null, [
                                                                            'class' => 'form-control
                                                                            price_af_discount',
                                                                            'onchange' => 'calculategst(this)',
                                                                            'data-name' => 'price_af_discount',
                                                                            'data-group' => 'invoice_items',
                                                                            'readonly' => true,
                                                                            ])); ?>


                                                                            <td><?php echo e(Form::text('total',
                                                                                old('invoice_items')[$i]['total'] ??
                                                                                null, [
                                                                                'class' => 'form-control
                                                                                total',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'total',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td style="width: 130px;">
                                                                                <?php
                                                                                $disable = true;
                                                                                if (old('party_id')) {
                                                                                $disable = false;
                                                                                }
                                                                                ?>
                                                                                <?php echo e(Form::select('gst_rate', $gst,
                                                                                old('invoice_items')[$i]['gst_rate'] ??
                                                                                null, [
                                                                                'class' => 'form-control
                                                                                gst_type gst_dropdown ',
                                                                                'placeholder' => 'Select GST',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'gst_rate',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>

                                                                            <td>
                                                                                <?php echo e(Form::text('cgst_rate',
                                                                                old('invoice_items')[$i]['cgst_rate'] ??
                                                                                null, [
                                                                                'class' => 'form-control
                                                                                custom-rate all_gst',
                                                                                'placeholder' => '%',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'cgst_rate',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td>
                                                                                <?php echo e(Form::text('sgst_utgst_rate',
                                                                                old('invoice_items')[$i]['sgst_utgst_rate']
                                                                                ?? null, [
                                                                                'class' => 'form-control
                                                                                custom-rate all_gst',
                                                                                'placeholder' => '%',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'sgst_utgst_rate',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td>
                                                                                <?php echo e(Form::text('igst_rate',
                                                                                old('invoice_items')[$i]['igst_rate'] ??
                                                                                null, [
                                                                                'class' => 'form-control
                                                                                custom-rate all_gst',
                                                                                'placeholder' => '%',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'igst_rate',
                                                                                'data-group' => 'invoice_items',
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>

                                                                            <td><?php echo e(Form::text('gst_amount',
                                                                                old('invoice_items')[$i]['gst_amount']
                                                                                ?? null, [
                                                                                'class' => 'form-control
                                                                                gst_amount',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'gst_amount',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>
                                                                            <td><?php echo e(Form::text('gross_total',
                                                                                old('invoice_items')[$i]['gross_total']
                                                                                ?? null, [
                                                                                'class' => 'form-control
                                                                                gross_total',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'gross_total',
                                                                                'data-group' => 'invoice_items',
                                                                                'required' => true,
                                                                                'readonly' => true,
                                                                                ])); ?>

                                                                            </td>

                                                                            <td style="width: 210px;">
                                                                                <?php echo e(Form::select(
                                                                                'storage_location_id',
                                                                                $storage_locations,
                                                                                old('invoice_items')[$i]['storage_location_id']
                                                                                ?? null,
                                                                                [
                                                                                'class' => 'form-control
                                                                                storage_locations',
                                                                                'data-name' => 'storage_location_id',
                                                                                'required' => true,
                                                                                ],
                                                                                )); ?>

                                                                            </td>





                                                                            <td><button type='button'
                                                                                    class='btn btn-danger btn-flat btn-xs old_rep_item_del'
                                                                                    data-repeater-delete><i
                                                                                        class='fa fa-fw fa-remove'></i></button>
                                                                            </td>


                                                                        </tr>

                                                                        <?php
                                                                   
                                                                    }
                                                                    ?>





                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>




                                                    </div>
                                                </div>

                                    </section>

                                </div>







                                <div class="col-sm-12">

                                </div>

                            </div>

                            <hr>


                            <div class="row">


                                <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <?php echo e(Form::label('remarks', 'Remarks')); ?>

                                        <?php echo e(Form::textarea('remarks', null, [
                                        'class' => 'form-control remarks',
                                        'placeholder' => 'Remarks',
                                        'style' => 'height:100px;',
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                </div>

                                <div class="col-md-3 col-sm-3">
                                    <div>
                                        <p>Total Before Discount: <strong><span class="total_amount">0</span></strong>
                                        </p>
                                    </div>
                                    <div>
                                        <p>Discount: <strong><input type="number" name="discount" id="discount"
                                                    style="width:30px;" oninput="calculate_grand_total()"
                                                    value="<?php echo e(old('discount') ?? 0); ?>"> %</strong>
                                            <input class="discount_amt w-25 readonly" value="">
                                        </p>
                                    </div>
                                    <div>
                                        <p>Total After Discount: <strong><span class="total_af_disc"></span>0</strong>
                                        </p>
                                    </div>

                                    <div>
                                        <p>Tax: <strong><span class="gst_total">0</span></strong></p>
                                    </div>
                                    <div>
                                        <p>Rounding: <strong><span class="rounding"></span></strong></p>
                                    </div>
                                    <div>
                                        <p>Total Payment Due: <strong><span class="final_amount">0</span></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-center">
                                <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1 '])); ?>

                                <button type="reset" class="btn btn-light-secondary mr-1 mb-1 ">Reset</button>
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




<script>
    function handleInput(elem) {
        calculategst(elem);
        validateQty(elem);
    }



    function get_data_display(customer_id) {

            // alert(customer_id);

            $.get(APP_URL + '/admin/purchaseorder/partydetails/' + customer_id, {}, function(
                response) {
                var customer_details = $.parseJSON(response);
                // console.log(customer_details);
                if (customer_details) {
                    $(".party").html(customer_details.party_detail);
                    $(".party_input").val(customer_details.party_detail);
                    $(".bill_to_state").val(customer_details.bill_to_state);
                    $(".party_state").val(customer_details.party_state);
                    $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);
                    $(".party_name").val(customer_details.party_name);

                    // alert(customer_details.bill_to_state);
                    // alert(customer_details.party_state);
                    if (customer_details.bill_to_state != '' && customer_details.party_state != '') {
                        $(".gst_dropdown").prop('disabled', false);
                    }

                } else {
                    // alert('nop res');
                    $(".pary").html("");
                    $(".party_input").val('');
                    $(".bill_to_state").val('');
                    $(".party_state").val('');
                }
            });

        }

        

    

        $(document).ready(function() {




            var customer_id = $('#party_id option:selected,#party_code option:selected').val();
            if (customer_id != "") {
                get_data_display(customer_id);
            }
            $("#party_id,#party_code").on('change', function() {


            // fetch company_id for party
            $.ajax({
                    method: 'get',
                    url: '<?php echo e(route('admin.get_company')); ?>',
                    data: {
                        party_id: $(this).val(),
                    },
                    // dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if(data.company.is_backdated_date != 0){
                            $('.back_dated').removeClass('d-none');
                        }

                    }
            });

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
                        $('#po_temp_no').val(newDocNumber);

                    }
                });
               

                $(".gst_dropdown").val('');
                $(".all_gst").val('');
                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
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

            calculate_grand_total();

            $('.repeater').repeater({
                isFirstItemUndeletable: true,
                // initEmpty: false,
            });


            get_invoice_itemnames();

            $('.add_btn_rep').click(function() {

                //set default first warehouse
                $('.storage_locations').each(function() {
                    $(this).val($(this).find('option:first').val()); // Set the default value to the first option
                });
                
                get_invoice_itemnames();
                var lastIndex = $('.repeater tbody tr').length - 1;
                $("input[name='invoice_items[" + lastIndex + "][uom]']").val($("input[name='invoice_items[0][uom]']").val());
            });


            // 08-01-2024 -usama
            $('#party_code').on('change', function() {
                $('.repeater').show();
                $('#party_id').val($(this).val()).trigger('change.select2');
            });

            $('#party_id').on('change', function() {
                $('#party_code').val($(this).val()).trigger('change.select2');
            });

        });
</script>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/purchaseorder/create.blade.php ENDPATH**/ ?>