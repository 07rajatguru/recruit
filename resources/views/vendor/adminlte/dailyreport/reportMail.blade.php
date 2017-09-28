@extends('adminlte::page')

@section('title', 'Daily Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">

                    <h2>Send Daily Report</h2>

                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('dailyreport.index') }}"> Back</a>
                </div>

            </div>

        </div>

        {!! Form::open(array('route' => 'dailyreport.reportMail','files' => true,'method'=>'POST')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="box-body col-xs-12 col-sm-12 col-md-12">
                                <div class="box-body col-xs-6 col-sm-6 col-md-6">

                                    {!! Form::hidden('fromDate', $fromDate, array('id'=>'fromDate')) !!}
                                    {!! Form::hidden('toDate', $toDate, array('id'=>'toDate')) !!}

                                    <div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
                                        <strong>Select To</strong>
                                        {!! Form::select('to[]', $users,null, array('id'=>'to','class' => 'form-control', 'multiple')) !!}
                                        @if ($errors->has('to'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('to') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('cc') ? 'has-error' : '' }}">
                                        <strong>Select CC</strong>
                                        {!! Form::select('cc[]', $users,null, array('id'=>'cc','class' => 'form-control','multiple')) !!}
                                        @if ($errors->has('cc'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('cc') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('bcc') ? 'has-error' : '' }}">
                                        <strong>Select Bcc</strong>
                                        {!! Form::select('bcc[]', $users,null, array('id'=>'bcc','class' => 'form-control','multiple')) !!}
                                        @if ($errors->has('bcc'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('bcc') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">

                    <button type="submit" class="btn btn-primary">Submit</button>

                </div>
            </div>
        {!! Form::close() !!}
    </div>

@stop



@section('adminlte_js')
    <script>
        $(document).ready(function(){

            $( "#to" ).select2();
            $( "#cc" ).select2();
            $( "#bcc" ).select2();
        });
    </script>
@endsection

