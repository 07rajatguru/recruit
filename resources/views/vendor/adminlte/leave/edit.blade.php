@extends('adminlte::page')

@section('title', 'Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::leave.form')
    </div>
@stop