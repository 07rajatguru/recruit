@extends('adminlte::page')

@section('title', 'Accounting Head')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::accounting.form')
    </div>
@stop