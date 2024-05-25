@extends('backend.layouts.app')
@section('title', 'Categories')

@section('content')
<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title">Categories</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard')}}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Categories</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
        <a class="btn btn-outline-primary" href="{{ route('admin.categories.create') }}">
          <i class="feather icon-plus"></i> Add
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
                    <h4 class="card-title">Categories</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table zero-configuration" id="tbl-datatable">
                                <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Category Name</th>
                                      <th>Visibility</th>
                                      <th>Has Subcategories</th>
                                      <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if(isset($categories) && count($categories)>0)
                                    @php $srno = 1; @endphp
                                    @foreach($categories as $category)
                                    <tr>
                                      <td>{{ $srno }}</td>
                                      <td>{{ $category->category_name }}</td>
                                      <td>{{ ($category->visibility==1)?'Yes':'No' }}</td>
                                      <td>{{ ($category->has_subcategories==1)?'Yes':'No' }}</td>
                                      <td>
                                        @php
                                          if($category->has_subcategories)
                                          {
                                        @endphp
                                            <a href="{{ url('admin/subcategories/category/' . $category->category_id) }}" class="btn btn-primary btn-xs">Sub Categories</a>
                                        @php
                                          }
                                        @endphp
                                        <a href="{{ url('admin/categories/edit/'.$category->category_id) }}" class="btn btn-primary" title="Edit"><i class="feather icon-edit"></i></a>
                                        {!! Form::open([
                                            'method'=>'GET',
                                            'url' => ['admin/categories/delete', $category->category_id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                            {!! Form::button('<i class="feather icon-trash"></i>', ['type' => 'submit', 'title'=>'Delete','class' => 'btn btn-danger','onclick'=>"return confirm('Are you sure you want to Delete this Entry ?')"]) !!}
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
