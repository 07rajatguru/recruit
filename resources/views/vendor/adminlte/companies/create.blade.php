@extends('adminlte::page')

@section('title', 'Company')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::companies.form')
    </div>
@stop