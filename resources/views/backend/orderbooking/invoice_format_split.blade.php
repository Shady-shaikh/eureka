@php
use Carbon\Carbon;
@endphp
<div class="table-responsive printable mb-4">
        @if($invoice->invoice_items)
        @php $invoice_counter = "A"; @endphp
        @foreach($invoice->invoice_items as $item)
        @php $i=1; @endphp


        @php
  if($item->igst_total <= 0){
    $cs_gst_percent = "@ ".($item->cgst_rate)." %";
    $st_gst_percent = "@ ".($item->sgst_utgst_rate)." %";
    $igst_percent='';
  }else{
    $igst_percent = "@ ".($item->igst_rate)." %";
    $cs_gst_percent='';
    $st_gst_percent='';
  }
@endphp


        <table class="table table-bordered table-striped table-hover" border="1" style="page-break-after:always;">
            <thead>
              <tr>
                <td colspan="7">
                  <table width="100%" class="no-border">
                  <tr>
                  <td width="80">
                  <img class="img-fluid for-light" src="{{ asset('public/assets/images/logo/logo.png')}}" width="80" alt="">
                  </td>
                  <td>
                    <h2 class="text-uppercase" style="text-transform: uppercase;">Raj Marine Services Private Limited</h2>
                    <p class="mb-0">'Tower House' 'Aberdeen Bazar' Port Blair - 744 101, A & N Islands</p>
                    <p>Tel : (03192) 231186/232308/233244 Fax : (03192) 230896/230109</p>
                  </td>
                  </tr>
                  </table>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="7" style="text-align:center;font-weight:bold;font-size:20px;">TAX INVOICE</td>
              </tr>
              <tr>
                <td colspan="2">Invoice No</td>
                <td colspan="2">Invoice Date</td>
                <td colspan="3">GSTIN</td>
              </tr>
              <tr>
                <td colspan="2">{{$invoice->bill_no}} {{$invoice_counter++}}</td>
                <td colspan="2">{{ Carbon::parse($invoice->bill_date)->format('d-m-Y')}}</td>
                <td colspan="3">{{ $invoice->bill_to_gst_no }}</td>
              </tr>
              <tr>
                <td colspan="3">To,</td>
                <td colspan="2">Vessel</td>
                <td colspan="2">{{ $invoice->vessel }}</td>
              </tr>
              <tr>
                <td rowspan="3" colspan="3">
                  <p class="mb-0"><strong>{{ $party->name }}</strong></p>
                  <p class="mb-0">{!! nl2br($party->address) !!}</p>
                  <p class="mb-0">POS:Code & State: {{ $party->state }}</p>
                </td>
                <td colspan="2">Port</td>
                <td colspan="2">{{ $invoice->port }}</td>
              </tr>
              <tr>
                <td colspan="2">Date Of Arrival</td>
                <td colspan="2">{{ Carbon::parse($invoice->date_of_arrival)->format('d-m-Y') }}</td>
              </tr>
              <tr>
                <td colspan="2">Date Of Departure</td>
                <td colspan="2">{{ Carbon::parse($invoice->date_of_departure)->format('d-m-Y') }}</td>
              </tr>
              <tr>
                <td colspan="3">GSTIN/UIN: {{ $invoice->bill_to_gst_no }}</td>
                <td colspan="2">Place Of Supply</td>
                <td colspan="2">{{ $invoice->place_of_supply }}</td>
              </tr>
              <tr>
              <th width="20">Sr.No</th>
              <th width="40%" colspan="2">Description</th>
              <th>SAC</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Total - INR</th>
              </tr>
                <tr>
                  <td>{{ $i++ }}</td>
                  <td colspan="2">{{ $item->item_name }}</td>
                  <td>{{ $item->hsn_sac }}</td>
                  <td>{{ $item->qty }}</td>
                  <td>{{ $item->taxable_amount }}</td>
                  <td>{{ $item->total }}</td>
                </tr>
              <tr>
                <td colspan="6" style="text-align: right;">Total</td>
                <td>{{ $item->total }}</td>
              </tr>
              <tr>
              <td colspan="6" style="text-align: right;">CGST {{$cs_gst_percent}}  </td>
              <td>{{ $item->cgst_amount }}</td>
            </tr>
            <tr>
              <td colspan="6" style="text-align: right;">SGST {{$st_gst_percent}}</td>
              <td>{{ $item->sgst_utgst_amount }}</td>
            </tr>
            <tr>
              <td colspan="6" style="text-align: right;">IGST {{$igst_percent}}</td>
              <td>{{ $item->igst_amount }}</td>
            </tr>
            <tr>
              <td colspan="6" style="text-align: right;">Grand Total</td>
              <td>{{ $total_amt = $item->total+$item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount }}</td>
            </tr>
            <tr>
              <td colspan="7" style="font-weight: bold;">Amount Chargeable In Words - {{ amount_in_words($total_amt) }}</td>
            </tr>
            <tr>
              <th width="20">Sr.No</th>
              <th>SAC</th>
              <th>Taxable Value</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>IGST</th>
              <th width="40">Total - INR</th>
              </tr>
                <tr>
                  <td>{{ $i++ }}</td>
                  <td>{{ $item->hsn_sac }}</td>
                  <td>{{ $item->total }}</td>
                  <td>{{ $item->cgst_amount }}</td>
                  <td>{{ $item->sgst_utgst_amount }}</td>
                  <td>{{ $item->igst_amount }}</td>
                  <td width="80">{{ $total_tax = $item->cgst_amount+$item->sgst_utgst_amount+$item->igst_amount }}</td>
                </tr>
              <tr>
              <td colspan="7" style="font-weight: bold;">Tax Amount In Words - {{ amount_in_words($total_tax) }}</td>
              </tr>
              <tr>
              <td colspan="4" rowspan="2">
                <p>Bank Details:-</p>
                <p>A/c Name :-{{ $party->account_name }}</p>
                <p>A/c No- {{ $party->account_no }}</p>
                <p>Bank Name:- {{ $party->bank_name }}</p>
                <p>RTGS/NEFT IFSC Code:- {{ $party->ifsc_code }}</p>
                <p>Branch: {{ $party->branch }}</p>
              </td>
              <td colspan="3" style="text-align:center;">For Raj Marine Service Pvt. Ltd.</td>
            </tr>
            <tr>
              <td colspan="3" style="vertical-align: bottom;text-align: center;padding-top:100px;">Authorised Signatory</td>
            </tr>
            </tbody>
        </table>
@endforeach
@endif

    </div>

@if($download)
    <style>@page{body{color:#000;} table{width:100%;border:1px solid #000;border-collapse:collapse;} table tr td,table tr th{border:1px solid #000;text-align:left;font-size:12px;padding:4px;}table tr th p, table tr td p,table tr td h2,table tr th h2{margin-bottom:0px;padding-bottom:0px;padding-top:0px;margin-top:0px;}.invoice_items tr th, .invoice_items tr td{padding:4px;}.no-border,.no-border tr td,.no-border tr th{border:none !important;}</style>
@endif