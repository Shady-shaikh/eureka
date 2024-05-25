@extends('backend.layouts.app')
@section('title', 'Backend Sub Menus')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Backend Sub Menus</h5>
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">Backend Sub Menus
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
                    <h4 class="card-title">Backend Sub Menus</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="col-12 text-right">
                        <a href="{{ route('admin.backendsubmenu.create') }}/{{ request()->menu_id }}" class="btn btn-primary"><i class="bx bx-plus"></i> Add </a>
                      </div>
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Name</th>
                                      <th>Controller Name</th>
                                      <th>Method Name</th>
                                      <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if(isset($backendsubmenus) && count($backendsubmenus)>0)
                                    @php $srno = 1; @endphp
                                    @foreach($backendsubmenus as $menu)
                                    <tr>
                                      <td>{{ $srno }}</td>
                                      <td>{{ $menu->submenu_name }}</td>
                                      <td>{{ $menu->submenu_controller_name }}</td>
                                      <td>{{ $menu->submenu_action_name }}</td>
                                      <td>

                                        <a href="{{ url('admin/backendsubmenu/edit/'.$menu->submenu_id) }}" class="btn btn-primary"><i class="bx bx-pencil"></i></a>
                                        {!! Form::open([
                                            'method'=>'GET',
                                            'url' => ['admin/backendsubmenu/delete', $menu->submenu_id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                            {!! Form::button('<i class="bx bx-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger','onclick'=>"return confirm('Are you sure you want to Delete this Entry ?')"]) !!}
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
</div>

@endsection
