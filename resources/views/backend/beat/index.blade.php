@extends('backend.layouts.app')
@section('title', 'Beat')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Beat</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Beat</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    @can('Create Beat')
                        <a class="btn btn-outline-primary" href="{{ route('admin.beat.create') }}">
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
                        <h4 class="card-title">Beat</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="table-responsive">

                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Beat</th>
                                            <th>Area</th>
                                            <th>Route</th>
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($details) && count($details) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($details as $data)
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <!--<td>{{ $data->beat_number }}</td>-->
                                                    <td>{{ $data->beat_name }}</td>
                                                    <td><?php
                                                    foreach ($area_data as $row) {
                                                        if ($row->area_id == $data->area_id) {
                                                            echo $row->area_name;
                                                        }
                                                    }
                                                    
                                                    ?></td>

                                                    <td><?php
                                                    foreach ($route_data as $row) {
                                                        if ($row->route_id == $data->route_id) {
                                                            echo $row->route_name;
                                                        }
                                                    }
                                                    
                                                    ?></td>

                                                    <td>

                                                        <a href="{{ url('admin/beat/edit/' . $data->beat_id) }}"
                                                            class="btn btn-primary" title="Edit"><i
                                                                class="feather icon-edit"></i></a>

                                                        {!! Form::open([
                                                            'method' => 'GET',
                                                            'url' => ['admin/beat/delete', $data->beat_id],
                                                            'style' => 'display:inline',
                                                        ]) !!}
                                                        {!! Form::button('<i class="feather icon-trash"></i>', [
                                                            'type' => 'submit',
                                                            'title' => 'Delete',
                                                            'class' => 'btn btn-danger',
                                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry ?')",
                                                        ]) !!}
                                                        {!! Form::close() !!}


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

