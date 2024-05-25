@extends('backend.layouts.app')
@section('title', 'Pending Requests')

@section('content')

<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title"> Pending Requests</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active"> Pending Requests</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary" href="{{ route('admin.outlet.create') }}">
          <i class="feather icon-plus"></i> Add Outlet
        </a>
        <a class="btn btn-outline-secondary" href="{{ route('admin.bussinesspartner') }}">
          <i class="feather icon-arrow-left"></i> Back
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
                    <h4 class="card-title">Pending Requests</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        {{--  {{ dd($details->toArray()) }}  --}}
                        <div class="table-responsive">

                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Outlet</th>
                                        <th>Beat</th>
                                        <th colspan="3">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($details) && count($details) > 0)
                                        @php $srno = 1; @endphp
                                        @foreach ($details as $data)
                                            <tr>
                                                <td>{{ $srno }}</td>
                                                
                                                <td>{{ $data->bp_name }}</td>
                                                <td>{{ $data->get_beat->beat_name }}</td>

                                                <td>
                                   
                                                    <a href="{{ url('admin/bussinesspartner/edit/'.$data->business_partner_id) }}" class="btn btn-primary" title="Edit"><i class="feather icon-edit"></i></a>
                                       
                                                    {!! Form::open([
                                                        'method'=>'GET',
                                                        'url' => ['admin/outlet/delete', $data->business_partner_id],
                                                        'style' => 'display:inline'
                                                    ]) !!}
                                                        {!! Form::button('<i class="feather icon-trash"></i>', ['type' => 'submit','title'=>'Delete', 'class' => 'btn btn-danger','onclick'=>"return confirm('Are you sure you want to Delete this Entry ?')"]) !!}
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
