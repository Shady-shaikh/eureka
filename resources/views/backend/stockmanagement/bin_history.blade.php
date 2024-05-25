@extends('backend.layouts.app')
@section('title', 'Bin Transfer History')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Bin Transfer History</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Bin Transfer History</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary" href="{{ route('admin.stockmanagement') }}">
                        Back
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
                        <h4 class="card-title">Bin Transfer History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>From (Warehouse/Bin)</th>
                                            <th>To (Warehouse/Bin)</th>
                                            <th>Base Pack</th>
                                            <th>Item Description</th>
                                            <th>Item Code</th>
                                            <th>Available Quantity</th>
                                            <th>Transferred Quantity</th>
                                            <th>Date</th>
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
                                                    <td>{{ $row->get_from_warehouse_name->storage_location_name }} / {{$row->get_from_bin_name->get_bin->name??''}}</td>
                                                    <td>{{ $row->get_to_warehouse_name->storage_location_name }} / {{$row->get_to_bin_name->get_bin->name??''}}</td>
                                                    <td>{{$row->sku}}</td>
                                                    <td>{{$row->item_name}}</td>
                                                    <td>{{$row->item_code}}</td>
                                                    <td>{{$row->from_qty}}</td>
                                                    <td>{{$row->qty}}</td>
                                                    <td>{{$row->created_at}}</td>

                                              
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
