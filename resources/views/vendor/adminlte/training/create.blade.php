@extends('adminlte::page')

@section('title', 'Add New Training Material')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::training.form')
    </div>
@stop