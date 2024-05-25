@extends('backend.layouts.app')
@section('title', 'Warehouses')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Warehouses</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Warehouses</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    @can('Create Warehouse Management')
                        <a class="btn btn-outline-primary" href="{{ route('admin.storagelocations.create') }}">
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
                        <h4 class="card-title">Warehouses</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Warehouse Name</th>
                                            <th>Warehouse Location</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($storagelocations) && count($storagelocations) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($storagelocations as $storagelocation)
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <td>{{ $storagelocation->storage_location_name }}</td>
                                                    <td>{{ $storagelocation->company->city ?? '' }}
                                                    </td>

                                                    <td>
                                                        @can('Update Warehouse Management')
                                                            <a href="{{ url('admin/storagelocations/edit/' . $storagelocation->storage_location_id) }}"
                                                                class="btn btn-primary" title="Edit"><i
                                                                    class="feather icon-edit"></i></a>
                                                        @endcan
                                                        @can('Delete Warehouse Management')
                                                            {!! Form::open([
                                                                'method' => 'GET',
                                                                'url' => ['admin/storagelocations/delete', $storagelocation->storage_location_id],
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
