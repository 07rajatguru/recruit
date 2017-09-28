@extends('adminlte::page')

@section('title', 'Candidate Status')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        @include('adminlte::candidateStatus.form')
    </div>

@stop