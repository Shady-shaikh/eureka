@php
use Carbon\Carbon;
use App\Models\backend\Company;
use App\Models\backend\City;
use App\Models\backend\State;
use App\Models\backend\BussinessPartnerMaster;

$company = Company::where('company_id',$goodsservicereceipts->company_id)->first();
$bp_master = BussinessPartnerMaster::where('business_partner_id',$goodsservicereceipts->party_id)->first();
$all_states = State::pluck('name','id');
$all_districts = City::pluck('city_name','city_id');
@endphp
@php
// dd($invoice);
if($invoice->igst_total <= 0){ $cs_gst_percent="@ " .($invoice->gst_rate/2)." %";
  $st_gst_percent = "@ ".($invoice->gst_rate/2)." %";
  $igst_percent='';
  }else{
  $igst_percent = "@ ".($invoice->gst_rate)." %";
  $cs_gst_percent='';
  $st_gst_percent='';
  }
  
  @endphp
  <div class="table-responsive printable mb-4">
    <table class="table table-bordered table-striped table-hover" border="1">
      <thead>
        <tr>
          <td colspan="7">
            <table width="100%" class="no-border">
              <tr>
                <td width="80">
                  <img class="img-fluid for-light" src="{{ asset('public/assets/images/logo/logo.png')}}" width="80"
                    alt="">
                </td>
                <td>
                  <h2 class="text-uppercase" style="text-transform: uppercase;">{{$company->name}}</h2>
                  <p class="mb-0">
                    @php
                    $districts = City::where('city_id',$company->district)->first();
                    @endphp
                    {{$districts->city_name}}</p>
                  <p>Tel : {{$company->mobile_no??''}} GST: {{$company->gstno??''}}</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="7" style="text-align:center;font-weight:bold;font-size:20px;">Order Fulfilment</td>
        </tr>
        <tr>
          <td colspan="12">

            <p style="text-decoration: underline;text-align:center">Detail of Recipient / Billed To</p>
            Name &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp &nbsp;<b>: &nbsp;&nbsp;
              {{$bp_master->bp_name}}</b><br /><br />
            Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>: &nbsp;&nbsp;
              {{$invoice->get_bill_toaddress->building_no_name. " ,".$invoice->get_bill_toaddress->street_name ."
              ,". $invoice->get_bill_toaddress->landmark
              ." ,". $invoice->get_bill_toaddress->city }}
              {{$all_districts[$invoice->get_bill_toaddress->district]." - ".$invoice->get_bill_toaddress->pin_code}}
              {{$all_states[$invoice->get_bill_toaddress->state]}}</b> <br /><br />
            GSTIN NO.&nbsp;&nbsp; <b>:&nbsp;&nbsp&nbsp; {{ $invoice->bill_to_gst_no }}</b>
          </td>
        </tr>
        <tr>
          <td colspan="2">Order Fulfilment No</td>
          <td colspan="2">Order Fulfilment Date</td>
          <td colspan="3">GSTIN</td>
        </tr>
        <tr>
          <td colspan="2">{{$invoice->bill_no}}</td>
          <td colspan="2">{{ Carbon::parse($invoice->bill_date)->format('d-m-Y')}}</td>
          <td colspan="3">{{ $invoice->bill_to_gst_no }}</td>
        </tr>
        <tr>
          <td colspan="3">GSTIN/UIN: {{ $invoice->bill_to_gst_no }}</td>
          <td colspan="2">Place Of Supply</td>
          <td colspan="2">{{ $invoice->place_of_supply }}</td>
        </tr>
        <tr>
          <td colspan="3">Bill To: {{ $invoice->get_bill_toaddress->bp_address_name }}</td>
          <td colspan="4">Ship From: {{$invoice->get_ship_toaddress->bp_address_name}}</td>
        </tr>
        <tr>
          <th width="20">Sr.No</th>
          <th width="40%" colspan="1">Description</th>
          <th>HSN / SAC</th>
          <th>Quantity</th>
          <th>Rate</th>
          <th>MRP</th>
          <th>Total - INR</th>
        </tr>
        @php
        $total_sum = 0;
        @endphp

        @if($invoice->goodsservicereceipts_items)
        @php $i=1; @endphp
        @foreach($invoice->goodsservicereceipts_items as $item)
        @if($item->final_qty != 0)
        @php
        $total_sum += $item->total ;
        @endphp
        <tr>
          <td>{{ $i++ }}</td>
          <td colspan="1">{{ $item->item_name }}</td>
          <td>{{ $item->hsn_sac }}</td>
          <td>{{ $item->final_qty??$item->qty }}</td>
          <td>{{ $item->taxable_amount }}</td>
          <td>{{ $item->get_product->mrp }}</td>
          <td>
            {{ $item->total }}
          </td>
        </tr>
        @endif
        @endforeach
        @endif
        <tr>
          <td colspan="6" style="text-align: right;">Total</td>
          <td>{{ $total_sum }}</td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">CGST {{$cs_gst_percent}} </td>
          <td>{{ round($invoice->cgst_total,2) }}</td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">SGST {{$st_gst_percent}}</td>
          <td>{{ round($invoice->sgst_utgst_total,2) }}</td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">IGST {{$igst_percent}}</td>
          <td>{{ round($invoice->igst_total,2) }}</td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Total Tax</td>
          <td>
            {{ round($invoice->gst_grand_total,2) }}
          </td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Discount %</td>
          <td>{{ $invoice->discount }}</td>
          @php
          $discount_amount = ($total_sum * $invoice->discount) / 100 ?? 0;
          // dd($discount_amount);
          @endphp
        </tr>
        <tr>
          <td colspan="6" style="text-align: right;">Total After Discount</td>
          <td>{{ $total_sum-$discount_amount }}</td>
        </tr>

        <td colspan="6" style="text-align: right;">Final Amount</td>
        <td>{{ round(($total_sum + $invoice->gst_grand_total)- $discount_amount) }}</td>
        </tr>
        <tr>
          <td colspan="7" style="font-weight: bold;">Amount Chargeable In Words - {{ $invoice->amount_in_words }}</td>
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

        @if($invoice->goodsservicereceipts_items)
        @php $i=1; @endphp

        @foreach($invoice->goodsservicereceipts_items as $item)
        {{-- {{dd($item->hsn_sac )}} --}}
        @if($item->final_qty != 0)
        <tr>
          <td>{{ $i++ }}</td>
          <td>{{ $item->hsn_sac }}</td>
          <td>{{ $item->total }}</td>
          <td>{{ round($item->cgst_amount,2) }}</td>
          <td>{{ round($item->sgst_utgst_amount,2) }}</td>
          <td>{{ round($item->igst_amount,2) }}</td>
          <td width="80">{{ round($item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount,2) }}</td>
        </tr>
        @endif
        @endforeach
        @endif
        <tr>
          <td colspan="7" style="font-weight: bold;">Tax Amount In Words - {{ $invoice->tax_in_words }}</td>
        </tr>
        {{-- {{dd($bank_details->toArray())}} --}}
        <tr>
          <td colspan="4" rowspan="2">
            <p>Bank Details:-</p>
            <p>A/c Name :-{{ $bank_details->acc_holdername??'' }}</p>
            <p>A/c No- {{ $bank_details->ac_number??'' }}</p>
            <p>Bank Name:- {{ $bank_details->bank_name??'' }}</p>
            <p>RTGS/NEFT IFSC Code:- {{ $bank_details->ifsc??'' }}</p>
            <p>Branch: {{ $bank_details->bank_branch??'' }}</p>
          </td>
          <td colspan="3" style="text-align:center;">For {{$company->name}}</td>
        </tr>
        <tr>
          <td colspan="3" style="vertical-align: bottom;text-align: center;padding-top:100px;">Authorised Signatory</td>
        </tr>
      </tbody>
    </table>
  </div>

  @if($download)
  <style>
    @page {
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
  @endif