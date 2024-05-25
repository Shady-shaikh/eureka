@extends('backend.layouts.app')
@section('title', 'Roles')
@section('content')
@php
use Spatie\Permission\Models\Role;
@endphp
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Roles</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                @can('Create Roles')
                <a class="btn btn-outline-primary" href="{{ route('admin.roles.create') }}">
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
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Parent Role</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($roles) && count($roles) > 0)
                                    @php $srno = 1; @endphp
                                    @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $srno }}</td>
                                        <td>{{ $role->name }}</td>
                                        @php
                                        $role_data = Role::where('id', $role->parent_roles)->first();
                                        // dd($role_data);
                                        @endphp
                                        <td>{{ $role_data->name ?? '' }}</td>
                                        <td>

                                            @can('Update Roles')
                                            <a href="{{ url('admin/roles/edit/' . $role->id) }}"
                                                class="btn btn-primary"><i class="feather icon-edit-2"></i></a>
                                            @endcan

                                            @can('Delete Roles')
                                            {!! Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/roles/delete', $role->id],
                                            'style' => 'display:inline',
                                            ]) !!}
                                            {!! Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn
                                            btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry
                                            ?')",
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