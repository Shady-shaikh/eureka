
<?php $__env->startSection('title', 'Edit Order Fulfilment'); ?>

<?php $__env->startSection('content'); ?>
<?php
use App\Models\backend\Company;
use App\Models\backend\Inventory;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BinManagement;
use App\Models\backend\Bintype;

$bp_master = BussinessPartnerMaster::where('business_partner_id',$model->party_id)->first();
$company = Company::where('company_id',$bp_master->company_id)->first();

$check_all_qty = 0;
?>
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Order Fulfilment</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Order Fulfilment</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a href="<?php echo e(route('admin.orderfulfilment')); ?>" class="btn btn-outline-secondary float-right"><i
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
                            <?php echo e(Form::model($model, ['url' => 'admin/orderfulfilment/update', 'files' => true, 'class' =>
                            'w-100'])); ?>

                            <?php echo e(Form::hidden('financial_year', $fyear)); ?>

                            <?php echo e(Form::hidden('bill_to_state', null, ['class' => 'bill_to_state'])); ?>

                            <?php echo e(Form::hidden('party_state', null, ['class' => 'party_state'])); ?>

                            <?php echo e(Form::hidden('bill_to_gst_no', null, ['class' => 'bill_to_gst_no'])); ?>

                            
                            <?php echo e(Form::hidden('order_fulfillment_id', $model->order_fulfillment_id)); ?>

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



                                    <div class="col-md-3 col-sm-12 d-none">
                                        <div class="form-group">
                                            <?php echo e(Form::label('company_gstin', 'GSTIN *')); ?>

                                            <?php echo e(Form::text('company_gstin', null, ['class' => 'form-control',
                                            'placeholder' => 'GSTIN'])); ?>

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
                                                        <?php echo e(Form::label('party_code', 'Customer Code *')); ?>

                                                        <?php echo e(Form::select('party_code', $party_code, $model->party_id, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_code',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        'disabled'=>true,
                                                        ])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('party_id', 'Customer *')); ?>

                                                        <?php echo e(Form::select('party_id', $party, null, [
                                                        'class' => 'form-control select2',
                                                        'id' => 'party_id',
                                                        'placeholder' => 'Select Bill To',
                                                        'required' => true,
                                                        'disabled'=>true,
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
                                                            class="form-control readonly" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label(
                                                        'order_fullfilment_temp_no',
                                                        'Order Fulfilment No',
                                                        )); ?>

                                                        <?php echo e(Form::text('order_fullfilment_temp_no', $model->bill_no, [
                                                        'class' => 'form-control
                                                        order_fullfilment_temp_no readonly',
                                                        'placeholder' => 'Order Fulfilment No',
                                                        'disabled' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label(
                                                        'customer_ref_no',
                                                        'Customer Refrence Number
                                                        *',
                                                        )); ?>

                                                        <?php echo e(Form::text('customer_ref_no', null, [
                                                        'class' => 'form-control
                                                        customer_ref_no readonly',
                                                        'placeholder' => 'Customer Refrence
                                                        Number',
                                                        'required' => true,
                                                        ])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('place_of_supply', 'Place Of Supply')); ?>

                                                        <?php echo e(Form::text('place_of_supply', null, [
                                                        'class' => 'form-control
                                                        place_of_supply',
                                                        'placeholder' => 'Place Of Supply',
                                                        ])); ?>

                                                    </div>
                                                </div>



                                            </div>


                                            <div class="col-sm-6">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('bill_to', 'Bill To *')); ?>

                                                        <select name="bill_to" id="bill_to"
                                                            class="form-control readonly" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php echo e(Form::label('ship_from', 'Ship To *')); ?>

                                                        <select name="ship_from" id="ship_from"
                                                            class="form-control readonly" required></select>
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
                                            'placeholder' => 'Select Status',
                                            'required' => true,
                                            'readonly' => true,
                                            ])); ?>

                                        </div>

                                    
                                        <?php if(isset($company) && $company->is_backdated_date): ?>
                                        <div class="form-group">
                                            <?php echo e(Form::label('bill_date', 'Date *')); ?>

                                            <?php echo e(Form::date('bill_date', null, ['class' => 'form-control ', 'placeholder'
                                            => 'Date', 'required' => true])); ?>

                                        </div>
                                        <?php endif; ?>


                                        <div class="form-group">
                                            <?php echo e(Form::label('due_date', 'Delivery Date *')); ?>

                                            <?php echo e(Form::date('due_date', null, [
                                            'class' => 'form-control due_date',
                                            'placeholder' => 'Delivery Date',
                                            'required' => true,
                                            'readonly' => true,
                                            ])); ?>

                                        </div>

                                        <div class="form-group">
                                            <?php echo e(Form::label('document_date', 'Document Date *')); ?>

                                            <?php echo e(Form::date('document_date', null, [
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

                                <div class="col-sm-12 mb-3">
                                    <section id="form-repeater-wrapper">
                                        <!-- form default repeater -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="card-title">
                                                    Particulars Detail
                                                </h5>



                                                
                                                

                                                
                                                <div class="conatiner-fluid table-responsive repeater">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered " id="repeater"
                                                            style="width:120%;">
                                                            <thead class="bg-light" style="font-size:10px;">
                                                                <tr>
                                                                    <td class="adjust_col">
                                                                        <?php echo e(Form::label('item_code', 'Item Code')); ?>

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
                                                                    <td><?php echo e(Form::label(
                                                                        'og_qty',
                                                                        'Original
                                                                        Quantity',
                                                                        )); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label(
                                                                        'fulfil_qty',
                                                                        'Fulfilled
                                                                        Quantity',
                                                                        )); ?>

                                                                    </td>

                                                                    <td class="adjust_col d-none">
                                                                        <?php echo e(Form::label('uom', 'UOM',['style'=>'display: flex;justify-content: center;'])); ?>

                                                                        <?php echo e(Form::select('uom',
                                                                        ['units'=>'Units','case'=>'Case'],
                                                                        $model->goodsservicereceipts_items[0]->uom,
                                                                        [
                                                                            'class' => 'form-control uom',
                                                                            'data-name' => 'uom',
                                                                            'data-group' => 'invoice_items',
                                                                        ])); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label('qty', 'Quantity',['id'=>'qty_label'])); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label('final_qty', 'Quantity (Units)')); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label(
                                                                        'avail_qty',
                                                                        'Available
                                                                        Quantity',
                                                                        )); ?>

                                                                    </td>
                                                                    
                                                                    <td><?php echo e(Form::label('taxable_amount', 'Unit Price')); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label('total', 'Total INR')); ?>

                                                                    </td>
                                                                    

                                                                    <td class="adjust_col">
                                                                        <?php echo e(Form::label('GST', 'GST (%)')); ?></td>
                                                                    <td><?php echo e(Form::label('CGST', 'CGST (%)')); ?></td>
                                                                    <td><?php echo e(Form::label('SGST', 'SGST (%)')); ?></td>
                                                                    <td><?php echo e(Form::label('IGST', 'IGST (%)')); ?></td>

                                                                    <td><?php echo e(Form::label('Amount', 'GST Amount')); ?>

                                                                    </td>
                                                                   
                                                                    <td><?php echo e(Form::label('gross_total','GrossTotal')); ?>

                                                                    </td>
                                                                    <td><?php echo e(Form::label(
                                                                        'storage_location_id',
                                                                        'From
                                                                        Warehouse',
                                                                        )); ?>

                                                                    </td>

                                                                    <?php if($company->batch_system): ?>
                                                                    <td><?php echo e(Form::label('bacth_id', 'Batch Details')); ?>

                                                                    </td>
                                                                    <?php endif; ?>


                                                                </tr>
                                                            </thead>
                                                            <tbody data-repeater-list="old_invoice_items">


                                                                <?php $__currentLoopData = $model->goodsservicereceipts_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                $good_bin = Bintype::where('name','Good')->first();
                                                                $bin = BinManagement::where(['bin_type'=>$good_bin->bin_type_id,'warehouse_id' =>  $items->storage_location_id])->first();

                                                                $get_total_qty =
                                                                Inventory::where([
                                                                'warehouse_id' => $items->storage_location_id,
                                                                'sku' => $items->sku,
                                                                'bin_id'=>$bin->bin_id
                                                                ])
                                                                ->when((session('fy_year') != 0 && session('company_id') !=0),function($query){
                                                                    $query->where([
                                                                        'company_id' => session('company_id'),
                                                                    ]);
                                                                })
                                                                ->sum('qty') -
                                                                Inventory::where([
                                                                'warehouse_id' => $items->storage_location_id,
                                                                'sku' => $items->sku,
                                                                'bin_id'=>$bin->bin_id
                                                                ]) 
                                                                ->when((session('fy_year') != 0 && session('company_id') !=0),function($query){
                                                                    $query->where([
                                                                        'company_id' => session('company_id'),
                                                                    ]);
                                                                })
                                                                ->sum('blocked_qty');
                                                                // dd($get_total_qty);
                                                                if($get_total_qty !=0 && $get_total_qty>0){
                                                                    $check_all_qty = 1;
                                                                }
                                                                ?>
                                                                
                                                                <tr data-repeater-item class="item_row item-content"
                                                                    id="old_row_<?php echo e($loop->index); ?>">

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index .'][unit_pack]',$items->get_product->dimensions_unit_pack??null, ['data-group' => 'old_invoice_items'])); ?>

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index .'][pack_case]',$items->get_product->unit_case??null, ['data-group' => 'old_invoice_items'])); ?>

                                                                    

                                                                    <?php echo e(Form::hidden(
                                                                    'old_invoice_items[' . $loop->index .
                                                                    '][order_fulfillment_item_id]',
                                                                    $items->order_fulfillment_item_id,
                                                                    [
                                                                    'data-name' => 'order_fulfillment_item_id',
                                                                    'class' => 'form-control item_name typeahead',
                                                                    'placeholder' => 'Description',
                                                                    'required' => true,
                                                                    'autocomplete' => 'off',
                                                                    ],
                                                                    )); ?>





                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][cgst_rate]', $items->cgst_rate, [
                                                                    'class' => 'form-control
                                                                    custom-rate',
                                                                    'placeholder' => '%',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'cgst_rate',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][cgst_amount]', $items->cgst_amount, [
                                                                    'class' => 'form-control custom-amount
                                                                    cgst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'cgst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>


                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][sgst_utgst_rate]', $items->sgst_utgst_rate, [
                                                                    'class' => 'form-control custom-rate',
                                                                    'placeholder' => '%',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'sgst_utgst_rate',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][sgst_utgst_amount]', $items->sgst_utgst_amount,
                                                                    [
                                                                    'class' => 'form-control custom-amount
                                                                    sgst_utgst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'sgst_utgst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>


                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][igst_rate]', $items->igst_rate, [
                                                                    'class' => 'form-control
                                                                    custom-rate',
                                                                    'placeholder' => '%',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'igst_rate',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][igst_amount]', $items->igst_amount, [
                                                                    'class' => 'form-control
                                                                    custom-amount igst_amount',
                                                                    'placeholder' => 'Amt.',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'igst_amount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>


                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][sku]', $items->sku, [
                                                                    'class' => 'form-control sku',
                                                                    'data-name' => 'sku',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>


                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][gross_total]', $items->gross_total, [
                                                                    'class' => 'form-control
                                                                    gross_total',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'gross_total',
                                                                    'data-group' => 'old_invoice_items',
                                                                    'required' => true,
                                                                    'readonly' => true,
                                                                    ])); ?>



                                                                    <td><?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][item_code]',
                                                                        $items->item_code, [
                                                                        'data-name' => 'item_code',
                                                                        'class' => 'form-control item_code
                                                                        typeahead',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>


                                                                    <td><?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][item_name]',
                                                                        $items->item_name, [
                                                                        'data-name' => 'item_name',
                                                                        'class' => 'form-control
                                                                        item_name typeahead',
                                                                        'oninput' => 'validateInput(this)',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][hsn_sac]', $items->hsn_sac, [
                                                                        'class' => 'form-control readonly',
                                                                        'data-name' => 'hsn_sac',
                                                                        ])); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e(Form::number('old_invoice_items[' .
                                                                        $loop->index . '][og_qty]', $items->og_qty, [
                                                                        'class' => 'form-control og_qty',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'og_qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e(Form::number('old_invoice_items[' .
                                                                        $loop->index . '][fulfil_qty]',
                                                                        $items->fulfil_qty ?? 0, [
                                                                        'class' => 'form-control fulfil_qty',
                                                                        'onchange' => 'calculateQty(this);
                                                                        calculategst(this);',
                                                                        'data-name' => 'fulfil_qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td class="d-none">
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                            $loop->index . '][uom]',
                                                                            $items->uom,
                                                                            [
                                                                                'class' => 'form-control uom_field',
                                                                                'onchange' => 'calculategst(this)',
                                                                                'data-name' => 'uom',
                                                                                'data-group' => 'old_invoice_items',
                                                                                'readonly' => true,
                                                                            ])); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][qty]', $items->qty??$items->og_qty, [
                                                                        'class' => 'form-control qty',
                                                                        'oninput' => 'handleInput(this)',
                                                                        'data-name' => 'qty',
                                                                        'data-index' => $loop->index,
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(Form::number('old_invoice_items[' .
                                                                        $loop->index . '][final_qty]', $items->final_qty??$items->qty??$items->og_qty, [
                                                                        'class' => 'form-control final_qty',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'final_qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>


                                                                    <td>
                                                                        <?php echo e(Form::number('old_invoice_items[' .
                                                                        $loop->index . '][avail_qty]', $get_total_qty<0?0:$get_total_qty, [
                                                                        'class' => 'form-control avail_qty',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'avail_qty',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    
                                                                    <td>
                                                                        <?php echo e(Form::number('old_invoice_items[' .
                                                                        $loop->index . '][taxable_amount]',
                                                                        $items->taxable_amount, [
                                                                        'class' => 'form-control taxable_amount',
                                                                        'onchange' => 'calculateQty(this);
                                                                        calculategst(this);',
                                                                        'data-name' => 'taxable_amount',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        'required' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td><?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][total]', $items->total, [
                                                                        'class' => 'form-control total',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'total',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    
                                                                    
                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][discount_item]', $items->discount_item, [
                                                                    'class' => 'form-control
                                                                    discount_item',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'discount_item',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>

                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][price_af_discount]', $items->price_af_discount,
                                                                    [
                                                                    'class' => 'form-control price_af_discount',
                                                                    'onchange' => 'calculategst(this)',
                                                                    'data-name' => 'price_af_discount',
                                                                    'data-group' => 'old_invoice_items',
                                                                    'readonly' => true,
                                                                    ])); ?>

                                                                    


                                                                    <td style="width: 130px;">
                                                                        <?php echo e(Form::select('old_invoice_items[' .
                                                                        $loop->index . '][gst_rate]', $gst,
                                                                        $items->gst_rate, [
                                                                        'class' => 'form-control
                                                                        gst_dropdown readonly',
                                                                        'placeholder' => 'Select
                                                                        GST',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][cgst_rate]',
                                                                        $items->cgst_rate, [
                                                                        'class' => 'form-control
                                                                        custom-rate all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'cgst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][sgst_utgst_rate]',
                                                                        $items->sgst_utgst_rate, [
                                                                        'class' => 'form-control custom-rate
                                                                        all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'sgst_utgst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][igst_rate]',
                                                                        $items->igst_rate, [
                                                                        'class' => 'form-control
                                                                        custom-rate all_gst',
                                                                        'placeholder' => '%',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'igst_rate',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][gst_amount]',
                                                                        $items->gst_amount, [
                                                                        'class' => 'form-control readonly',
                                                                        'placeholder' => 'GST
                                                                        Amount',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gst_amount',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        ])); ?>

                                                                    </td>
                                                                    

                                                                    <td><?php echo e(Form::text('old_invoice_items[' .
                                                                        $loop->index . '][gross_total]',
                                                                        $items->gross_total, [
                                                                        'class' => 'form-control gross_total',
                                                                        'onchange' => 'calculategst(this)',
                                                                        'data-name' => 'gross_total',
                                                                        'data-group' => 'old_invoice_items',
                                                                        'required' => true,
                                                                        'readonly' => true,
                                                                        ])); ?>

                                                                    </td>

                                                                    <td style="width: 150px;">
                                                                        <?php echo e(Form::select(
                                                                        'old_invoice_items[' . $loop->index .
                                                                        '][storage_location_id]',
                                                                        $storage_locations,
                                                                        $items->storage_location_id,
                                                                        [
                                                                        'class' => 'form-control selected_' .
                                                                        $items->storage_location_id,
                                                                        'data-selected' => $items->storage_location_id,
                                                                        'placeholder' => 'Warehouse',
                                                                        'data-name' => 'storage_location_id',
                                                                        'required' => true,
                                                                        ],
                                                                        )); ?>


                                                                    </td>

                                                              
                                                                    <?php if($company->batch_system): ?>
                                                                    <?php
                                                                    $batch_data = Inventory::where([
                                                                            'warehouse_id' => $items->storage_location_id,
                                                                            'sku' => $items->sku,
                                                                        ])->when(session('fy_year') != 0 && session('company_id') != 0, function ($query) {
                                                                            $query->where([
                                                                                'fy_year' => session('fy_year'),
                                                                                'company_id' => session('company_id'),
                                                                            ]);
                                                                        })->pluck('batch_no', 'batch_no');

                                                                    ?>
                                                                    <td style="width: 80px;">
                                                                        <?php echo e(Form::select('old_invoice_items[' .
                                                                        $loop->index . '][batch_no]', $batch_data,
                                                                        $items->batch_no, [
                                                                        'class' => 'form-control ' . $items->batch_no,
                                                                        'data-selected' => $items->batch_no,
                                                                        'placeholder' => 'Select Batch',
                                                                        'data-name' => 'batch_no',
                                                                        'required' => true,
                                                                        ])); ?>


                                                                    </td>
                                                                    <?php else: ?>
                                                                    <?php echo e(Form::hidden('old_invoice_items[' . $loop->index
                                                                    . '][batch_no]', $items->batch_no, [
                                                                    'class' => 'form-control def_batch_no',
                                                                    'data-name' => 'batch_no',
                                                                    'data-group' => 'old_invoice_items',
                                                                    ])); ?>

                                                                    <?php endif; ?>



                                                                </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                

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
                                                    style="width:30px;" value="<?php echo e($model->discount); ?>"
                                                    oninput="calculate_grand_total()">
                                                %</strong>
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
                                <?php echo e(Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1 save_btn', 'id' => 'custom_form'])); ?>

                                <button type="reset" class="btn btn-light-secondary mr-1 mb-1 save_btn">Reset</button>
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
        validateQuantity(elem);
        validateQty(elem);
    }


    $(document).ready(function() {

            let itemIndex;

            $('#custom_form').click(function(e) {
                let items = $('.item_row');
                let flag;
                for (let i = 0; i < items.length; i++) {
                    var batchNo = document.querySelector(`#old_invoice_items_${i}_batch_no`);
                    console.log(batchNo);

                    if (batchNo) {
                        if (batchNo.value === '') {
                            flag = 'Batch Number';
                            itemIndex = i;
                            break;
                        }
                    }
                }
                ++itemIndex;
                if (flag) {
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
    function get_data_display(customer_id) {

            // alert(customer_id);
            $.get(APP_URL + '/admin/goodsservicereceipts/partydetails/' + customer_id, {}, function(
                response) {
                var customer_details = $.parseJSON(response);
                console.log(customer_details);
                if (customer_details) {
                    $(".party").html(customer_details.party_detail);
                    $(".party_input").val(customer_details.party_detail);
                    $(".bill_to_state").val(customer_details.bill_to_state);
                    $(".party_state").val(customer_details.party_state);
                    $(".bill_to_gst_no").val(customer_details.bill_to_gst_no);

                    $('.qty').each(function(index, element) {
                        calculategst(element);
                    });
                    
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

            var customer_id = $('#party_id option:selected,#party_code option:selected').val();
            if (customer_id != "") {
                get_data_display(customer_id);
            }
            $("#party_id,#party_code").on('change', function() {
                var customer_id = $(this).val();
                // alert(customer_id);
                if (customer_id != '') {
                    get_data_display(customer_id);
                }
            });


            $(document).on('click', '.batch-details-button', function(e) {
                $(this).closest('.item_row').find('.modal').modal('show');
            });


            // warehouse change event 

            //for new bacthes click
            $(document).on('click', '.storage_locations option:selected', function(e) {
                if ($(this).val() != '') {
                    var parent_div = $(this).parents()[2];
                    var suffix = $(this).attr('name');
                    $(this).closest('.item_row').find('.modal').modal('show');
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
    
            // usama remove freeze part of status
            var all_items_qty = '<?php echo e($check_all_qty); ?>';
            if(all_items_qty == 0){
                $('.save_btn').addClass('d-none');
            }

             //remove disbaled attr to get data in post while submitting the form
             $('form').submit(function() {
                $('#party_id').prop('disabled', false);
                $('#party_code').prop('disabled', false);
             });
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



            // 08-01-2024 -usama
            $('#party_code').on('change', function() {
                $('#party_id').val($(this).val()).trigger('change.select2');
            });

            $('#party_id').on('change', function() {
                $('#party_code').val($(this).val()).trigger('change.select2');
            });


        });

        $("#discount").on('change', function() {
            calculate_grand_total();
        });
</script>


<script>

        function calculateQty(input) {
            var ogQty = parseFloat($(input).closest('tr').find('.og_qty').val()) || 0;
            var fulfilQty = parseFloat($(input).closest('tr').find('.fulfil_qty').val()) || 0;

            // Calculate remaining quantity
            var remainingQty = ogQty - fulfilQty;

            // Update the quantity field
            $(input).closest('tr').find('.qty').val(remainingQty);

            // Restrict input to be less than or equal to the original quantity
            // $(input).attr('max', ogQty);
        }

        // usama_22-03-2024 validate quantity
        document.addEventListener("DOMContentLoaded", function() {
            // Select all input fields within the repeater
            var inputsToValidate = document.querySelectorAll('input[name^="old_invoice_items"][data-name="qty"]');

            // Validate each input field
            inputsToValidate.forEach(function(input) {
                validateQuantity(input);
            });
        });

    // usama_07-02-2024- to restrict user to enter more than available/original qty
        
    function validateQuantity(input) {
        var rowIndex = input.getAttribute('data-index');
        var originalQuantity = parseFloat(document.querySelector('input[name="old_invoice_items[' + rowIndex + '][og_qty]"]').value);
        var availableQuantity = parseFloat(document.querySelector('input[name="old_invoice_items[' + rowIndex + '][avail_qty]"]').value);
        var finalQty = document.querySelector('input[name="old_invoice_items[' + rowIndex + '][final_qty]"]');
        var enteredQuantity = parseFloat(input.value);

        if(enteredQuantity >0){
            // If available quantity is less than original, restrict input to available quantity
            if (availableQuantity < originalQuantity && enteredQuantity > availableQuantity) {
                // alert('You cannot enter more than the available quantity.');
                $(input).val("");
                $(finalQty).val("");
            }
            // If available quantity is equal to or greater than original, restrict input to original quantity
            if (availableQuantity >= originalQuantity && enteredQuantity > originalQuantity) {
                //alert('You cannot enter more than the original quantity.');
                // input.value = originalQuantity;
                // finalQty.value = originalQuantity;
                $(input).val("");
                $(finalQty).val("");
            }
        }else if(enteredQuantity <0){
            alert('Please enter a valid quantity');
            $(input).val("");
        }
    }


</script>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/orderfulfilment/edit.blade.php ENDPATH**/ ?>