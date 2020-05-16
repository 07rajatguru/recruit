@extends('adminlte::page')

@section('title', 'Candidate Source')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::candidateSource.form')
    </div>
@stop