@extends('backend.layouts.app')
@section('title', 'Partner Ledger')

@section('content')
@php
use App\Models\backend\City;

@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Partner Ledger</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Partner Ledger</li>
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
                    <h4 class="card-title">Partner Ledger</h4>
                </div>

                <form action="{{ route('admin.partnerledger') }}" method="get">
                    <div class="form-group ml-2">
                        <label for="date_range">Filter By Dates: </label>
                        <input type="text" name="daterange" value="" />
                        <input type="hidden" name="from_date" value="" />
                        <input type="hidden" name="to_date" value="" />

                        <button class="btn-sm btn-secondary reset">Reset</button>
                    </div>

                </form>



                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Doc. Type</th>
                                        <th>Document No.</th>
                                        <th>Customer Code</th>
                                        <th>Customer Name</th>
                                        <th>Base Value</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>IGST</th>
                                        <th>GST Total</th>
                                        <th>Gross Value</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data) && count($data) > 0)
                                    @php $srno = 1; @endphp
                                    @foreach ($data as $row)


                                    <tr>
                                        <td>{{ $srno }}</td>
                                        <td>{{ $row->transaction_type }}</td>
                                        <td>{{ $row->doc_no}}</td>
                                        <td>{{ $row->party_id}}</td>
                                        <td>{{ $row->get_partner->bp_name }}</td>
                                        <td>{{ $row->total }}</td>
                                        <td>{{ round($row->cgst_amount ,2)}}</td>
                                        <td>{{ round($row->sgst_utgst_amount ,2)}}</td>
                                        <td>{{ round($row->igst_amount ,2)}}</td>
                                        <td>{{ $row->gst_amount }}</td>
                                        <td>{{ $row->gross_total }}</td>
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

            // Handle cancel button click
            $(".cancelBtn").click(function() {
                window.location.reload();
            });


            $('.reset').on('click', function() {
                // Clear the date range
                $('input[name="daterange"]').val('');

                // Optionally trigger the change event to apply the changes
                $('input[name="daterange"]').trigger('change');
            });
        });
</script>


@endsection