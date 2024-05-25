@extends('backend.layouts.app')
@section('title', 'View Menu')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Backend Menus</h5>
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">Backend Menus
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
                    <h4 class="card-title">Backend Menus</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                      <div class="col-12 text-right">
                        <a href="{{ route('admin.backendmenu') }}" class="btn btn-secondary"> Back </a>
                      </div>
                      <div class="table-responsive">
                          <table class="table table-bordered table-striped table-hover">
                              <thead>
                                  <tr>
                                      <th>ID.</th> <th>Menu Name</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>{{ $backendmenu->menu_id }}</td> <td> {{ $backendmenu->menu_name }} </td>
                                  </tr>
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

