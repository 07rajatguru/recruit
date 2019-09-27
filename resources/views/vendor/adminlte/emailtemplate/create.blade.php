@extends('adminlte::page')

@section('title', 'Email Template | Add')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::emailtemplate.form')
</div>
@stop