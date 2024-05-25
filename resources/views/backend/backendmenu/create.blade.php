@extends('backend.layouts.app')
@section('title', 'Create Menu')

@section('content')
@php
use App\Models\backend\BasePermissions;
  $permissions = BasePermissions::get();
@endphp
<div class="app-content content">
  <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Create Menu</h5>
                <div class="breadcrumb-wrapper col-12">
                  <ol class="breadcrumb p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">Create
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section id="multiple-column-form">
          <div class="row match-height">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Create Menu</h4>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    @include('backend.includes.errors')
                    {{ Form::open(array('url' => 'admin/backendmenu/store')) }}
                      <div class="form-body">
                        <div class="row">
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              {{ Form::label('menu_name', 'Menu Name *') }}
                              {{ Form::text('menu_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Menu Name', 'required' => true]) }}
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              {{ Form::label('menu_controller_name', 'Menu Controller Name *') }}
                              {{ Form::text('menu_controller_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Menu Controller Name', 'required' => true]) }}
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              {{ Form::label('menu_action_name', 'Menu Action Name *') }}
                              {{ Form::text('menu_action_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Menu Action Name']) }}
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              {{ Form::label('menu_icon', 'Menu Icon *') }}
                              {{ Form::text('menu_icon', null, ['class' => 'form-control', 'placeholder' => 'Enter Menu Icon']) }}
                            </div>
                          </div>
                          <div class="col-md-6 col-6">
                            {{ Form::label('has_submenu', 'Has Subcategories ?') }}
                            <fieldset>
                              <div class="radio radio-success">
                                {{ Form::radio('has_submenu','1',true,['id'=>'radioyes']) }}
                                {{ Form::label('radioyes', 'Yes') }}
                              </div>
                            </fieldset>
                            <fieldset>
                              <div class="radio radio-danger">
                                {{ Form::radio('has_submenu','0',false,['id'=>'radiono']) }}
                                {{ Form::label('radiono', 'No') }}
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-6 col-6">
                            {{ Form::label('visibility', 'Show / Hide') }}
                            <fieldset>
                              <div class="radio radio-success">
                                {{ Form::radio('visibility','1',true,['id'=>'radioshow']) }}
                                {{ Form::label('radioshow', 'Yes') }}
                              </div>
                            </fieldset>
                            <fieldset>
                              <div class="radio radio-danger">
                                {{ Form::radio('visibility','0',false,['id'=>'radiohide']) }}
                                {{ Form::label('radiohide', 'No') }}
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-6 col-12 mt-2 menu_permissions">
                            {{ Form::label('permissions', 'Menu Permissions *') }}
                            <ul class="list-unstyled mb-0">
                              @foreach($permissions as $permission)
                              <li class="d-inline-block mr-2 mb-1">
                                <fieldset>
                                  <div class="checkbox checkbox-primary">
                                    {{ Form::checkbox('permissions[]', $permission->base_permission_id, null, ['id'=>'permissions['.$permission->base_permission_id.']']) }}
                                    {{ Form::label('permissions['.$permission->base_permission_id.']', $permission->base_permission_name) }}
                                  </div>
                                </fieldset>
                              </li>
                              @endforeach
                            </ul>
                          </div>
                          <div class="col-12 d-flex justify-content-start">
                            {{ Form::submit('Save', array('class' => 'btn btn-primary mr-1 mb-1')) }}
                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                          </div>
                        </div>
                      </div>
                    {{ Form::close() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <script>
      $(document).ready(function()
      {
        $('input[type=radio][name=has_submenu]').change(function()
        {
          // alert(this.value);
          permissions(this.value);
        });
        var sub_per_load = $('input[type=radio][name=has_submenu]:checked').val();
        if (sub_per_load != '')
        {
          // alert(sub_per_load);
            permissions(sub_per_load);
        }
      });
      function permissions(subcat)
      {
        if (subcat == '1')
        {
            $('.menu_permissions').hide();
        }
        else if (subcat == '0')
        {
            $('.menu_permissions').show();
        }
      }
    </script>
@endsection
