@extends('adminlte::page')

@section('title', 'Create New Training Material')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::training.form')
    </div>

@stop
