@extends('backend.layouts.app')
@section('title', 'Focus Pack (Must Sell Pack)')

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Focus Pack (Must Sell Pack)</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Focus Pack (Must Sell Pack)</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                @can('Create Beat Calender Master')
                <a class="btn btn-outline-primary" href="{{ route('admin.focusmap.create') }}">
                    <i class="feather icon-plus"></i> Add
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>


<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Focus Pack (Must Sell Pack)</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Brand</th>
                                        <th>Format</th>
                                        <th>Product</th>
                                        <th>Month</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                {!! Form::text('brand_id', null, [
                                                'id' => 'brand_id',
                                                ]) !!}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                {!! Form::text('format_id', null, [
                                                'id' => 'format_id',
                                                ]) !!}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                {!! Form::text('product_id', null, [
                                                'id' => 'product_id',
                                                ]) !!}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('month', $all_data['month'], null, [
                                                    'id' => 'month',
                                                    'placeholder' => 'Select Month',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
                                        </th>

                                    </tr>
                                </thead>

                                <tbody></tbody>


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


<script>
    $(function() {
            var monthsOrder = @json(array_values($all_data['month']));

            $('.dropdown-toggle').dropdown();

            var table = $('#tbl-datatable').DataTable({
                orderable: true,
                searchable: true,
                ajax: "{{ route('admin.focusmap') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'brand_id',
                        name: 'brand_id'
                    },
                    {
                        data: 'format_id',
                        name: 'format_id'
                    },
                    {
                        data: 'product_id',
                        name: 'product_id'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: [0,1, 2, 3, 4],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'Focus Map (Must Sell Pack)';
                                return pageTitle
                            }
                        },
                       

                    ]
                }],
                dom: 'lBfrtip',
                select: true,
                columnDefs: [
                    {
                        targets: 4, // Column index for "Month"
                        type: 'months-order' // Custom sorting type for months
                    }
                ]
            });

            // Custom sorting functions for days and months
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                
                'months-order-pre': function(a) {
                    // Define the sorting order for months
                    return monthsOrder.indexOf(a);
                },
                'months-order-asc': function(a, b) {
                    return a - b;
                },
                'months-order-desc': function(a, b) {
                    return b - a;
                }
            });

            function applySearch(columnIndex, value) {
                table.column(columnIndex).search(value).draw();
            }

            $('#brand_id,#format_id,#product_id').on('keyup', function() {
                var columnIndex = $(this).closest('th').index();
                applySearch(columnIndex, this.value);
            });


            $('#month').on('change', function() {
                var columnIndex = $(this).closest('th').index(); // Get the column index of the changed dropdown
                var filterValue = $(this).find(':selected').text();
                table.column(columnIndex).search(filterValue).draw();
            });
        });
</script>
@endsection