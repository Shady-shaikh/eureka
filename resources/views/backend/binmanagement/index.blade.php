@extends('backend.layouts.app')
@section('title', 'Bin Management')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Bin Management</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Bin Management</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    @can('Create Bin Management')
                        <a class="btn btn-outline-primary" href="{{ route('admin.bin.create') }}">
                            <i class="feather icon-plus"></i> Add
                        </a>
                    @endcan
                    <a class="btn btn-outline-secondary" href="{{ route('admin.storagelocations') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bins</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bin</th>
                                            <th>Warehouse</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($storagelocations) && count($storagelocations) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($storagelocations as $storagelocation)
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <td>{{ $storagelocation->get_bin->name }}</td>
                                                    <td>{{ $storagelocation->get_warehouse_name->storage_location_name ?? '' }}
                                                    </td>

                                                    <td>
                                                        @can('Update Bin Management')
                                                            <a href="{{ url('admin/bin/edit/' . $storagelocation->bin_id) }}"
                                                                class="btn btn-primary" title="Edit"><i
                                                                    class="feather icon-edit"></i></a>
                                                        @endcan

                                                        @can('Delete Bin Management')
                                                            {!! Form::open([
                                                                'method' => 'GET',
                                                                'url' => ['admin/bin/delete', $storagelocation->bin_id],
                                                                'style' => 'display:inline',
                                                            ]) !!}
                                                            {!! Form::button('<i class="feather icon-trash"></i>', [
                                                                'type' => 'submit',
                                                                'title' => 'Delete',
                                                                'class' => 'btn btn-danger',
                                                                'onclick' => "return confirm('Are you sure you want to Delete this Entry ?')",
                                                            ]) !!}
                                                            {!! Form::close() !!}
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
    </div>
    </div>

@endsection


@section('scripts')
@include('backend.export_pagination_script')
@endsection