@extends('backend.layouts.app')
@section('title', 'Gst')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">GST</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">GST</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <a class="btn btn-outline-primary" href="{{ route('admin.gst.create') }}">
                    <i class="feather icon-plus"></i> Add
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content content">
    <div class="content-overlay"></div>
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="content-header row">
                                <div class="col-sm-12 text-end mb-1">
                                    @can('Create GST')
                                    <a href="{{ url('admin/gst/create') }}" class="btn btn-primary"><i class="icofont icofont-plus"></i> Add </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="tbl-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>GST Percent(%)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($gst) && count($gst)>0)
                                        @php $srno = 1; @endphp
                                        @foreach($gst as $item)
                                        <tr>
                                            <td>{{ $srno }}</td>
                                            <td>{{ $item->gst_name }}</td>
                                            <td>{{ $item->gst_percent }}</td>
                                            <td>
                                                {{-- @can('Update GST') --}}
                                                <a href="{{ url('admin/gst/edit/'.$item->gst_id) }}" class="btn btn-primary"><i class="feather icon-edit-2" title="Edit"></i></a>
                                                {{-- @endcan --}}
                                                {!! Form::open([
                                                'method'=>'GET',
                                                'url' => ['admin/gst/delete', $item->gst_id],
                                                'style' => 'display:inline'
                                                ]) !!}
                                                {{-- @can('Delete GST') --}}
                                                {!! Form::button('<i class="feather icon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger','onclick'=>"return confirm('Are you sure you want to Delete this Entry ?')","title"=>"Delete"]) !!}
                                                {{-- @endcan --}}
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

</div>

@endsection
@section('scripts')
@include('backend.export_pagination_script')
@endsection