@extends('adminlte::page')

@section('title', 'Receipt Talent')

@section('content_header')

@stop

@section('content')

@if(isset($messages) && sizeof($messages)>0)
    <div class="alert alert-success">
        @foreach($messages as $key=>$value)
                <p>{{ $value }}</p>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Receipt Talent Import</h2>
        </div>

        <div class="pull-right">
        	<a class="btn btn-primary" href="{{ route('receipt.talent') }}"> Back</a>
            <a class="btn btn-success" href=""> Create New Recepit Talent</a>
        </div>
    </div>
</div>

{!! Form::open(['route' => 'receipt.talentimportstore', 'method' => 'POST', 'id'=>'recepit_talent_import', 'files' => true, 'enctype' => 'multipart/form-data']) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
           <div class="box-body col-xs-6 col-sm-6 col-md-6">
                <div class="">
                    <strong>Bank Type:</strong>
                    {!! Form::select('bank_type', $bank_type,null, array('id'=>'bank_type','class' => 'form-control')) !!}
		            <br>
		            <input type="file" name="import_file" /><br/>
		            <button class="btn btn-primary">Import Excel File</button><br/>
                </div>
            </div>
        </div>
    </div>
</div>

@stop