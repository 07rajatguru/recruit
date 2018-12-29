@extends('adminlte::page')

@section('title', 'User Leave Balance Edit')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	@section('customs_css')
	    <style>
	        .error{
	            color:#f56954 !important;
	        }
	    </style>
	@endsection

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
	            <h2>Edit User Leave Balance</h2>  
	        </div>
	        <div class="pull-right">
	            <a class="btn btn-primary" href="{{ route('leave.userwise') }}"> Back</a>
	        </div>
	    </div>
	</div>

	{!! Form::model($leave_data,['route' => ['leave.userwiseupdate', $leave_data->id],'method'=>'PATCH','id' => 'user_leave_data','files' => true]) !!}

	<div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
	            <div class="box-header with-border col-md-6 ">
	            </div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
	                            <strong>Select User: <span class = "required_fields">*</span> </strong>
	                            {!! Form::select('user_id',$users, $user_id, array('id'=>'user_id','class' => 'form-control', 'tabindex' => '1' )) !!}
	                            @if ($errors->has('user_id'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('user_id') }}</strong>
		                            </span>
	                            @endif
	                        </div>

	                        <div class="form-group {{ $errors->has('leave_taken') ? 'has-error' : '' }}">
	                            <strong>Leave Taken: <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_taken',null, array('id'=>'leave_taken','placeholder' => 'Leave Taken','class' => 'form-control','tabindex' => '3', 'onchange' => 'getleave()')) !!}
	                            @if ($errors->has('leave_taken'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_taken') }}</strong>
		                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('leave_total') ? 'has-error' : '' }}">
	                            <strong>Total Leave: <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_total',null, array('id'=>'leave_total','placeholder' => 'Total Leave','class' => 'form-control','tabindex' => '2', 'onchange' => 'getleave()')) !!}
	                            @if ($errors->has('leave_total'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_total') }}</strong>
		                            </span>
	                            @endif
	                        </div>

	                        <div class="form-group {{ $errors->has('leave_remaining') ? 'has-error' : '' }}">
	                            <strong>Leave Remaining: <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_remaining',null, array('id'=>'leave_remaining','placeholder' => 'Leave Remaining','class' => 'form-control','tabindex' => '4')) !!}
	                            @if ($errors->has('leave_remaining'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_remaining') }}</strong>
		                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
	            <button type="submit" class="btn btn-primary">Submit</button>
	        </div>
	    </div>
	</div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("#user_id").select2();

            $("#user_leave_data").validate({
                rules: {
                    "user_id": {
                        required: true
                    },
                    "leave_total": {
                        required: true
                    },
                    "leave_taken": {
                    	required: true
                    },
                    "leave_remaining": {
                    	required: true
                    }
                },
                messages: {
                    "user_id": {
                        required: "User is required field."
                    },
                    "leave_total": {
                        required: "Leave Total required field."
                    },
                    "leave_taken": {
                    	required: "Leave Taken is required field."
                    },
                    "leave_remaining": {
                    	required: "Leave Remaining is required field."
                    }
                }
            });
        });

        function getleave(){
        	var total_leave = $("#leave_total").val();
        	var taken_leave = $("#leave_taken").val();

        	var remaining_leave = parseInt(total_leave) - parseInt(taken_leave);
        	if (remaining_leave > 0 || remaining_leave < 0) {
        		$("#leave_remaining").val(remaining_leave);
        	}
        	else if(remaining_leave == 0){
        		$("#leave_remaining").val(0);
        	}
        	else{
        		$("#leave_remaining").val(total_leave);
        	}
        }
    </script>
@endsection