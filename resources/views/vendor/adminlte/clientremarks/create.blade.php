@extends('adminlte::page')

@section('title', 'Client Remarks')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::clientremarks.form')
</div>
@stop