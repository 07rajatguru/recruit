@extends('adminlte::page')

@section('title', 'Add Eligibility')

@section('content_header')
    <h1></h1>

@stop

@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection


@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add Eligibility</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('report.eligibilityreportindex') }}"> Back</a>
        </div>
    </div>
</div>

{!! Form::open(array('route' => 'report.eligibilityreportstore', 'method' => 'POST', 'id' => 'eligibility_form')) !!}

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="box-body col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-2 col-sm-2 col-md-2">
        <div class="form-group">
            {!! Form::submit('Select', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>

@stop