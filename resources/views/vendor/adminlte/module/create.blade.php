@extends('adminlte::page')

@section('title', 'Modules')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::module.form')
    </div>
@stop