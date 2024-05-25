@extends('backend.layouts.app')
@section('title', 'Distributor')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Distributor</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                                class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">Distributor
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            @can('Create Company Master')
                                <a class="btn btn-outline-primary" href="{{ route('admin.company.create') }}">
                                    Add
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
                                <h4 class="card-title">Distributor</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table zero-configuration" id="tbl-datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Logo</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile Number</th>
                                                    <th>Pin Code</th>
                                                    <th>GST No.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $srno = 1; @endphp
                                                @if (isset($company))
                                                    @foreach ($company as $row)
                                                        <tr>
                                                            <td>{{ $srno }}</td>
                                                            <td>
                                                                @if (!empty($row->company_logo))
                                                                <a href="{{ asset('public/backend-assets/images/') }}/{{ $row->company_logo }}"
                                                                    target="_blank"><img class="card-img-top img-fluid mb-1"
                                                                        src="{{ asset('public/backend-assets/images/') }}/{{ $row->company_logo }}"
                                                                        alt="Distributor Logo" style="width:50px"></a>
                                                                @endif
                                                            </td>
                                                            <td>{{ $row->name ?? '' }}</td>
                                                            <td>{{ $row->email }}</td>
                                                            <td>{{ $row->mobile_no }}</td>
                                                            <td>{{ $row->pincode }}</td>
                                                            <td>{{ $row->gstno }}</td>
                                                            <td>
                                                                @can('Update Company Master')
                                                                    <a href="{{ url('admin/company/edit/' . $row->company_id) }}"
                                                                        class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                                                @endcan
                                                                {{-- address button --}}
                                                                <a href="{{ url('admin/bussinesspartner/address/' . $row->company_id) }}"
                                                                    class="btn btn-primary"><i
                                                                        class="feather icon-map"></i></a>


                                                                {{-- banking details --}}
                                                                <a href="{{ url('admin/bussinesspartner/banking/' . $row->company_id) }}"
                                                                    class="btn btn-primary"><i
                                                                        class="feather icon-dollar-sign"></i></a>

                                                                @if (count($company->toArray()) != 1)
                                                                    @can('Delete Company Master')
                                                                        {!! Form::open([
                                                                            'method' => 'GET',
                                                                            'url' => ['admin/company/delete', $row->company_id],
                                                                            'style' => 'display:inline',
                                                                        ]) !!}
                                                                        {!! Form::button('<i class="feather icon-trash"></i>', [
                                                                            'type' => 'submit',
                                                                            'class' => 'btn btn-danger',
                                                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry ?')",
                                                                        ]) !!}

                                                                        {!! Form::close() !!}
                                                                    @endcan
                                                                @endif

                                                            </td>

                                                        </tr>
                                                        @php
                                                            $srno++;
                                                        @endphp
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
