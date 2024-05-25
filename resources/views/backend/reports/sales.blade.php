@extends('backend.layouts.app')
@section('title', 'Sales Report')

@section('content')
@php
use Carbon\Carbon;
use App\Models\backend\Company;

@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Sales Report</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Sales Report</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">

            </div>
        </div>
    </div>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sales Report</h4>
                </div>

                <form action="{{ route('admin.sales') }}" method="get">

                    <div class="row ml-2 ">
                        @if (is_superAdmin())
                        <div class="col-md-3 col-sm-12 ">
                            <div class="form-group">
                                {{ Form::label('company_id', 'Distributor *') }}
                                {{ Form::select('company_id', Company::pluck('name','company_id'),
                                $_GET['company_id']??null, [
                                'class' => 'form-control ',
                                'placeholder' => 'Select Company',
                                ]) }}
                            </div>
                        </div>
                        @endif

                        <div class="col-md-3 col-sm-12 ">
                            <div class="form-group">
                                <label for="date_range">Filter By Dates: </label>
                                <input type="text" class="form-control" name="daterange" value="" />
                                <input type="hidden" name="from_date" value="" />
                                <input type="hidden" name="to_date" value="" />

                            </div>
                        </div>

                        <div class="" style="height:30px;margin:auto 0px;">
                            <button class="btn-sm btn-secondary reset">Reset</button>
                        </div>
                    </div>

                </form>

                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>DT Code</th>
                                        <th>DT Name</th>
                                        <th>Customer Name</th>
                                        <th>Customer City</th>
                                        <th>Customer Type</th>
                                        <th>Region</th>
                                        <th>Country</th>
                                        <th>Invoice No.</th>
                                        <th>Invoice Date</th>
                                        <th>Month</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Base Pack code</th>
                                        <th>Item Code</th>
                                        <th>Desc.</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Format</th>
                                        <th>Variant</th>
                                        <th>EAN Code</th>
                                        <th>Product</th>
                                        <th>Combi Type</th>
                                        <th>Combi Type Int.</th>
                                        <th>MRP</th>
                                        <th>Unit/Case</th>
                                        <th>Qty(Case)</th>
                                        <th>Qty(Pcs)(System)</th>
                                        <th>Qty(Pcs)(Single Units)</th>
                                        <th>NSV/Unit</th>
                                        <th>Net Value</th>
                                        <th>Discount</th>
                                        <th>GST</th>
                                        <th>Gross Value</th>
                                        <th>Net Value (Lacs)</th>
                                        <th>MRP Value (INR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data) && count($data) > 0)
                                    @php $srno = 1; @endphp
                                    @foreach ($data as $row)
                                    @php
                                    $qty_cs = floor($row->qty / ($row->get_product->unit_case *
                                    $row->get_product->dimensions_unit_pack));
                                    $qty_units = $row->qty- (floor($row->qty / ($row->get_product->unit_case *
                                    $row->get_product->dimensions_unit_pack))
                                    * ($row->get_product->unit_case * $row->get_product->dimensions_unit_pack));
                                    @endphp
                                    <tr>
                                        <td>{{ $srno }}</td>
                                        <td>{{$row->get_ar->get_company->company_id??''}}</td>
                                        <td>{{$row->get_ar->get_company->name??''}}</td>
                                        <td>{{ $row->get_ar->get_partyname->bp_name??'' }}</td>
                                        <td>{{ $row->get_ar->get_partyname->get_address->city??'' }}</td>
                                        <td>{{$row->get_ar->get_partyname->get_category->business_partner_category_name??''}}
                                        </td>
                                        <td>{{ $row->get_ar->get_company->get_zone->zone_name??'' }}</td>
                                        <td>{{
                                            isset($row->get_ar->get_partyname->get_company->country)?$all_country[$row->get_ar->get_partyname->get_company->country]:''
                                            }}</td>
                                        <td>{{ $row->get_ar->bill_no??'' }}</td>
                                        <td>{{
                                            isset($row->get_ar->bill_date)?Carbon::parse($row->get_ar->bill_date)->format('d-m-Y'):''
                                            }}</td>
                                        <td>{{ isset($row->get_ar->created_at)?$row->get_ar->created_at->format('M'):''
                                            }}</td>
                                        @php
                                        $quarter = ceil(date('n', strtotime($row->get_ar->created_at??'')) / 3); //
                                        $quarter_str = 'Q' . $quarter;
                                        // Getting the last two digits of the year
                                        $year = date('y', strtotime($row->get_ar->created_at??''));
                                        $quarter_result = $quarter_str . " '" . $year;

                                        @endphp
                                        <td>{{ $quarter_result }}</td>
                                        <td>{{ isset($row->get_ar->created_at)?$row->get_ar->created_at->format('Y'):''
                                            }}</td>
                                        <td>{{ $row->sku }}</td>
                                        <td>{{ $row->item_code }}</td>
                                        <td>{{ $row->get_product->consumer_desc }}</td>
                                        <td>{{ $row->get_product->brand->brand_name }}</td>
                                        <td>{{ $row->get_product->category->category_name }}</td>
                                        <td>{{ $row->get_product->sub_category->subcategory_name }}</td>
                                        <td>{{ $row->get_product->variants->name }}</td>
                                        <td>{{ $row->get_product->ean_barcode }}</td>
                                        @php
                                        $product = $row->get_product->brand->brand_name." ".
                                        $row->get_product->sub_category->subcategory_name." ".
                                        $row->get_product->variants->name;
                                        @endphp
                                        <td>{{ $product }}</td>
                                        <td>{{ $row->get_product->get_combi_type->name??'' }}</td>
                                        <td>{{ $row->get_product->combi_type_int??1 }}</td>
                                        <td>{{ $row->get_product->mrp }}</td>
                                        <td>{{ $row->get_product->unit_case }}</td>
                                        <td>{{ $qty_cs }}</td>
                                        <td>{{ $row->final_qty ?? $row->qty }}</td>
                                        <td>
                                            {{ ($qty_units * intval($row->get_product->combi_type_int??1) ) }}
                                        </td>

                                        <td>
                                            @php
                                            $divisor = $qty_units * (isset($row->get_product) ?
                                            intval($row->get_product->combi_type_int ?? 1) : 1);
                                            $result = $divisor != 0 ? round($row->total / $divisor, 2) : 'N/A'; //
                                            @endphp
                                            {{ $row->taxable_amount }}
                                        </td>

                                        <td>{{$row->total}}</td>
                                        <td>{{$row->get_ar->discount??0}} %</td>
                                        <td>{{$row->gst_amount}}</td>
                                        <td>{{$row->gross_total}}</td>
                                        @php
                                        $net_val_lac = $row->total / pow(10, 5);
                                        @endphp
                                        <td>{{ round($net_val_lac,2)}}</td>
                                        <td>{{$qty_units !=0 ? $qty_units * $row->get_product->mrp :
                                            $row->get_product->mrp}}</td>



                                    </tr>
                                    @php $srno++; @endphp
                                    @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>

