@extends('backend.layouts.app')
@section('title', 'Goods/Service Receipts')




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
        <h3 class="content-header-title">Goods/Service Receipts</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Goods/Service Receipts</li>
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
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>GOODS/SERVICE RECEIPT No</th>
                                        <th>Vendor PO Refrence Number</th>
                                        <th>Goods/Service Receipts To</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="headings">
                                        <th></th>
                                        <th>
                                            <div class="my-1">
                                                <input type="date" id="date">
                                            </div>
                                        </th>
                                        {{-- <th></th> --}}
                                        <th>
                                            <div class="my-1">
                                                <input type="text" id="purchase_order_no">
                                            </div>
                                        </th>
                                        {{-- <th>Vendor PO Refrence Number</th> --}}
                                        <th>
                                            <div class="my-1">
                                                <input type="text" id="vendor_po_ref_num">
                                            </div>
                                        </th>

                                        <th>
                                            <div class="my-1">
                                                <input type="text" id="purchase_order_to">
                                            </div>
                                        </th>
                                        <th>
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
                                        <th>
                                            <div class="my-1">
                                                <input type="text" style="visibility:hidden;">
                                            </div>
                                        </th>
                                    </tr>


                                </thead>

                                {{-- <thead id="search_input">

                                </thead> --}}
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

                // scrollY: 600,
                // scrollX: true,
                // scrollCollapse: true,
                // fixedColumns:   {
                //     leftColumns: 0,
                //     rightColumns: 1,
                // },
                
                // 
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.goodsservicereceipts') }}",
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
                                columns: [0,1, 2, 3, 4, 5],
                                modifier: {
                                    page: 'all',
                                    search: 'applied'
                                }
                            },
                            title: function() {
                                var pageTitle = 'GOODS/SERVICES RECEIPTS';
                                return pageTitle
                            }
                        },
                        
    
                    ]
                }],
                dom: 'lBfrtip',
                select: true
            });


            $(window).on('resize', function() {
                table.responsive.recalc();
            });

            $('#purchase_order_no').on('keyup', function() {
                table.column(2).search(this.value).draw();
                // console.log(v);
            });

            $('#vendor_po_ref_num').on('keyup', function() {
                table.column(3).search(this.value).draw();
            });


            // var flag = false;
            $('#date', this).on('keyup change', function() {
                var val = $(this).val();
                var date = new Date(val);
                var newDate = date.toString('dd-MM-yy');
                console.log(newDate);
                table.column(1).search(this.value).draw();

            });

            $('#purchase_order_to').on('keyup', function() {
                table.column(4).search(this.value).draw();
            });

            $('#status').on('change', function() {
                table.column(5).search(this.value).draw();
            });

        });
</script>
@endsection
@endsection