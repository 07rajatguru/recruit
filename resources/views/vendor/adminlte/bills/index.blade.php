@extends('adminlte::page')

@section('title', 'Bills')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Bills List</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('bills.create') }}"> Create New Bill</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif
@stop