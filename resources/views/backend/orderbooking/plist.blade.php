@php
use Carbon\Carbon;
use App\Models\backend\Company;
use App\Models\backend\City;
use App\Models\backend\State;
use App\Models\backend\BussinessPartnerMaster;

$company = Company::where('company_id',$purchaseorder->company_id)->first();
$bp_master = BussinessPartnerMaster::where('business_partner_id',$purchaseorder->party_id)->first();

$all_districts = City::pluck('city_name','city_id');
$all_states = State::pluck('name','id');
// dd($company);
@endphp
@php
// dd($invoice->gst_rate);
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
        {{-- {{dd($party)}} --}}
        <table class="table table-bordered table-striped table-hover" border="1">
            <thead>
                <tr>
                    <td colspan="13">
                        <table width="100%" class="no-border">
                            <tr>
                                <td width="80">
                                    <img src="{{asset('public/backend-assets/images/'.$company->company_logo)}}"
                                        width="80" style="padding-left:10px;padding-top:10px;float:left" />
                                </td>
                                <td>
                                    <h2 class="text-uppercase" style="text-transform: uppercase;">{{$company->name}}
                                    </h2>
                                    <p class="mb-0">
                                        @php
                                        $districts = City::where('city_id',$company->district)->first();
                                        @endphp
                                        {{$districts->city_name}}
                                    </p>
                                    <p>Tel : {{$company->mobile_no??''}} GST: {{$company->gstno??''}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
                @if(!empty($invoice->get_bill_toaddress))

                <td colspan="13">

                    <p style="text-decoration: underline;text-align:center">Detail of Recipient / Billed To</p>
                    Name &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp &nbsp;<b>: &nbsp;&nbsp;
                        {{$bp_master->bp_name}}</b><br /><br />
                    Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>: &nbsp;&nbsp;
                        {{$invoice->get_bill_toaddress->building_no_name. " ,".$invoice->get_bill_toaddress->street_name
                        ."
                        ,". $invoice->get_bill_toaddress->landmark
                        ." ,". $invoice->get_bill_toaddress->city }}
                        {{$all_districts[$invoice->get_bill_toaddress->district]." -
                        ".$invoice->get_bill_toaddress->pin_code}}
                        {{$all_states[$invoice->get_bill_toaddress->state]}}</b> <br /><br />
                    GSTIN NO.&nbsp;&nbsp; <b>:&nbsp;&nbsp&nbsp; {{ $invoice->bill_to_gst_no }}</b>

                </td>
                @endif

                <tr>
                    <td colspan="13" style="text-align:center;font-weight:bold;font-size:20px;">Pick List</td>
                </tr>
                <tr>
                    <td colspan="4">Sale Order No</td>
                    <td colspan="4">Sale Order Date</td>
                    <td colspan="5">GSTIN</td>
                </tr>
                <tr>
                    <td colspan="4">{{$invoice->bill_no}}</td>
                    <td colspan="4">{{ Carbon::parse($invoice->bill_date)->format('d-m-Y')}}</td>
                    <td colspan="5">{{ $invoice->bill_to_gst_no }}</td>
                </tr>
                <tr>
                    <td colspan="4">GSTIN/UIN: {{ $invoice->bill_to_gst_no }}</td>
                    <td colspan="4">Ship From</td>
                    <td colspan="5">{{ $invoice->get_ship_toaddress->bp_address_name??'' }}</td>
                </tr>
                <tr>
                    <th>SKU</th>
                    <th colspan="2">Description</th>
                    <th>Brand</th>
                    <th>Format</th>
                    <th>Variant</th>
                    <th>Mrp</th>
                    <th>Total Order<br>/Qty (Units)</th>
                    <th>C/S<br> (cases)</th>
                    <th>Units</th>
                    <th>Total</th>
                    <th>Pick<br> Cases</th>
                    <th>Pick<br> Units</th>
                </tr>
                @php
                $total_sum = 0;
                @endphp
                @if($invoice->purchaseorder_items)
                @php $i=1; @endphp
                @foreach($invoice->purchaseorder_items as $item)
                @php
                $total_sum += (int) $item->total ;
                @endphp
                <tr>
                    @php
                    $qty_cs = floor($item->final_qty / ($item->get_product->unit_case *
                    $item->get_product->dimensions_unit_pack));
                    $qty_units = $item->final_qty- (floor($item->final_qty / ($item->get_product->unit_case *
                    $item->get_product->dimensions_unit_pack))
                    * ($item->get_product->unit_case * $item->get_product->dimensions_unit_pack));
                    @endphp

                    <td>{{ $item->sku }}</td>
                    <td colspan="2">{{ $item->item_name }}</td>
                    <td>{{ $item->get_product->brand->brand_name }}</td>
                    <td>{{ $item->get_product->sub_category->subcategory_name }}</td>
                    <td>{{ $item->get_product->variants->name }}</td>
                    <td>{{ $item->get_product->mrp }}</td>
                    <td>{{$item->final_qty}}</td>
                    <td>{{$qty_cs}}</td>
                    <td>{{$qty_units}}</td>
                    <td>{{ $item->total }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
                @endif

                <tr>
                    <td colspan="8" rowspan="2">

                    </td>
                    <td colspan="5" style="text-align:center;">For {{$company->name}}</td>
                </tr>
                <tr>
                    <td colspan="5" style="vertical-align: bottom;text-align: center;padding-top:100px;">Authorised
                        Signatory</td>
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