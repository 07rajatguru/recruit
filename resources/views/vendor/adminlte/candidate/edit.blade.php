@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::candidate.form')
    </div>
@stop