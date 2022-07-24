@extends('adminlte::page')

@section('title', 'User Leave Balance')

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
	            <h2>Edit User Leave Balance</h2>  
	        </div>
	        <div class="pull-right">
	            <a class="btn btn-primary" href="{{ route('monthwise.leavebalance') }}">Back</a>
	        </div>
	    </div>
	</div>

	{!! Form::model($leave_data,['route' => ['leave.userwiseupdate', $leave_data->id],'method'=>'PATCH','id' => 'user_leave_data','files' => true]) !!}

	<div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
	            <div class="box-header with-border col-md-6"></div>

	            <div class="col-xs-12 col-sm-12 col-md-12">

	            	<div class="col-xs-4 col-sm-4 col-md-4">
		            	<div class="form-group">
		                    <strong>User : <span class = "required_fields">*</span> </strong>
		                    {!! Form::select('user_id',$users, $user_id, array('id'=>'user_id','class' => 'form-control', 'tabindex' => '1')) !!}
		                </div>
		            </div>

		            <div class="col-xs-4 col-sm-4 col-md-4">
			            <div class="form-group">
			            	<strong>Month : <span class = "required_fields">*</span> </strong>
			                {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control', 'tabindex' => '2')) }}
			            </div>
			        </div>
		      
		      		<div class="col-xs-4 col-sm-4 col-md-4">
			            <div class="form-group">
			            	<strong>Year : <span class = "required_fields">*</span> </strong>
			                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control', 'tabindex' => '3')) }}
			            </div>
			        </div>
			    </div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group">
	                            <strong>Total PL : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_total',null, array('id'=>'leave_total','placeholder' => 'Total PL','class' => 'form-control','tabindex' => '4', 'onchange' => 'setPrivilegeLeaveBalance()')) !!}
	                        </div>
	                	</div>
	                </div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	                	<div class="">
	            			<div class="form-group">
	                            <strong>Opted PL : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_taken',null, array('id'=>'leave_taken','placeholder' => 'Opted PL','class' => 'form-control','tabindex' => '5', 'onchange' => 'setPrivilegeLeaveBalance()')) !!}
	                        </div>
	            		</div>
	            	</div>

	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group">
	                            <strong>PL Balance : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('leave_remaining',null, array('id'=>'leave_remaining','placeholder' => 'PL Balance','class' => 'form-control','tabindex' => '6')) !!}
	                        </div>
	                    </div>
	            	</div>
	            </div>

	            <div class="col-xs-12 col-sm-12 col-md-12">
	            	<div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	                        <div class="form-group">
	                            <strong>Total SL : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_total',null, array('id'=>'seek_leave_total','placeholder' => 'Total SL','class' => 'form-control','tabindex' => '7', 'onchange' => 'setSickLeaveBalance()')) !!}
	                        </div>
	                    </div>
	                </div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	            			<div class="form-group">
	                            <strong>Opted SL : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_taken',null, array('id'=>'seek_leave_taken','placeholder' => 'Opted SL','class' => 'form-control','tabindex' => '8', 'onchange' => 'setSickLeaveBalance()')) !!}
	                        </div>
	            		</div>
	            	</div>

	                <div class="col-xs-4 col-sm-4 col-md-4">
	            		<div class="">
	                        <div class="form-group">
	                            <strong>SL Balance : <span class = "required_fields">*</span> </strong>
	                            {!! Form::text('seek_leave_remaining',null, array('id'=>'seek_leave_remaining','placeholder' => 'SL Balance','class' => 'form-control','tabindex' => '9')) !!}
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
            $("#month").select2();
            $("#year").select2();

            $('#user_id option:not(:selected)').prop('disabled', true);
            $('#month option:not(:selected)').prop('disabled', true);
            $('#year option:not(:selected)').prop('disabled', true);

            $("#user_leave_data").validate({
                rules: {

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
                	
                    "leave_total": {
                        required: "Total Privilege Leave is Required field."
                    },
                    "leave_taken": {
                    	required: "Taken Privilege Leave is Required field."
                    },
                    "leave_remaining": {
                    	required: "Remaining Privilege Leave is Required field."
                    },
                    "seek_leave_total": {
                        required: "Total Sick Leave is Required field."
                    },
                    "seek_leave_taken": {
                    	required: "Taken Sick Leave is Required field."
                    },
                    "seek_leave_remaining": {
                    	required: "Remaining Sick Leave is Required field."
                    }
                }
            });
        });

        function setPrivilegeLeaveBalance() {

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

        function setSickLeaveBalance() {

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