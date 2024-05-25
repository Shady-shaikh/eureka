@extends('backend.layouts.app')
@section('title', 'BSPL Sub-Category')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">BSPL Sub-Category</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.bsplcat') }}">BSPL Category</a>
                        </li>
                        <li class="breadcrumb-item active">BSPL Sub-Category</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    @can('Create BSPL Sub-Category')
                        <a class="btn btn-outline-primary"
                            href="{{ route('admin.bsplsubcat.create', ['id' => isset(request()->id) ? request()->id : 0]) }}">
                            <i class="feather icon-plus"></i> Add
                        </a>
                    @endcan
                    @if (Request::route()->getName() != 'admin.bsplsubcat')
                        <a class="btn btn-outline-secondary ml-25" href="{{ route('admin.bsplcat') }}">
                            <i class="feather icon-arrow-left"></i> Back
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">BSPL Sub-Category </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            {{--  {{ dd($details->toArray()) }}  --}}
                            <div class="table-responsive">

                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>BSPL Category</th>
                                            <th>BSPL Sub-Category</th>
                                            <th colspan="3">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($details) && count($details) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($details as $data)
                                                <tr>
                                                    <td>{{ $srno }}</td>

                                                    <td>{{ $data->bspl_cat_name->bspl_cat_name }}</td>
                                                    <td>{{ $data->bspl_subcat_name }}</td>

                                                    <td>
                                                        @can('Update BSPL Sub-Category')
                                                            <a href="{{ url('admin/bsplsubcat/edit/' . $data->bsplsubcat_id . '?route=' . Request::route()->getName()) }}"
                                                                class="btn btn-primary" title="Edit"><i
                                                                    class="feather icon-edit"></i></a>
                                                        @endcan
                                                        @can('Delete BSPL Sub-Category')
                                                            {!! Form::open([
                                                                'method' => 'GET',
                                                                'url' => ['admin/bsplsubcat/delete', $data->bsplsubcat_id],
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

@endsection
