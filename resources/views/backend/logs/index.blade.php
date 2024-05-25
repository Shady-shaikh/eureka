@extends('backend.layouts.app')
@section('title', 'user activity')
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title"></h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">User Activity</li>
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
                    <div class="">

                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">

                                <table class="table zero-configuration w-100" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Sr. No</th>
                                            <th class='text-center'>User Name</th>
                                            <th class='text-center'>Description</th>
                                            <th class='text-center'>Date & Time</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (isset($users) && count($users) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($users as $useractivity)
                                                <tr class='text-center'>
                                                    <td>{{ $srno }}</td>

                                                    <td>{{ $useractivity->user_name }}</td>

                                                    <td class="text-left">
                                                        @if (isset($useractivity->description))
                                                            {{ $useractivity->description }}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($useractivity->created_at))
                                                            {{ $useractivity->created_at }}
                                                        @else
                                                            --
                                                        @endif
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

    <script>
        $(document).ready(function() {

            $('#tbl-datatable').DataTable({
                dom: 'Bfrtip',
                scrollX: true,
                fixedHeader: true,
                buttons: [{
                        text: '<i class="feather icon-printer"></i> Print',
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },

                        title: function() {
                            var printTitle = 'User Activity';
                            return printTitle
                        },
                        className: 'btn btn-info text-white font-weight-bold pb-0 pt-0 px-1',
                    },

                    {
                        text: '<i class="feather icon-download-cloud"></i> Excel',
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },

                        title: function() {
                            var printTitle = 'User Activity';
                            return printTitle
                        },
                        className: 'btn btn-success text-white font-weight-bold pb-0 pt-0 px-1',
                    },

                ],


            });
        });
    </script>
@endsection


