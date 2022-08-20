@extends('adminlte::page')

@section('title', 'User Benchmark')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::userbenchmark.form')
    </div>
@stop