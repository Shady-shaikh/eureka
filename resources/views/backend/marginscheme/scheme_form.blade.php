@extends('backend.layouts.app')
@section('title', 'Set Scheme Price')


@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Set Scheme Price : {{$pricing->pricing_name}}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            {{-- <a href="{{ asset('public/sheets/sample.xlsx') }}" class="btn btn-outline-primary">Sample Sheet</a>
            --}}
        </div>
    </div>
</div>

<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-sm-12">
            <div class="card">
                <h4 class="px-2 py-1">{{$pricing->pricing_name}}</h4>
                <div class="card-content">
                    <div class="card-body">


                        @include('backend.includes.errors')
                        {{ Form::open(['url' => 'admin/scheme/update', 'class' => 'w-100', 'enctype' =>
                        'multipart/form-data']) }}
                        {{ Form::hidden('pricing_master_id', request('id'), ['class' =>
                        'form-control']) }}

                        <div class="row">





                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('file', 'Import Data From File') }}
                                    <input type="file" name="file" class="form-control">

                                </div>
                            </div>
                            <div class="col-md-1">
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


                        <table class="table table-bordered table-striped" id="tbl-datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Brand</th>
                                    <th>Format</th>
                                    <th>Variant</th>
                                    <th>Buom Pack Size</th>
                                    <th>Scheme %</th>
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
</section>
</div>
</div>




@endsection

@section('scripts')
<script src="{{ asset('public/backend-assets/js/DynamicDropdown.js') }}"></script>


<script>
    var table = $('#tbl-datatable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.scheme.form',['id'=>request('id')]) }}", // Replace with your server-side endpoint
                type: "GET",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'brand_id',
                    name: 'brand_id'
                },
                {
                    data: 'sub_category_id',
                    name: 'sub_category_id'
                },
                {
                    data: 'variant',
                    name: 'variant'
                },
                {
                    data: 'buom_pack_size',
                    name: 'buom_pack_size'
                },
                {
                    data: 'scheme',
                    name: 'scheme',
                    // render: function(data, type, row) {
                    //     // return data==0?0:data;
                    //     return '<input type="number" name="margin" class="selling-price-input" value="' +
                    //         data + '">';
                    // }
                },
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5],
                    modifier: {
                        page: 'all',
                        search: 'applied'
                    }
                },
                title: 'Scheme Data',
                customize: function (xlsx) {
                    
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop through each table row
                    $('#tbl-datatable tbody tr').each(function (index) {
                        var marginCell = $('row c[r^="F' + (index + 3) + '"]', sheet);
                        var marginValue = $(this).find('td:eq(5)').text(); // Get margin value from 5th column
                        marginCell.find('v').text(marginValue);
                    });
                }

            }],
            dom: 'lBfrtip',
            select: true,
            paging:true,
            pageLength:10,
            lengthMenu: [
                [-1],
                ['All']
            ],
    });


            
   
</script>

@endsection