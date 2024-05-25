@extends('backend.layouts.app')
@section('title', 'Bill Booking')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Bill Booking</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Bill Booking</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    @can('Create Bill Booking')
                        <a class="btn btn-outline-primary" href="{{ route('admin.billbooking.create') }}">
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
                        <h4 class="card-title">Bill Booking</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            {{--  {{ dd($details->toArray()) }}  --}}
                            <div class="table-responsive">

                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                
                                            <th>Date</th>
                                            <th>Bill To</th>
                                            <th>Doc Number</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <thead id="search_input">
                                        <tr>
                                            <th></th>
                        
                                            <th><input type="date" id="posting_date"></th>
                                            <th><input type="text" id="bill_to"></th>
                                            <th><input type="text" id="doc_no"></th>
                                            <th>
                                                <div class="dropdown">
                                                    <select name=""
                                                        id="type"class="btn btn-sm border border-dark dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <option value="">Select</option>
                                                        <option value="invoice">Invoice</option>
                                                        <option value="expense">Expense</option>
                                                    </select>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="dropdown">
                                                    <select name=""
                                                        id="status"class="btn btn-sm border border-dark dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <option value="">Select</option>
                                                        <option value="open">Open</option>
                                                        <option value="close">Close</option>
                                                    </select>
                                                </div>
                                            </th>
                                            <th>

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
    <script>
        $(function() {

            var table = $('#tbl-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.billbooking') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'bill_date',
                        name: 'bill_date'
                    },
 
                    {
                        data: 'get_partyname.bp_name',
                        name: 'get_partyname.bp_name'
                    },
                    {
                        data: 'doc_no',
                        name: 'doc_no'
                    },
               
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: false
                    }
                ],
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1,2, 3, 4, 5],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'BILL BOOKING';
                                return pageTitle
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1,2, 3, 4, 5],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'BILL BOOKING';
                                return pageTitle
                            }
                        },
                      
                    ]
                }],
                dom: 'lBfrtip',
                select: true
            });

            $('#bill_to').on('keyup', function() {
                table.column(2).search(this.value).draw();
                // console.log(v);
            });

            $('#doc_no').on('keyup', function() {
                table.column(3).search(this.value).draw();
            });
            // var flag = false;
            $('#posting_date', this).on('keyup change', function() {
                var val = $(this).val();
                var date = new Date(val);
                var newDate = date.toString('dd-MM-yy');
                console.log(newDate);
                table.column(1).search(this.value).draw();

            });
            $('#type').on('click', function() {
                table.column(4).search(this.value).draw();
            });
            $('#status').on('change', function() {
                table.column(5).search(this.value).draw();
            });

        });
    </script>
@endsection
