@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::client.form')
    </div>

@stop

