@extends('adminlte::page')

@section('title', 'Ticket Discussion')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div>
    @include('adminlte::ticketDiscussion.form')
</div>
@stop