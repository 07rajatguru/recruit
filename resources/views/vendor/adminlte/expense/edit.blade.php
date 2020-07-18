@extends('adminlte::page')

@section('title', 'Expense')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div>
        @include('adminlte::expense.form')
    </div>
@stop