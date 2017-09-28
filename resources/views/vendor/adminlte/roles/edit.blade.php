@extends('adminlte::page')

@section('title', 'Role')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::roles.form')
    </div>

@stop
