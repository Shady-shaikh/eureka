@extends('backend.layouts.app')
@section('title', 'A/P Invoice')

<style>
    tr td {
        background-color: #ffffff;
    }

    tr.odd td {
        background-color: #f9f9f9;
    }


    thead th {
        background-color: white;
    }

    #tbl-datatable {
        font-size: 12px;
    }

    .headings {
        font-size: 13px !important;
    }
</style>

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">A/P Invoice</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">A/P Invoice</li>
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
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="row">
                        </div>
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr class="headings">
                                        <th>#</th>
                                        <th>Date
                                            <div class="my-1">
                                                <input type="date" id="date">
                                            </div>
                                        </th>
                                        {{-- <th></th> --}}
                                        <th>Invoice No
                                            <div class="my-1">
                                                <input type="text" id="purchase_order_no">
                                            </div>
                                        </th>
                                        {{-- <th>Vendor Invoice Number </th> --}}
                                        <th>Vendor PO Refrence Number
                                            <div class="my-1">
                                                <input type="text" id="vendor_po_ref_num">
                                            </div>
                                        </th>
                                        <th>Vendor Invoice Number
                                            <div class="my-1">
                                                <input type="text" id="vendor_inv_no">
                                            </div>
                                        </th>
                                        <th>Invoice Number
                                            <div class="my-1">
                                                <input type="text" id="ap_inv_no">
                                            </div>
                                        </th>

                                        <th>Invoice For
                                            <div class="my-1">
                                                <input type="text" id="invoice_for">
                                            </div>
                                        </th>
                                        <th>Status
                                            <div class="my-1">
                                                <div class="dropdown">
                                                    <select name="" id="status"
                                                        class="btn btn-sm border border-dark dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <option value="">Select</option>
                                                        <option value="open">Open</option>
                                                        <option value="close">Close</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </th>
                                        <th>Action
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
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
</div>
</div>
@section('scripts')


<script>
    $(function() {

            var table = $('#tbl-datatable').DataTable({

                scrollY: 600,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns:   {
                    leftColumns: 0,
                    rightColumns: 1,
                },

                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.apinvoice') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'bill_date',
                        name: 'bill_date'
                    },
         
                    {
                        data: 'bill_no',
                        name: 'bill_no'
                    },
                    {
                        data: 'vendor_ref_no',
                        name: 'vendor_ref_no'
                    },
                    {
                        data: 'vendor_inv_no',
                        name: 'vendor_inv_no'
                    },
                    {
                        data: 'ap_inv_no',
                        name: 'ap_inv_no'
                    },
                  
                    {
                        data: 'get_partyname.bp_name',
                        name: 'get_partyname.bp_name'
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
                                columns: [0, 2, 3, 4, 5, 6, 7, 8],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'A/P INVOICE';
                                return pageTitle
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 2, 3, 4, 5, 6],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'A/P INVOICE';
                                return pageTitle
                            }
                        },
                        
                    ]
                }],
                dom: 'lBfrtip',
                select: true
            });

            $('#purchase_order_no').on('keyup', function() {
                table.column(2).search(this.value).draw();
                // console.log(v);
            });

            $('#vendor_po_ref_num').on('keyup', function() {
                table.column(3).search(this.value).draw();
            });
            $('#vendor_inv_no').on('keyup', function() {
                table.column(4).search(this.value).draw();
            });
            $('#ap_inv_no').on('keyup', function() {
                table.column(5).search(this.value).draw();
            });

            // var flag = false;
            $('#date', this).on('keyup change', function() {
                var val = $(this).val();
                var date = new Date(val);
                var newDate = date.toString('dd-MM-yy');
                console.log(newDate);
                table.column(1).search(this.value).draw();
            });

            $('#invoice_for').on('keyup', function() {
                table.column(6).search(this.value).draw();
            });

            $('#status').on('change', function() {
                table.column(7).search(this.value).draw();
            });
        });
</script>
@endsection
@endsection