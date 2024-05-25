@extends('backend.layouts.app')
@section('title', 'City Master')

@section('content')

<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title">City Master</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>

          <li class="breadcrumb-item">
            <a href="{{ route('admin.state',['id'=>request('id')]) }}">States</a>
          </li>

          <li class="breadcrumb-item active">City</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary add-btn" href="{{ route('admin.state',['id'=>request('id')]) }}">
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
          <div class="card-content">
            <div class="card-body card-dashboard">
                @include('backend.includes.errors')
                {{ Form::open(['url' => 'admin/states/city/store']) }}
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('name', 'City Name *') }}
                                {{ Form::text('city_name', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Enter City Name']) }}
                                {{ Form::hidden('state_id', $id, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Enter Name']) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-12 pt-2">
                            {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


{{--  {{ dd($categories->toArray()) }}  --}}
<section id="basic-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table class="table zero-configuration" id="tbl-datatable" style="white-space: nowrap;">
                <thead>
                  <tr>
                    <th class="col-1 text-center">Sr. No</th>
                    <th>City Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  @if(isset($cities) && count($cities)>0)
                  @php $srno = 1; @endphp
                  @foreach($cities as $data)
                  <tr>
                    <td class="text-center">{{ $srno }}</td>
                    <td>{{ $data->city_name }}</td>
                    <td>
                        <a href="{{ url('admin/states/city/edit/'.$data->city_id.'?state_id='.request('id')) }}" class="btn btn-primary"><i class="feather icon-edit-2"></i></a>
                        <a href="{{ url('admin/states/city/delete/'.$data->city_id) }}" class="btn btn-danger" onclick = "return confirm('Are you sure you want to Delete this Entry ?')"><i class="feather icon-trash"></i></a>
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

<script>
    $(document).ready(function(){
        $('#tbl-datatable').DataTable({
            dom: 'Bfrtip',
            scrollX: true,
            fixedHeader: true,
            buttons: [
                {
                    text: '<i class="feather icon-printer"></i> Print',
                    extend: 'print',
                    exportOptions: {
                        columns: [0,1]
                    },
                    title: function(){
                      var printTitle = 'City Master';
                      return printTitle
                  },
                  className: 'btn btn-info pb-0 pt-0 px-1 text-white font-weight-bold',
                },
                {
                    text: '<i class="feather icon-download-cloud"></i> Excel',
                    extend: 'excel',
                    exportOptions: {
                        columns: [0,1]
                    },
                    title: function(){
                      var printTitle = 'City Master';
                      return printTitle
                    },
                    className: 'btn btn-success text-white font-weight-bold pb-0 pt-0 px-1',
                },
        ],
        });
    });
</script>
@endsection

