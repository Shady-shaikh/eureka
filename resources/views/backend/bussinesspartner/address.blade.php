@extends('backend.layouts.app')
@section('title', 'Bussiness Partner Address')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Bussiness Partner Address</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Bussiness Partner Address</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <a class="btn btn-outline-primary"
                        href="{{ route('admin.bussinesspartner.createaddress', ['id' => $id]) }}">
                        <i class="feather icon-plus"></i> Add
                    </a>
                    @php
                        $previousController = session('previous_controller');

                        if ($previousController == 'CompanyController@index') {
                            $url_back = route('admin.company');
                        } elseif ($previousController == 'BussinessParatnerController@index') {
                            $url_back = route('admin.bussinesspartner');
                        } elseif ($previousController == 'BussinessParatnerController@edit') {
                            $url_back = route('admin.bussinesspartner.edit',['id'=>request('id')]);
                        }

                    @endphp
                    <a class="btn btn-outline-dark" href="{{ $url_back }}"> Back</a>
                </div>
            </div>
        </div>
    </div>


    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bussiness Partner</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="table-responsive">

                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Address Type</th>
                                            <th>Address Name</th>
                                            <th>Address</th>
                                            <th>Landmark</th>
                                            <th>City</th>
                                            <th>Pin Code</th>
                                            <th>State</th>
                                            <th>District</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (isset($addresses) && count($addresses) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($addresses as $data)
                                                <tr>
                                                    <td>{{ $srno }}</td>
                                                    <td>{{ $data->address_type }}</td>
                                                    <td>{{ $data->bp_address_name }}</td>
                                                    <td>{{ $data->building_no_name }} <br>
                                                        {{ $data->street_name }}
                                                    </td>
                                                    <td>{{ $data->landmark }}</td>
                                                    <td>{{ $data->city }}</td>
                                                    <td>{{ $data->pin_code }}</td>
                                                    <td>{{ $data->getState->name??'' }}</td>
                                                    <td>{{ $data->getDistrict->city_name ??''}}</td>
                                                    <td>

                                                        {{--  update Button  --}}
                                                        <a href="{{ url('admin/bussinesspartner/editaddress/' . $data->bp_address_id) }}"
                                                            class="btn btn-primary"><i class="feather icon-edit-2"></i></a>

                                                        {{--  delete  --}}
                                                        {!! Form::open([
                                                            'method' => 'GET',
                                                            'url' => ['admin/bussinesspartner/deleteaddress', $data->bp_address_id],
                                                        ]) !!}
                                                        {!! Form::button('<i class="feather icon-trash"></i>', [
                                                            'type' => 'submit',
                                                            'class' => 'btn btn-danger',
                                                            'onclick' => "return confirm('Are you sure you want to Delete this Entry ?')",
                                                        ]) !!}
                                                        {!! Form::close() !!}

                                                    </td>
                                                </tr>
                                                @php $srno++; @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan=10>Address Not Found</td>
                                            </tr>
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
    <script src="{{ asset('public/backend-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('public/backend-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/backend-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
@endsection
