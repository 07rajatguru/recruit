@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::new_role.form')
    </div>
@stop