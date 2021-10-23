@extends('adminlte::page')

@section('title', 'User Leave Balance Add')

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

	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	            <h2>Add User Leave Balance</h2>  
	        </div>
	        <div class="pull-right">
	            <a class="btn btn-primary" href="{{ route('leave.userwise') }}">Back</a>
	        </div>
	    </div>
	</div>

	{!! Form::open(array('route' => 'leave.userwisestore','method'=>'POST','id' => 'user_leave_data','files' => true)) !!}

	<div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
	            <div class="box-header with-border col-md-6"></div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	            	<div class="form-group">
	                    <strong>Select User : <span class = "required_fields">*</span> </strong>
	                    {!! Form::select('user_id',$users, null, array('id'=>'user_id','class' => 'form-control', 'tabindex' => '1' )) !!}
	                </div>
	            </div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group {{ $errors->has('leave_total') ? 'has-error' : '' }}">
	                            <strong>Total Paid Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_total',null, array('id'=>'leave_total','placeholder' => 'Total Paid Leave','class' => 'form-control','tabindex' => '2', 'onchange' => 'setPaidLeaveBalance()')) !!}
	                            @if ($errors->has('leave_total'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_total') }}</strong>
		                            </span>
	                            @endif
	                        </div>
	                	</div>
	                </div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	                	<div class="">
	            			<div class="form-group {{ $errors->has('leave_taken') ? 'has-error' : '' }}">
	                            <strong>Taken Paid Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_taken',null, array('id'=>'leave_taken','placeholder' => 'Taken Paid Leave','class' => 'form-control','tabindex' => '3', 'onchange' => 'setPaidLeaveBalance()')) !!}
	                            @if ($errors->has('leave_taken'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_taken') }}</strong>
		                            </span>
	                            @endif
	                        </div>
	            		</div>
	            	</div>

	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group {{ $errors->has('leave_remaining') ? 'has-error' : '' }}">
	                            <strong>Remaining Paid Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_remaining',null, array('id'=>'leave_remaining','placeholder' => 'Remaining Paid Leave','class' => 'form-control','tabindex' => '4')) !!}
	                            @if ($errors->has('leave_remaining'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('leave_remaining') }}</strong>
		                            </span>
	                            @endif
	                        </div>
	                    </div>
	            	</div>
	            </div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	                        <div class="form-group {{ $errors->has('seek_leave_total') ? 'has-error' : '' }}">
	                            <strong>Total Seek Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_total',null, array('id'=>'seek_leave_total','placeholder' => 'Total Seek Leave','class' => 'form-control','tabindex' => '5', 'onchange' => 'setSeekLeaveBalance()')) !!}
	                            @if ($errors->has('seek_leave_total'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('seek_leave_total') }}
		                            	</strong>
		                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group {{ $errors->has('seek_leave_taken') ? 'has-error' : '' }}">
	                            <strong>Taken Seek Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_taken',null, array('id'=>'seek_leave_taken','placeholder' => 'Taken Seek Leave','class' => 'form-control','tabindex' => '6', 'onchange' => 'setSeekLeaveBalance()')) !!}
	                            @if ($errors->has('seek_leave_taken'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('seek_leave_taken') }}
		                            	</strong>
		                            </span>
	                            @endif
	                        </div>
	            		</div>
	            	</div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	                        <div class="form-group {{ $errors->has('seek_leave_remaining') ? 'has-error' : '' }}">
	                            <strong>Remaining Seek Leave : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_remaining',null, array('id'=>'seek_leave_remaining','placeholder' => 'Remaining Seek Leave','class' => 'form-control','tabindex' => '7')) !!}
	                            @if ($errors->has('seek_leave_remaining'))
	                                <span class="help-block">
		                            	<strong>{{ $errors->first('seek_leave_remaining') }}
		                            	</strong>
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
                    },
                    "seek_leave_total": {
                        required: true
                    },
                    "seek_leave_taken": {
                    	required: true
                    },
                    "seek_leave_remaining": {
                    	required: true
                    }
                },
                messages: {
                    "user_id": {
                        required: "User is Required field."
                    },
                    "leave_total": {
                        required: "Total Paid Leave is Required field."
                    },
                    "leave_taken": {
                    	required: "Taken Paid Leave is Required field."
                    },
                    "leave_remaining": {
                    	required: "Remaining Paid Leave is Required field."
                    },
                    "seek_leave_total": {
                        required: "Total Seek Leave is Required field."
                    },
                    "seek_leave_taken": {
                    	required: "Taken Seek Leave is Required field."
                    },
                    "seek_leave_remaining": {
                    	required: "Remaining Seek Leave is Required field."
                    }
                }
            });
        });

        function setPaidLeaveBalance() {

        	var leave_total = $("#leave_total").val();
        	var leave_taken = $("#leave_taken").val();

        	var remaining_leave = leave_total - leave_taken;

        	if (remaining_leave > 0 || remaining_leave < 0) {
        		$("#leave_remaining").val(remaining_leave);
        	}
        	else if(remaining_leave == 0) {
        		$("#leave_remaining").val(0);
        	}
        	else{
        		$("#leave_remaining").val(leave_total);
        	}
        }

        function setSeekLeaveBalance() {

        	var seek_leave_total = $("#seek_leave_total").val();
        	var seek_leave_taken = $("#seek_leave_taken").val();

        	var remaining_leave = seek_leave_total - seek_leave_taken;

        	if (remaining_leave > 0 || remaining_leave < 0) {
        		$("#seek_leave_remaining").val(remaining_leave);
        	}
        	else if(remaining_leave == 0){
        		$("#seek_leave_remaining").val(0);
        	}
        	else{
        		$("#seek_leave_remaining").val(seek_leave_total);
        	}
        }
    </script>
@endsection