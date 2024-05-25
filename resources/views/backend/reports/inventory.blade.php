@extends('backend.layouts.app')
@section('title', 'Inventory Transactions')

@section('content')
@php
use App\Models\backend\City;
use App\Models\backend\Company;

@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Inventory Transactions</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Inventory Transactions</li>
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
                    <h4 class="card-title">Inventory Transactions</h4>
                </div>

                <form action="{{ route('admin.reports') }}" method="get">
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
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Doc Number</th>
                                        <th>Transaction Type</th>
                                        <th>Master DB/ DB Name</th>
                                        <th>DB Type</th>
                                        <th>City</th>
                                        <th>Region</th>
                                        <th>Item Code</th>
                                        <th>Item Description</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Format</th>
                                        <th>Variant</th>
                                        <th>EAN Code</th>
                                        <th>Product</th>
                                        <th>Warehouse (Bin)</th>
                                        <th>Month</th>
                                        <th>Quantity</th>
                                        <th>Updated Quantity</th>
                                        <th>Final Quantity</th>
                                        <th>Mfg Date</th>
                                        <th>Exp Date</th>
                                        <th>Days To Expire</th>
                                        <th>Freshness (%)</th>
                                        <th>Stock Status</th>
                                        <th>Billing Price/ Unit (W/O Gst)</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data) && count($data) > 0)
                                    @php $srno = 1; @endphp
                                    @foreach ($data as $row)
                                    @php
                                    // dd($row->get_unit_price->variants->name);
                                    @endphp

                                    @php

                                    $mfgDate = $row->manufacturing_date ?
                                    \Carbon\Carbon::parse($row->manufacturing_date) : null;
                                    $expiryDate = $row->expiry_date ? \Carbon\Carbon::parse($row->expiry_date) : null;
                                    $daysRemaining = 0;
                                    $freshnessPercentage = 0;

                                    if ($expiryDate && $mfgDate) {
                                    $daysRemaining = now()->diffInDays($expiryDate);
                                    $total_days = $expiryDate->diffInDays($mfgDate);
                                    $freshnessPercentage = round(($daysRemaining / $total_days) * 100,2);
                                    }

                                    if ($expiryDate < now()) { $daysRemaining=0; $freshnessPercentage=0; } @endphp <tr>
                                        <td>{{ $srno }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('H:i:s') }}</td>
                                        <td>{{ $row->doc_no }}</td>
                                        <td>{{ $row->transaction_type }}</td>
                                        <td>{{ $row->get_company->name??'' }}</td>
                                        <td>{{ $row->get_company->db_type??'' }}</td>
                                        <td>{{ $row->get_company->city??'' }}</td>
                                        <td>
                                            @php
                                            $districts =
                                            City::where('city_id',$row->get_company->district??'')->first();
                                            @endphp
                                            {{ isset($districts)?$districts->city_name:'' }}</td>
                                        <td>{{ $row->item_code }}</td>
                                        <td>{{ $row->get_unit_price->product_desc??'' }}</td>
                                        <td>{{ $row->get_unit_price->brand->brand_name??'' }}</td>
                                        <td>{{ $row->get_unit_price->category->category_name??'' }}</td>
                                        <td>{{ $row->get_unit_price->sub_category->subcategory_name??'' }}</td>
                                        <td>{{ $row->get_unit_price->variants->name??'' }}</td>
                                        <td>{{ $row->get_unit_price->ean_barcode??'' }}</td>
                                        <td>{{ $row->get_unit_price->consumer_desc??'' }}</td>
                                        <td>{{ $row->get_warehouse->storage_location_name }}
                                            ({{ $row->get_bin->get_bin->name ?? '' }})
                                        </td>
                                        <td>{{ $row->created_at->format('M \'Y') }}</td>
                                        <td>{{ abs($row->qty) }}</td>
                                        <td>{{ $row->updated_qty }}</td>
                                        <td>{{ $row->final_qty<0?0:$row->final_qty }}</td>

                                        <td style="font-size: 11px;">{{ $row->manufacturing_date ?? '' }}</td>
                                        <td style="font-size: 11px;">{{ $row->expiry_date ?? '' }}</td>
                                        <td>{{ $daysRemaining ?? '' }}</td>
                                        <td>{{ $freshnessPercentage ?? '' }}%</td>
                                        <td></td>

                                        <td>{{ $row->unit_price }}</td>



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