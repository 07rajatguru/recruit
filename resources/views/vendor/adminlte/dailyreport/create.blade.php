@extends('adminlte::page')

@section('title', 'Daily Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::dailyreport.form')
    </div>

@stop

