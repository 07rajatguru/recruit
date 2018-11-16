@extends('adminlte::page')

@section('title', 'Receipt Talent')

@section('content_header')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Receipt Talent</h2>
        </div>

        <div class="pull-right">
        	<a class="btn btn-primary" href="{{ route('receipt.talentimport') }}"> Import Recepit Talent</a>
            <a class="btn btn-success" href=""> Create New Recepit Talent</a>
        </div>
    </div>
</div>

@stop