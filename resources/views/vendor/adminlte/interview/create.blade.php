@extends('adminlte::page')

@section('title', 'Interview Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::interview.form')
    </div>

@stop