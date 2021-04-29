@extends('adminlte::page')

@section('title', 'Department')

@section('content_header')
@stop

@section('content')
    <div>
        @include('adminlte::departments.form')
    </div>
@stop