@extends('adminlte::page')

@section('title', "Attendance")

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

	@if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    
    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add Attendance</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
        </div>
    </div>
</div>

{!! Form::open(['route' => 'users.attendancestore', 'method' => 'POST', 'id' => 'add_attendance_form']) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
	                    <div class="form-group {{ $errors->has('users') ? 'has-error' : '' }}">
	                        <strong>User: <span class = "required_fields">*</span> </strong>
	                        {!! Form::select('users', $users, null, array('id'=>'users','class' => 'form-control','tabindex' => '1' )) !!}
	                        @if ($errors->has('users'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('users') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>

	                <div class="">
	                    <div class="form-group {{ $errors->has('date_time') ? 'has-error' : '' }}">
	                        <strong>Date-Time: <span class = "required_fields">*</span> </strong>
	                        {!! Form::text('date_time', null, array('id'=>'date_time','class' => 'form-control','tabindex' => '1' )) !!}
	                        @if ($errors->has('date_time'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('date_time') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	            </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
	                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
	                        <strong>Type: <span class = "required_fields">*</span> </strong>
	                        {!! Form::select('type', $type, null, array('id'=>'type','class' => 'form-control','tabindex' => '1' )) !!}
	                        @if ($errors->has('type'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('type') }}</strong>
	                            </span>
	                        @endif
	                    </div>
	                </div>
	            </div>
	    	</div>
	    </div>
	</div>

	<div class="form-group">
        <div class="col-sm-2">&nbsp;</div>
	        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
	            {!! Form::submit('Submit', ['class' => 'btn btn-primary',]) !!}
	        </div>
    </div>
</div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#date_time").datetimepicker({
                format: "DD-MM-YYYY h:mm A",
            });

            $("#users").select2();

            $("#add_attendance_form").validate({
            	rules: { 
            		"users": {
            			required: true,
            		},
            		"date_time": {
            			required: true,
            		},
            		"type": {
            			required: true,
            		},
            	},
            	messages : {
            		"users" : {
            			required: "User field can't be empty",
            		},
            		"date_time" : {
            			required: "Date & time field can't be empty",
            		},
            		"type" : {
            			required: "Type field can't be empty",
            		}
            	},
            });
        });
    </script>
@endsection