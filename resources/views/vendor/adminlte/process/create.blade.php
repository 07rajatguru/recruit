@extends('adminlte::page')

@section('title', 'Process Manual')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::process.form')
    </div>
@stop