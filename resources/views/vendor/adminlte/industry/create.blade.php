@extends('adminlte::page')

@section('title', 'Industry')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div>
        @include('adminlte::industry.form')
    </div>

@stop
