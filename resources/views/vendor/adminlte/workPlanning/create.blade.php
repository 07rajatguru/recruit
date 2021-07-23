@extends('adminlte::page')

@section('title', 'Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::workPlanning.form')
</div>
@stop