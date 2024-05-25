@extends('backend.layouts.app')
@section('title', 'Set Price')


@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Set Price For: {{ $pricings->pricing_name }}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Set Price</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            {{-- <a href="{{ asset('public/sheets/sample.xlsx') }}" class="btn btn-outline-primary">Sample Sheet</a>
            --}}
            <a href="{{ route('admin.sellingpricing') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                        @if(Auth()->guard('admin')->user()->role != 41)

                        @include('backend.includes.errors')
                        {{ Form::open(['url' => 'admin/updatepricings/update', 'class' => 'w-100', 'enctype' =>
                        'multipart/form-data']) }}
                        {{ Form::hidden('pricing_master_id', $pricings->pricing_master_id, ['class' => 'form-control'])
                        }}

                        <div class="row">

                            <div class="col-md-5"></div>

                            <div class="col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('file', 'Import Data From File') }}
                                    <input type="file" name="file" class="form-control">

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <br style="margin-top:5px;">
                                    {{ Form::submit('Import', ['class' => 'btn btn-primary ']) }}

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                            </div>

                        </div>

                        {{ Form::close() }}
                        <hr>

                        @endif


                        <table class="table table-bordered table-striped" id="tbl-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Base Pack</th>
                                    <th>Item Code</th>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Format</th>
                                    <th>Variant</th>
                                    <th>
                                        @if($pricings->pricing_type == 'sale')
                                        Selling Price
                                        @else
                                        Purchase Price
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- @if (!$products->isEmpty())
                                @foreach ($products as $row)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $row->sku }}</td>
                                    <td>{{ $row->item_code }}</td>
                                    <td>{{ $row->consumer_desc }}</td>
                                    <td>{{ $row->brand->brand_name }}</td>
                                    <td>{{ $row->category->category_name }}</td>
                                    <td>{{ $row->sub_category->subcategory_name }}</td>
                                    <td>{{ $row->variants->name }}</td>
                                    <td>
                                        <input type="number" name="selling_price" value="{{ $row->selling_price??0 }}">
                                    </td>
                                </tr>
                                @endforeach

                                @endif --}}

                            </tbody>
                        </table>


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


<script>
    var table = $('#tbl-datatable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.pricings.setpricing', ['id' => request('id')]) }}", // Replace with your server-side endpoint
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'sku',
                    name: 'sku'
                },
                {
                    data: 'item_code',
                    name: 'item_code'
                },
                {
                    data: 'consumer_desc',
                    name: 'consumer_desc'
                },
                {
                    data: 'brand.brand_name',
                    name: 'brand.brand_name'
                },
                {
                    data: 'category.category_name',
                    name: 'category.category_name'
                },
                {
                    data: 'sub_category.subcategory_name',
                    name: 'sub_category.subcategory_name'
                },
                {
                    data: 'variants.name',
                    name: 'variants.name'
                },
                {
                    data: 'selling_price',
                    name: 'selling_price',
                    render: function(data, type, row) {
                        return data==0?0:data;
                        // return '<input type="number" name="selling_price" class="selling-price-input" value="' +
                        //     data + '">';
                    }
                },
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, 2, 3, 4, 5, 6, 7, 8],
                    modifier: {
                        page: 'all',
                        search: 'applied'
                    }
                },
                title: 'Product Data',
            }],
            dom: 'lBfrtip',
            select: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [
                [-1],
                ['All']
            ],
        });
</script>

@endsection