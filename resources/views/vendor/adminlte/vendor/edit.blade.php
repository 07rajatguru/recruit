@extends('adminlte::page')

@section('title', 'Vendors')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::vendor.form')
    </div>
@stop