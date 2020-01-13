@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::new_permissions.form')
    </div>
@stop
