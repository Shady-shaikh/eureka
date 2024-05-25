@extends(request()->is('api/*') ? 'backend.layouts.appempty' : 'backend.layouts.app')


@section('title', 'Create Outlet')
@php
use Spatie\Permission\Models\Role;
use App\Models\backend\Country;

@endphp
@section('content')

@if(!request()->is('api/*') )
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Create Outlet</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.outlet') }}">Outlets</a>
                    </li>
                    <li class="breadcrumb-item active">Create Outlet</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <div class="btn-group" role="group">
                <a class="btn btn-outline-secondary" href="{{ route('admin.outlet') }}">
                    <i class="feather icon-arrow-left"></i> Back
                </a>
            </div>

        </div>
    </div>
</div>
@endif


<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        @if(!request()->is('api/*') )
                        @include('backend.includes.errors')
                        @endif
                        {{ Form::open(['url' => 'admin/outlet/store']) }}
                        @csrf
                        <div class="form-body">


                            <div class="row">


                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('outlet_name', 'Outlet') }}
                                        {{ Form::text('outlet_name', null, ['class' => 'form-control', 'placeholder' =>
                                        'Enter Name', 'required' => true]) }}
                                    </div>
                                </div>


                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('building_no_name', 'Building No and Name ') }}
                                        {{ Form::text('building_no_name', null, ['class' => 'form-control',
                                        'placeholder' => 'Building No and Name', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('street_name', 'Street Name ') }}
                                        {{ Form::text('street_name', null, ['class' => 'form-control', 'placeholder' =>
                                        'Street Name', 'required' => true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('landmark', 'Landmark ') }}
                                        {{ Form::text('landmark', null, ['class' => 'form-control', 'placeholder' =>
                                        'Landmark', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('country', 'Country') }}
                                        {{ Form::select('country', Country::pluck('name','country_id'), null, ['class' => 'form-control ',
                                         'required' =>true,'placeholder'=>'Select Country']) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('state', 'State') }}
                                        {{ Form::select('state', [], null, ['class' => 'form-control ', 'required' =>
                                        true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('district', 'District ') }}
                                        {{ Form::select('district', [], null, ['class' => 'form-control ', 'required' =>
                                        true]) }}
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('city', 'Name of City ') }}
                                        {{ Form::text('city', null, ['class' => 'form-control',
                                        'placeholder' => 'Name of City', 'required' => true]) }}
                                    </div>
                                </div>


                                <div class="col-md-6 col-6">
                                    <div class="form-label-group">
                                        {{ Form::label('pin_code', 'Pin Code ') }}
                                        {{ Form::number('pin_code', null, ['class' => 'form-control', 'onkeypress' =>
                                        'return event.charCode === 0 ||
                                        /\d/.test(String.fromCharCode(event.charCode));', 'placeholder' => 'Pin Code',
                                        'required' => true]) }}
                                    </div>
                                </div>

                          
                            </div>

                            <hr>
                            <div class="row">



                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('area_id', 'Area *') }}
                                        {{ Form::select('area_id', $area_data, '', ['class' => 'form-control select2',
                                        'id' => 'area_field', 'placeholder' => 'Select Area', 'required' => true]) }}
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('routes', 'Route *') }}
                                        <select name="route_id" id="routes" class="form-control select2 "
                                            required></select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('beat_id', 'Beat *') }}
                                        <select name="beat_id" id="beat" class="form-control select2"></select>
                                    </div>
                                </div>





                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        {{ Form::label('salesman', 'Salesman *') }}
                                        {{ Form::select('salesman', $salesman,
                                        Auth::user()->admin_user_id??request()->segment(3, 'default'), ['class' =>
                                        'form-control tags', 'placeholder' => 'Select Salesman', 'required' => true]) }}
                                    </div>
                                </div>



                            </div>
                            <div class="row mt-3">
                                <div class="col md-12 ">
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary mr-1 mb-1']) }}
                                    <button type="reset" class="btn btn-secondary mr-1 mb-1">Reset</button>
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

<script src="{{ asset('public/backend-assets/js/DynamicDropdown.js') }}"></script>


{{-- add data for area,route and beat --}}
<script>
    $(document).ready(function() {
            // usama_19-02-2024_get states
            $('#country').change(function() {       
            new DynamicDropdown('{{ route('admin.getStates') }}', 
             $(this).val(), '#state',null,'#district');
            });

            $('#state').change(function() {       
            new DynamicDropdown('{{ route('admin.getCities') }}', 
             $(this).val(), '#district',null);
            });
        });
</script>
@endsection