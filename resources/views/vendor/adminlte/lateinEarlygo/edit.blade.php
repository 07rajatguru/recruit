@extends('adminlte::page')

@section('title', 'Late In / Early Go')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::lateinEarlygo.form')
    </div>
@stop