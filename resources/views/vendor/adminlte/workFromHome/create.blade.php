@extends('adminlte::page')

@section('title', 'Work From Home Request')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::workFromHome.form')
</div>
@stop