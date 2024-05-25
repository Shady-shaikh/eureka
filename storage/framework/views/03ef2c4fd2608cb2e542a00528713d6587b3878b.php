<?php
use Carbon\Carbon;
use App\Models\backend\Company;
use App\Models\backend\City;

$company = Company::where('company_id',$purchaseorder->company_id)->first();
// dd($company);
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
  <div class="table-responsive printable mb-4">
    
    <table class="table table-bordered table-striped table-hover" border="1">
      <thead>
        <tr>
          <td colspan="7">
            <table width="100%" class="no-border">
              <tr>
                <td width="80">
                  <img class="img-fluid for-light" src="<?php echo e(asset('public/assets/images/logo/logo.png')); ?>" width="80"
                    alt="">
                </td>
                <td>
                  <h2 class="text-uppercase" style="text-transform: uppercase;"><?php echo e($company->name); ?></h2>
                  <p class="mb-0">
                    <?php
                    $districts = City::where('city_id',$company->district)->first();
                    ?>
                    <?php echo e($districts->city_name); ?></p>
                  <p>Tel : <?php echo e($company->mobile_no??''); ?> GST: <?php echo e($company->gstno??''); ?></p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="7" style="text-align:center;font-weight:bold;font-size:20px;">PURCHASE ORDER</td>
        </tr>
        <tr>
          <td colspan="2">Purchase Order No</td>
          <td colspan="2">Purchase Order Date</td>
          <td colspan="3">GSTIN</td>
        </tr>
        <tr>
          <td colspan="2"><?php echo e($invoice->bill_no); ?></td>
          <td colspan="2"><?php echo e(Carbon::parse($invoice->bill_date)->format('d-m-Y')); ?></td>
          <td colspan="3"><?php echo e($invoice->bill_to_gst_no); ?></td>
        </tr>
        <tr>
          <td colspan="3">GSTIN/UIN: <?php echo e($invoice->bill_to_gst_no); ?></td>
          <td colspan="2">Ship From</td>
          <td colspan="2"><?php echo e($invoice->get_ship_toaddress->bp_address_name); ?></td>
        </tr>
        <tr>
          <th width="20">Sr.No</th>
          <th width="40%" colspan="2">Description</th>
          <th>HSN / SAC</th>
          <th>Quantity (Units)</th>
          <th>Rate</th>
          <th>Total - INR</th>
        </tr>
        <?php
        $total_sum = 0;
        ?>
        <?php if($invoice->purchaseorder_items): ?>
        <?php $i=1; ?>
        <?php $__currentLoopData = $invoice->purchaseorder_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $total_sum += $item->total ;
        ?>
        <tr>
          <td><?php echo e($i++); ?></td>
          <td colspan="2"><?php echo e($item->item_name); ?></td>
          <td><?php echo e($item->hsn_sac); ?></td>
          <td><?php echo e($item->final_qty); ?></td>
          <td><?php echo e($item->taxable_amount); ?></td>
          
          <td><?php echo e($item->total); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <tr>
          
          <td colspan="6" style="text-align: right;">Total</td>
          <td><?php echo e($total_sum); ?></td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">CGST <?php echo e($cs_gst_percent); ?> </td>
          <td><?php echo e(round($invoice->cgst_total,2)); ?></td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">SGST <?php echo e($st_gst_percent); ?></td>
          <td><?php echo e(round($invoice->sgst_utgst_total,2)); ?></td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">IGST <?php echo e($igst_percent); ?></td>
          <td><?php echo e(round($invoice->igst_total,2)); ?></td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Total Tax</td>
          <td>
            <?php echo e(round($invoice->gst_grand_total,2)); ?>

          </td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Discount %</td>
          <td><?php echo e($invoice->discount); ?></td>
          <?php
          $discount_amount = ($total_sum * $invoice->discount) / 100 ?? 0;
          // dd($discount_amount);
          ?>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Total After Discount</td>
          <td><?php echo e($total_sum-$discount_amount); ?></td>
        </tr>

        <tr>
          <td colspan="6" style="text-align: right;">Final Amount</td>
          <td><?php echo e(round(($total_sum + $invoice->gst_grand_total)- $discount_amount)); ?></td>
        </tr>
        <tr>
          <td colspan="7" style="font-weight: bold;">Amount Chargeable In Words - <?php echo e($invoice->amount_in_words); ?></td>
        </tr>
        <tr>
          <th width="20">Sr.No</th>
          <th>HSN / SAC</th>
          <th>Taxable Value</th>
          <th>CGST</th>
          <th>SGST</th>
          <th>IGST</th>
          <th width="40">Total - INR</th>
        </tr>
        <?php if($invoice->purchaseorder_items): ?>
        <?php $i=1; ?>
        <?php $__currentLoopData = $invoice->purchaseorder_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td><?php echo e($i++); ?></td>
          <td><?php echo e($item->hsn_sac); ?></td>
          <td><?php echo e($item->total); ?></td>
          <td><?php echo e(round($item->cgst_amount,2)); ?></td>
          <td><?php echo e(round($item->sgst_utgst_amount,2)); ?></td>
          <td><?php echo e(round($item->igst_amount,2)); ?></td>
          <td width="80"><?php echo e(round($item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount,2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <tr>
          <td colspan="7" style="font-weight: bold;">Tax Amount In Words - <?php echo e($invoice->tax_in_words); ?></td>
        </tr>
        <tr>
          <td colspan="4" rowspan="2">

          </td>
          <td colspan="3" style="text-align:center;">For <?php echo e($company->name); ?></td>
        </tr>
        <tr>
          <td colspan="3" style="vertical-align: bottom;text-align: center;padding-top:100px;">Authorised Signatory</td>
        </tr>
      </tbody>
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
  <?php endif; ?><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/purchaseorder/invoice_format.blade.php ENDPATH**/ ?>