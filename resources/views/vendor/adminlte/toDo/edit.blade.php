@extends('adminlte::page')

@section('title', "ToDo's Edit")

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::toDo.form')
    </div>
@stop