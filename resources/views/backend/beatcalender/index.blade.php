@extends('backend.layouts.app')
@section('title', 'Beat Calendar')

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Beat Calendar</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Beat Calendar</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                @can('Create Beat Calender Master')
                <a class="btn btn-outline-primary" href="{{ route('admin.beatcalender.create') }}">
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
                    <h4 class="card-title">Beat Calendar</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Date</th>
                                        <th>Beat</th>
                                        <th>Day</th>
                                        <th>Week</th>
                                        <th>Month</th>
                                        <th>Year</th>
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
                                                <input type="date" name="date" id="date">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('beat_id', $beats, null, ['id' => 'beat_id',
                                                    'placeholder' => 'Select Beat']) !!}
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('beat_day', $all_data['days'], null, ['id' =>
                                                    'beat_day', 'placeholder' => 'Select Day']) !!}
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('beat_week', $all_data['week'], null, ['id' =>
                                                    'beat_week', 'placeholder' => 'Select Week']) !!}
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('beat_month', $all_data['month'], null, [
                                                    'id' => 'beat_month',
                                                    'placeholder' => 'Select Month',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    {!! Form::select('beat_year', $all_data['year'], null, ['id' =>
                                                    'beat_year', 'placeholder' => 'Select Year']) !!}
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
            var daysOrder = @json(array_values($all_data['days']));
            var monthsOrder = @json(array_values($all_data['month']));

            $('.dropdown-toggle').dropdown();

            var table = $('#tbl-datatable').DataTable({
                orderable: true,
                searchable: true,
                ajax: "{{ route('admin.beatcalender') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'get_beat.beat_name',
                        name: 'get_beat.beat_name'
                    },
                    {
                        data: 'beat_day',
                        name: 'beat_day'
                    },
                    {
                        data: 'beat_week',
                        name: 'beat_week'
                    },
                    {
                        data: 'beat_month',
                        name: 'beat_month'
                    },
                    {
                        data: 'beat_year',
                        name: 'beat_year'
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
                                columns: [0,1, 2, 3, 4, 5,6],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'Beat Calendar';
                                return pageTitle
                            }
                        },
                       

                    ]
                }],
                dom: 'lBfrtip',
                select: true,
                columnDefs: [{
                        targets: 2, // Column index for "Day"
                        type: 'days-order' // Custom sorting type for days
                    },
                    {
                        targets: 4, // Column index for "Month"
                        type: 'months-order' // Custom sorting type for months
                    }
                ]
            });

            // Custom sorting functions for days and months
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                'days-order-pre': function(a) {
                    // Define the sorting order for days
                    return daysOrder.indexOf(a);
                },
                'days-order-asc': function(a, b) {
                    return a - b;
                },
                'days-order-desc': function(a, b) {
                    return b - a;
                },
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

            $('#date,#beat_id,#beat_day, #beat_week, #beat_month, #beat_year').on('change', function() {
                var columnIndex = $(this).closest('th').index(); // Get the column index of the changed dropdown
                var filterValue = $(this).find(':selected').text();
                table.column(columnIndex).search(filterValue).draw();
            });
            
            $('#date', this).on('change', function() {
                var val = $(this).val();
                var dateParts = val.split('-');
                
                // Construct a new date string in yyyy-mm-dd format
                var newDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                
                // Filter the DataTable column based on the formatted date
                table.column(1).search(newDate).draw();
            });


        });
</script>
@endsection