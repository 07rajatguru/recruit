@extends('adminlte::page')

@section('title', 'Team')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::team.form')
    </div>
@stop