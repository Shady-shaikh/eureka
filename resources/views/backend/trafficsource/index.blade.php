@extends('backend.layouts.app')
@section('title', 'Traffic Source')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Traffic Source</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                            class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Traffic Source
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Traffic Source
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">

                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="tbl-datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>DATE</th>
                                                <th>EMAIL</th>
                                                <th>IP ADDRESS</th>
                                                <!--<th>HTTP_USER_AGENT</th>-->
                                                <th>OPERATING SYSTEM</th>
                                                <th>DEVICE</th>
                                                <th>BROWSER</th>
                                                <th>LOGIN TYPE</th>



                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if (isset($externaluser) && count($externaluser) > 0)
                                            @php $srno = 1; @endphp
                                            @foreach ($externaluser as $user)
                                            <tr>
                                                <td>{{ $srno }}</td>
                                                <td>{{ date('d-m-Y H:i', strtotime($user->created_at)) }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->REMOTE_ADDR }}</td>
                                                <!--<td>{{ $user->HTTP_USER_AGENT }}</td>-->
                                                <td>{{ $user->user_os }}</td>
                                                <td>{{ ucfirst($user->device) }}</td>
                                                <td>{{ $user->browser }}</td>
                                                <td>{{ strtoupper($user->traffic_source) }}</td>


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