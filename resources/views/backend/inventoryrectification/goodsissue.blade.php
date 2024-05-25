@extends('backend.layouts.app')
@section('title', 'Goods Issue')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Goods Issue</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Goods Issue</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ route('admin.goodsissue.create') }}">
                        Add Goods Issue
                    </a>

                </div>
            </div>
        </div>
    </div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Goods Receipt</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Warehouse</th>
                                            <th>Bacth Number</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>HSN/SAC</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                            <th>Tax Amount</th>
                                            <th>Total</th>
                                            <th>Gross Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($data) && count($data) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($data as $row)
                                            @php
                                                // dd($row->get_from_bin_name->get_bin->name);
                                            @endphp
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <td>{{ $row->get_warehouse_name->storage_location_name }}</td>
                                                    <td>{{$row->batch_no}}</td>
                                                    <td>{{$row->item_code}}</td>
                                                    <td>{{$row->item_name}}</td>
                                                    <td>{{$row->hsn_sac}}</td>
                                                    <td>{{$row->taxable_amount}}</td>
                                                    <td>{{$row->final_qty}}</td>
                                                    <td>{{$row->discount ?? 0}}</td>
                                                    <td>{{$row->gst_amount}}</td>
                                                    <td>{{$row->total}}</td>
                                                    <td>{{$row->gross_total}}</td>

                                              
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
@endsection