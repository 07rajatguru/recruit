@extends('adminlte::page')

@section('title', 'Customer Support')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::customerSupport.form')
</div>
@stop