@extends('adminlte::page')

@section('title', 'Bills')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::bills.form')
    </div>
@stop