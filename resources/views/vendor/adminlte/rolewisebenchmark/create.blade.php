@extends('adminlte::page')

@section('title', 'Bench Mark')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::rolewisebenchmark.form')
    </div>
@stop