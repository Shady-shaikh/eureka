@extends('backend.layouts.app')
@section('title')
Schemes
@stop

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Offers</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Offers</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                @can('Create External Users')
                <a class="btn btn-outline-primary" href="{{ url('admin/schemes/create') }}">
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
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration " id="tbl-datatable" style="text-align:center">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Scheme Title</th>
                                        <th>Mininum Product Qty</th>
                                        <th>Free Product Qty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schemes as $item)
                                    <tr>
                                        <td>{{ $item->schemes_id }}</td>
                                        <td>{{ $item->scheme_title }}</td>
                                        <td>{{ $item->min_product_qty }}</td>
                                        <td>{{ $item->free_product_qty }}</td>
                                        <td>
                                            {{-- @if (in_array('Update Offers', $permissions)) --}}
                                            <a href="{{ url('admin/schemes/edit/' . $item->schemes_id) }}"
                                                class="btn btn-primary "><i class="feather icon-edit-2"></i></a>
                                            {{-- @endif
                                            @if (in_array('Delete Offers', $permissions)) --}}
                                            {!! Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/schemes/delete', $item->schemes_id],
                                            'style' => 'display:inline',
                                            ]) !!}
                                            {!! Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry
                                            ?')",
                                            ]) !!}
                                            {!! Form::close() !!}
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endforeach
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