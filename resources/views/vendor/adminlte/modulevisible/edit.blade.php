@extends('adminlte::page')

@section('title', 'Module Visibility Edit')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::modulevisible.form')
    </div>

@stop