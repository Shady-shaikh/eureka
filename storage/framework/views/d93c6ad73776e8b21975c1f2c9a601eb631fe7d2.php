<?php
use Carbon\Carbon;
use App\Models\backend\Company;
use App\Models\backend\City;
use App\Models\backend\State;
use App\Models\backend\OrderBooking;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\BussinessPartnerContactDetails;

$company = Company::where('company_id',$goodsservicereceipts->company_id)->first();
$bp_master = BussinessPartnerMaster::where('business_partner_id',$goodsservicereceipts->party_id)->first();
// dd($bp_master->get_salesman);
$districts = City::where('city_id',$company->district)->first();
$states = State::where('id',$company->state)->first();
$all_states = State::pluck('name','id');
$all_districts = City::pluck('city_name','city_id');

$order_booking = OrderBooking::where('customer_ref_no',$goodsservicereceipts->customer_ref_no)->first();

?>
<?php
// dd($invoice->gst_rate);
if($invoice->igst_total <= 0){ $cs_gst_percent="@ " .($invoice->gst_rate/2)." %";
  $st_gst_percent = "@ ".($invoice->gst_rate/2)." %";
  $igst_percent='';
  }else{
  $igst_percent = "@ ".($invoice->gst_rate)." %";
  $cs_gst_percent='';
  $st_gst_percent='';
  }
  ?>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: sans-serif;
    }

    .container {
      width: 1280px;
      margin: 50px auto;
      overflow-x: auto;
    }

    table,
    th,
    td {
      border: 1px solid #c3c3c3;
      border-collapse: collapse;
      padding: 15px;
    }
  </style>

  <div class="table-responsive printable mb-4">

    <table style="width: 100%">

      <tr>
        <td colspan="10" rowspan="3" style="width: 100%">
          <img src="<?php echo e(asset('public/backend-assets/images/'.$company->company_logo)); ?>" width="110"
            style="padding-left:10px;padding-top:10px;float:left" />

          <h5 style="text-align:center;padding-top:5px;font-size:20px;margin-bottom:0px;"><b><?php echo e($company->name); ?></b></h5>
          <p style="text-align:center">
            HO ADD : <?php echo e($company->address_line1 .', '.$company->landmark.', '.$company->city); ?><br />
            <?php echo e($all_districts[$company->district] . ' - '.$company->pincode); ?> <br />
            .<?php echo e($states->name); ?> (State) <br />
            
            <b>GSTIN NO. &nbsp;&nbsp;:</b> &nbsp;&nbsp; <?php echo e($company->gstno??''); ?> <br />

          </p>
        </td>

      </tr>
    </table>
    <table style="width: 100%">
      <tr>
        <th colspan="26" style="background:#ddd;padding:10px 0px 0px 0px">
          <h5 style="text-align:center;"><b>TAX INVOICE</b></h5>
        </th>
      </tr>

      <!-- <tr>
        <td colspan="13" style="width: 50%">
          <?php
          $company_ship_add = BussinessPartnerAddress::where(['address_type'=>'Ship-To/ Ship-From',
          'bussiness_partner_id'=>$company->company_id])->first();

          if(empty($company_ship_add)){
          $company_ship_add = $company;
          }
          ?>
          Ship From <br />
          <?php echo e($company_ship_add->address_line1 .', '.$company_ship_add->landmark.', '.$company_ship_add->city); ?><br />
          <?php echo e($all_districts[$company_ship_add->district] . ' - '.$company_ship_add->pincode); ?> <br />
          <?php echo e($all_districts[$company_ship_add->state]); ?> <br />
        </td>
      </tr> -->



      <tr>
        <?php if(!empty($bill_address)): ?>
        <td colspan="16">

          <p style="text-decoration: underline;text-align:center">Detail of Recipient / Billed To</p>
          Name &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp &nbsp;<b>: &nbsp;&nbsp;
            <?php echo e($bp_master->bp_name); ?></b><br /><br />
          Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>: &nbsp;&nbsp;
            <?php echo e($invoice->get_bill_toaddress->building_no_name. " ,".$invoice->get_bill_toaddress->street_name ."
            ,". $invoice->get_bill_toaddress->landmark
            ." ,". $invoice->get_bill_toaddress->city); ?>

            <?php echo e($all_districts[$invoice->get_bill_toaddress->district]." - ".$invoice->get_bill_toaddress->pin_code); ?>

            <?php echo e($all_states[$invoice->get_bill_toaddress->state]); ?></b> <br /><br />
          GSTIN NO.&nbsp;&nbsp; <b>:&nbsp;&nbsp&nbsp; <?php echo e($invoice->bill_to_gst_no); ?></b>
          <p style="float:right">Place of Supply &nbsp;&nbsp;<b>: &nbsp;&nbsp;<?php echo e($goodsservicereceipts->place_of_supply); ?>

            </b></p><br /><br />
        </td>


        <?php endif; ?>

        <td colspan="15" rowspan="2">
          State Code &nbsp;&nbsp;&nbsp;</b>:&nbsp;&nbsp; <b><?php echo e($states->code ?? ''); ?></b> <br><br>
          Invoice Date &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; <b> : &nbsp;&nbsp;<?php echo e(Carbon::parse($invoice->bill_date)->format('d-m-Y')); ?> </b><br /><br />
          Invoice No. &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; <b>: &nbsp;&nbsp; <?php echo e($invoice->bill_no); ?>

          </b><br /><br />
          
          Delivery Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>:&nbsp; &nbsp;&nbsp;&nbsp;
            <?php echo e(isset($order_booking->delivery_date)?Carbon::parse($order_booking->delivery_date)->format('d-m-Y'):''); ?></b><br /><br />
          Order No &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<b> :&nbsp; &nbsp;
            <?php echo e($order_booking->bill_no??''); ?></b><br /><br />
          Order Date &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; <b>:&nbsp; &nbsp;
            <?php echo e(isset($order_booking->bill_date)?Carbon::parse($order_booking->bill_date)->format('d-m-Y'):''); ?>

          </b><br /><br />
          Sales Person &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<b>:&nbsp; &nbsp;
            <?php echo e($bp_master->get_salesmangetFullNameAttribute??''); ?></b><br /><br />
          
          
          Customer Ref No &nbsp; &nbsp;<b>: &nbsp; &nbsp;<?php echo e($goodsservicereceipts->customer_ref_no); ?></b><br /><br />
          
          
        </td>
      </tr>
      <tr>
        <td colspan="16"><?php if(!empty($ship_address)): ?>
          <p style="text-decoration: underline;text-align:center">Detail of Consignee/Address of Delivery / Shipped To
          </p>
          Name &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<b>: &nbsp; &nbsp; <?php echo e($bp_master->bp_name); ?>

          </b><br /><br />
          Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<b> : &nbsp; &nbsp;
            <?php echo e($invoice->get_ship_toaddress->building_no_name. " ,".$invoice->get_ship_toaddress->street_name ."
            ,". $invoice->get_ship_toaddress->landmark
            ." ,". $invoice->get_ship_toaddress->city); ?>

            <?php echo e($all_districts[$invoice->get_ship_toaddress->district]." - ".$invoice->get_ship_toaddress->pin_code); ?>

            <?php echo e($all_states[$invoice->get_ship_toaddress->state]); ?></b><br /><br />
          GSTIN NO. &nbsp;&nbsp;<b>: &nbsp;&nbsp; <?php echo e($invoice->bill_to_gst_no); ?></b>
          <?php
          $contact_data = BussinessPartnerContactDetails::where([
          'type'=>'Ship-To/ Ship-From',
          'bussiness_partner_id'=>$goodsservicereceipts->party_id])->first();
          ?>
          <p style="float:right">Contact No &nbsp;&nbsp; <b>: &nbsp;&nbsp; <?php echo e($contact_data->mobile_no??''); ?></b></p>
          <?php endif; ?>
        </td>
      </tr>

      <tr style="font-weight:bold;">
        <td colspan="2">SR No</td>
        <td colspan="2">HSN</td>
        <td colspan="2">Description of Goods</td>
        <td colspan="2">MRP<br />(UNIT)</td>

        
        <td colspan="2">Total Qty (Units)</td>
        <td colspan="2">Qty (Cs)</td>
        <td colspan="2">Qty (Units)</td>
        <td colspan="2">NET BASIC (Units)</td>
        <td colspan="2">NET BASIC TOTAL</td>
        
        <td colspan="2">CGST</td>
        <td colspan="2">SGST</td>
        <td colspan="2">IGST</td>
        <td colspan="2">TOTAL VALUE</td>

      </tr>

      <?php
      $gst_amt=0;
      $total_inr=0;
      $total_qty=0;
      $unit_per_case=0;
      $cgst_total=0;
      $sgst_total=0;
      $igst_total=0;
      $amount_total = 0;
      $net_basic=0;
      $mrp_total=0;
      $qty_cs_total=0;
      $qty_units_total=0;
      $net_basic_total=0;

      ?>

      <?php if($invoice->goodsservicereceipts_items): ?>
      <?php $i=1; ?>
      <?php $__currentLoopData = $invoice->goodsservicereceipts_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($item->final_qty != 0): ?>
      <?php
      $qty = $item->final_qty ?? $item->qty;
      $gst_amt += $item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount;
      $total_inr += $item->total;
      $net_basic += $item->taxable_amount;
      $net_basic_total += $item->taxable_amount * $qty;
      $mrp_total += $item->get_product->mrp;
      $qty_cs = floor($qty / ($item->get_product->unit_case * $item->get_product->dimensions_unit_pack));
      $qty_units = $qty- (floor($qty / ($item->get_product->unit_case *
      $item->get_product->dimensions_unit_pack))
      * ($item->get_product->unit_case * $item->get_product->dimensions_unit_pack));
      $qty_cs_total += $qty_cs;
      $qty_units_total += $qty_units;


      ?>

      <tr>
        <td colspan="2"><?php echo e($i++); ?></td>
        <td colspan="2"><?php echo e($item->hsn_sac); ?></td>
        <td colspan="2">
          <?php echo e($item->item_code); ?><br />
          <?php echo e($item->item_name); ?>

        </td>
        <td colspan="2"><?php echo e($item->get_product->mrp); ?></td>

        
        <td colspan="2">
          <?php
          $total_qty += $qty
          ?>
          <?php echo e($qty); ?>

        </td>
        <td colspan="2"><?php echo e($qty_cs); ?></td>
        <td colspan="2"><?php echo e($qty_units); ?></td>

        <td colspan="2"><?php echo e($item->taxable_amount); ?></td>
        <td colspan="2"><?php echo e(round($item->taxable_amount * $qty,2)); ?></td>
        
        <td colspan="2">
          <?php
          $cgst_total += $item->cgst_amount;
          ?>

          <?php if($qty ==0): ?>
          0
          <?php else: ?>
          <?php echo e(round($item->cgst_amount,2)); ?>

          <?php endif; ?>
        </td>
        <td colspan="2">
          <?php
          $sgst_total += $item->sgst_utgst_amount;
          ?>

          <?php if($qty ==0): ?>
          0
          <?php else: ?>
          <?php echo e(round($item->sgst_utgst_amount,2)); ?>

          <?php endif; ?>
        </td>
        <td colspan="2">

          <?php
          $igst_total += $item->igst_amount;
          ?>
          <?php if($qty ==0): ?>
          0
          <?php else: ?>
          <?php echo e(round($item->igst_amount,2)); ?>

          <?php endif; ?>
        </td>
        <td colspan="2">
          <?php
          $amount_total += ($item->taxable_amount * $qty +
          $item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount);
          ?>

          <?php if($qty ==0): ?>
          0
          <?php else: ?>
          <?php echo e(round($item->taxable_amount * $qty + $item->cgst_amount + $item->sgst_utgst_amount + $item->igst_amount,2)); ?>

          <?php endif; ?>
        </td>
      </tr>
      <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>

      <tr>
        <td colspan="6" class="text-right">Total</td>
        <td colspan="2"></td>
        <td colspan="2"><?php echo e($total_qty); ?></td>
        <td colspan="2"><?php echo e($qty_cs_total); ?></td>
        <td colspan="2"><?php echo e($qty_units_total); ?></td>
        <td colspan="2"></td>
        <td colspan="2"><?php echo e(round($net_basic_total,2)); ?></td>
        <td colspan="2"><?php echo e(number_format(round($cgst_total,2),2,'.',',')); ?></td>
        <td colspan="2"><?php echo e(number_format(round($sgst_total,2),2,'.',',')); ?></td>
        <td colspan="2"><?php echo e(number_format(round($igst_total,2),2,'.',',')); ?></td>
        <td colspan="2"><?php echo e(number_format(round($amount_total,2),2,'.',',')); ?></td>
      </tr>

      <tr>
        <td colspan="24" class="text-right">Taxable Amount</td>
        <td colspan="2"><?php echo e(number_format(round($net_basic_total, 2), 2, '.', ',')); ?></td>
      </tr>
      <tr>
        <td colspan="24" class="text-right">Tax Amount</td>
        <td colspan="2"><?php echo e(number_format(round($gst_amt, 2), 2, '.', ',')); ?></td>
      </tr>
      <tr>
        <td colspan="24" class="text-right">Gross Amount</td>
        <td colspan="2"><?php echo e(number_format(round($amount_total , 2), 2, '.', ',')); ?></td>
      </tr>

      <tr>
        <td colspan="12" style="width: 50%">
          <b>Bank Name</b> : <?php echo e($bank_details->bank_name??''); ?> <br>
          <b>Branch Name</b> : <?php echo e($bank_details->bank_branch??''); ?><br>
          <b>A/c No.</b> : <?php echo e($bank_details->ac_number??''); ?><br>
          <b>IFSC Code</b> : <?php echo e($bank_details->ifsc??''); ?><br>
        </td>
        <td colspan="13" style="width: 50%">
          <?php
          $discount_amount = ($total_inr * $invoice->discount) / 100 ?? 0;
          $rounded = ($amount_total)-$discount_amount;
          ?>
          <?php if(empty($igst_percent)): ?>
          CGST <?php echo e($cs_gst_percent); ?><span style="float:right;"><?php echo e(number_format(round($cgst_total,2),2,'.',',')); ?></span>
          <br>
          SGST <?php echo e($st_gst_percent); ?><span style="float:right;"><?php echo e(number_format(round($sgst_total,2),2,'.',',')); ?></span>
          <br>
          <?php else: ?>
          IGST <?php echo e($igst_percent); ?><span style="float:right;"><?php echo e(number_format(round($igst_total,2),2,'.',',')); ?></span> <br>
          <?php endif; ?>
          Total GST Amount <span style="float:right;"> <?php echo e(number_format(round($gst_amt,2),2,'.',',')); ?></span><br>
          Round off <span style="float:right;"><?php echo e(round(fmod($rounded,1),2)); ?></span><br>
          <b>Total Value after tax : <span style="float:right;">
              <?php echo e(number_format(round(($amount_total)-$discount_amount),2,'.',',')); ?></span></b><br />

        </td>
      </tr>

      <tr>
        <td colspan="25" style="width: 100%">
          Invoice Value (In Words) :
          <b> <?php echo e(amount_in_words(round($amount_total))); ?></b>
        </td>
      </tr>


      <tr>
        <td colspan="25" style="width: 100%">
          Remarks :
        </td>
      </tr>

      <tr>
        <td colspan="12">
          <b>Terms and conditions :</b><br />
          <p>
            1. Any Claims for quality must be made within 3 days
          </p>
          <p>2. Goods once delivered will not be taken back and no refund will be
            allowed.</p>
          <p>3. Shortage or leakage after delivery should be intimated in 24 hours.</p>
          <p>4. Payment will be accepted only by A/c. Payee's Draft/Cheque.</p>
          <p>5. Subject to <?php echo e(($all_districts[$company->district])); ?> Jurisdiction.</p>
          <p>6. E. & O.E.</p>
        </td>

        <td colspan="6">
          <b>Collection Person : </b> <br>
          <br><br><br><br><br>
          <b>Receiver's Sign : </b>
        </td>

        <td colspan="7">For , <b><?php echo e($company->name); ?></b><br>
          <br><br><br><br><br>
          <b>Authorised Signatory</b>
        </td>
      </tr>

      
      <tr style="page-break-before: always;margin-top: 20px!important;">
        <!-- Second table headers -->
      </tr>

      <tr style="font-weight:bold;">
        <td colspan="4" rowspan="2">HSN / SAC</td>
        <td colspan="4" rowspan="2">Taxable Amount</td>
        <td colspan="4">CGST</td>
        <td colspan="4">SGST</td>
        <td colspan="4">IGST</td>
        <td colspan="6" rowspan="2">Total Tax Amount</td>
      </tr>

      <tr>
        <td colspan="2">Rate</td>
        <td colspan="2">Amount</td>
        <td colspan="2">Rate</td>
        <td colspan="2">Amount</td>
        <td colspan="2">Rate</td>
        <td colspan="2">Amount</td>
      </tr>

      <?php
      $taxable_amt=0;
      $csgst_amt=0;
      $sgst_amt=0;
      $igst_amt=0;
      $total_taxable_amt=0;
      $i=1;
      ?>

      <?php if($invoice->goodsservicereceipts_items): ?>
      <?php $__currentLoopData = $invoice->goodsservicereceipts_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($item->final_qty != 0): ?>
      <tr>

        <?php
        $taxable_amt += $item->taxable_amount;
        $csgst_amt += $item->cgst_amount;
        $sgst_amt += $item->sgst_utgst_amount;
        $igst_amt += $item->igst_amount;
        $total_taxable_amt += $item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount;
        ?>

        <td colspan="4"><?php echo e($item->hsn_sac); ?></td>
        <td colspan="4"><?php echo e($item->total); ?></td>
        <td colspan="2"><?php echo e($cs_gst_percent); ?></td>
        <td colspan="2"><?php echo e(round($item->cgst_amount,2)); ?></td>
        <td colspan="2"><?php echo e($st_gst_percent); ?></td>
        <td colspan="2"><?php echo e(round($item->sgst_utgst_amount,2)); ?></td>
        <td colspan="2"><?php echo e($igst_percent); ?></td>
        <td colspan="2"><?php echo e(round($item->igst_amount,2)); ?></td>
        <td colspan="6"><?php echo e(round($item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount,2)); ?></td>
      </tr>
      <?php endif; ?>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>

      <tr>
        <td colspan="16"></td>
        <td colspan="4">Total : </td>
        <td colspan="5"><?php echo e(number_format(round($total_taxable_amt,2),2,'.',',')); ?></td>
      </tr>



    </table>
  </div>

  <?php if($download): ?>
  <style>
    @page  {
      body {
        color: #000;
      }

      table {
        width: 100%;
        border: 1px solid #000;
        border-collapse: collapse;
      }

      table tr td,
      table tr th {
        border: 1px solid #000;
        text-align: left;
        font-size: 12px;
        padding: 4px;
      }

      table tr th p,
      table tr td p,
      table tr td h2,
      table tr th h2 {
        margin-bottom: 0px;
        padding-bottom: 0px;
        padding-top: 0px;
        margin-top: 0px;
      }

      .invoice_items tr th,
      .invoice_items tr td {
        padding: 4px;
      }

      .no-border,
      .no-border tr td,
      .no-border tr th {
        border: none !important;
      }
  </style>
  <?php endif; ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/arinvoice/invoice_format.blade.php ENDPATH**/ ?>