@endsection
@section('scripts')

@include('backend.export_pagination_script')

<script>
    $(document).ready(function() {            

        const from_date_param = new URLSearchParams(window.location.search).get('from_date');
        const to_date_param = new URLSearchParams(window.location.search).get('to_date');

        // Initialize date range picker
        $('input[name="daterange"]').daterangepicker({
            startDate: from_date_param ? moment(from_date_param, 'YYYY-MM-DD') : moment().subtract(1,
                'M'),
            endDate: to_date_param ? moment(to_date_param, 'YYYY-MM-DD') : moment(),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        // Handle form submission when date range is selected
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            // Update the hidden input fields with the selected date range
            $('input[name="from_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            $('input[name="to_date"]').val(picker.endDate.format('YYYY-MM-DD'));

            // Submit the form
            $('form').submit();
        });

        $('#company_id').on('change', function() {
            if($(this).val()){
                $('form').submit();
            }
        });

        // Handle cancel button click
        $(".cancelBtn").click(function() {
            window.location.reload();
        });


        $('.reset').on('click', function() {

            $('#company_id').val('');
            // Clear the date range
            $('input[name="daterange"]').val('');

            // Optionally trigger the change event to apply the changes
            $('input[name="daterange"]').trigger('change');
        });
    });
</script>



@endsection