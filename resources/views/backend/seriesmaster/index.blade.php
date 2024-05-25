@extends('backend.layouts.app')
@section('title', 'Series Master')

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title"> Series Master</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active"> Series Master</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                @can('Create Series Master')
                <a class="btn btn-outline-primary" href="{{ route('admin.seriesmaster.create') }}">
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
                    <h4 class="card-title"> Series Master </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        {{-- {{ dd($details->toArray()) }} --}}
                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Series Abbreviation</th>
                                        <th>Transaction Type</th>
                                        <th>Module Name</th>
                                        <th>Sample Doc Number</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($details) && count($details) > 0)
                                    @php $srno = 1; @endphp
                                    @foreach ($details as $data)
                                    <tr>
                                        <td>{{ $srno }}</td>

                                        <td>{{ $data->series_number }}</td>
                                        <td>{{ $data->transaction_type }}</td>
                                        @php
                                        $modules = DB::table('modules')
                                        ->where('id', $data->module)
                                        ->pluck('name', 'id');
                                        @endphp
                                        <td>{{ $modules[$data->module] }}</td>
                                        <td>
                                            {{$data->series_number}}-2023-24-1
                                        </td>
                                        <td>

                                            @can('Update Series Master')
                                            <a href="{{ url('admin/seriesmaster/edit/' . $data->id) }}"
                                                class="btn btn-primary" title="Edit"><i
                                                    class="feather icon-edit"></i></a>
                                            @endcan
                                            @can('Delete Series Master')
                                            {!! Form::open([
                                            'method' => 'GET',
                                            'url' => ['admin/seriesmaster/delete', $data->id],
                                            'style' => 'display:inline',
                                            ]) !!}
                                            {!! Form::button('<i class="feather icon-trash"></i>', [
                                            'type' => 'submit',
                                            'title' => 'Delete',
                                            'class' => 'btn btn-danger',
                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry?')",
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

@endsection

@section('scripts')
@include('backend.export_pagination_script')
@endsection