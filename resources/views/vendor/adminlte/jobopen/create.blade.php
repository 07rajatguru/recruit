@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::jobopen.form')
    </div>

@stop

