@php
use Carbon\Carbon;
use App\Models\backend\BillBooking;
@endphp
@php
// dd($invoice->gst_rate);
if ($invoice->igst_total <= 0) { $cs_gst_percent='@ ' . $invoice->gst_rate / 2 . ' %';
    $st_gst_percent = '@ ' . $invoice->gst_rate / 2 . ' %';
    $igst_percent = '';
    } else {
    $igst_percent = '@ ' . $invoice->gst_rate . ' %';
    $cs_gst_percent = '';
    $st_gst_percent = '';
    }
    @endphp
    <div class="table-responsive printable mb-4">
        {{-- {{dd($party)}} --}}
        <table class="table table-bordered table-striped table-hover" border="1">
            <thead>
                <tr>
                    <td colspan="7">
                        <table width="100%" class="no-border">
                            <tr>
                                <td width="80">
                                    <img class="img-fluid for-light"
                                        src="{{ asset('public/assets/images/logo/logo.png') }}" width="80" alt="">
                                </td>
                                <td>
                                    <h2 class="text-uppercase" style="text-transform: uppercase;">3P SAP Services</h2>
                                    <p class="mb-0">Kalyan</p>
                                    <p>Tel : 55544422 Fax : 55577784</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" style="text-align:center;font-weight:bold;font-size:20px;">BANKING PAYMENT</td>
                </tr>
                <tr>
                    <td colspan="2">Banking Payment No</td>
                    <td colspan="2">PO Date</td>
                    <td colspan="3">GSTIN</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $invoice->doc_no }}</td>
                    <td colspan="2">{{ Carbon::parse($invoice->bill_date)->format('d-m-Y') }}</td>
                    <td colspan="3">{{ $party->gst_details }}</td>
                </tr>
                <tr>
                    <td colspan="3">GSTIN/UIN: {{ $party->gst_details }}</td>
                </tr>
                <tr>
                    <th>Sr.No</th>
                    <th colspan="">Doc Number</th>
                    <th colspan="3">Description</th>
                    <th colspan="2">Total - INR</th>
                </tr>
                @php
                $total_sum = 0;
                @endphp
                @if ($banking_items)
                @php $i=1; @endphp
                @foreach ($banking_items as $item)
                @php
                $bill_booking = BillBooking::where('bill_booking_id', $item->bill_booking_id)->first();
                $total_sum += $item->amount;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $bill_booking->doc_no }}</td>
                    <td colspan="3">{{ $item->description }}</td>
                    <td colspan="2">{{ $item->amount }}</td>
                </tr>
                @endforeach
                @endif
                <tr>
                    {{-- {{dd($invoice->toArray())}} --}}
                    <td colspan="6" style="text-align: right;">Total</td>
                    <td>{{ $total_sum }}</td>
                </tr>
              
                <tr>
                    <td colspan="4" rowspan="2">
  
                    </td>
                    <td colspan="3" style="text-align:center;">For 3P SAP Services.</td>
                </tr>
                <tr>
                    <td colspan="3" style="vertical-align: bottom;text-align: center;padding-top:100px;">Authorised
                        Signatory</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if ($download)
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
        }
    </style>
    @endif