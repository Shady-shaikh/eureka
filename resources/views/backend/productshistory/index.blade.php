@extends('backend.layouts.app')
@section('title', 'Products History')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Products History (Till Last Year)</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Products History</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            </div>
        </div>
    </div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Products History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="no_export">Image</th>
                                            <th>Item Type</th>
                                            <th>Item Code</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Date & Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($products) && count($products) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <td class="no_export">
                                                        @if (!empty($product->product_thumb))
                                                            <a href="{{ asset('public/backend-assets/images/') }}/{{ $product->product_thumb }}"
                                                                target="_blank"><img class="card-img-top img-fluid mb-1"
                                                                    src="{{ asset('public/backend-assets/images/') }}/{{ $product->product_thumb }}"
                                                                    alt="Product Image" style="width:50px"></a>
                                                        @endif
                                                    </td>
                                                    </td>
                                                    <td>{{ isset($product->item_type) ? $product->item_type->item_type_name : '' }}
                                                    </td>
                                                    <td>{{ $product->item_code }}</td>
                                                    <td>{{ $product->consumer_desc }}</td>
                                                    <td>{{ isset($product->brand) ? $product->brand->brand_name : '' }}</td>
                                                    <td>{{ isset($product->category) ? $product->category->category_name : '' }}
                                                    </td>

                                                    <td>{{ $product->created_at }}</td>
                                                    <td>
                                                        {{-- revision show btn --}}

                                                        @can('View Products History')
                                                            <a href="{{ url('admin/productshistory/show/' . $product->product_revision_id) }}"
                                                                class="btn btn-primary" title="Edit"><i
                                                                    class="feather icon-eye"></i></a>
                                                        @endcan

                                                    </td>
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

@endsection


@section('scripts')
@include('backend.export_pagination_script')
@endsection