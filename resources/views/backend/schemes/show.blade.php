@extends('backend.layouts.inner')
@section('title')
Mark
@stop

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Mark Value</th><th>Mark Value In Rs</th><th>Mark Value In %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $mark->id }}</td> <td> {{ $mark->mark_value }} </td><td> {{ $mark->mark_value_in_rs }} </td>
                    <td> {{ $mark->mark_value_in_percent }} % </td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection
