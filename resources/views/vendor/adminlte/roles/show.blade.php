@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Role</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('roles.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $role->display_name }}
            </div>
        
            <div class="form-group">
                <strong>Description:</strong>
                {{ $role->description }}
            </div>

            <div class="form-group">
                <strong>Department:</strong>
                {{ $role->department }}
            </div>

            <div class="form-group">
                <strong>Permissions:</strong>
                @if(!empty($rolePermissions))
                    @foreach($rolePermissions as $v)
                        <label class="label label-success">{{ $v->display_name }}</label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection