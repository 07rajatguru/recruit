@extends('adminlte::page')

@section('title', 'Client Hierarchy')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::clientheirarchy.form')
</div>
@stop