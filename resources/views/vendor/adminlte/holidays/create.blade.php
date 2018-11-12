@extends('adminlte::page')

@section('title', 'Holidays')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div>
        @include('adminlte::holidays.form')
    </div>
@stop