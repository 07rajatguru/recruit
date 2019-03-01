@extends('adminlte::page')

@section('title', 'User Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Leave Balance</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('leave.add') }}"> Add Leave Application</a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $leave_balance->leave_total or 0 }}</h3>
                    <p>No. of Leave</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $leave_balance->leave_taken or 0 }}</h3>
                    <p>No. of Leave Taken</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $leave_balance->leave_remaining or 0 }}</h3>
                    <p>No. of Leave Remainings</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Leave Applications</h2>
            </div>
        </div>
    </div>

    <div class="table-responsive">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"" id="leave_table">
    		<thead>
    			<tr>
	    			<th>No</th>
                    <th width="40px">Action</th>
                    <th>User Name</th>
                    <th>Sujbect</th>
                    <th>From date</th>
                    <th>To Date</th>
                    <th>Leave Type</th>
                    <th>Leave Category</th>
                    <th>Status</th>
	    		</tr>
    		</thead>
    		<?php $i=0; ?>
    		<tbody>
    			@foreach($leave_details as $key => $value)
	    			<tr>
		    			<td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" title="Show" href="{{ route('leave.reply',$value['id']) }}"></a>
                        </td>
		    			<td>{{ $value['user_name'] }}</td>
		    			<td>{{ $value['subject'] }}</td>
		    			<td>{{ $value['from_date'] }}</td>
		    			<td>{{ $value['to_date'] }}</td>
		    			<td>{{ $value['leave_type'] }}</td>
		    			<td>{{ $value['leave_category'] }}</td>
		    			@if($value['status'] == 0)
		    				<td>Pending</td>
		    			@elseif($value['status'] == 1)
		    				<td>Approved</td>
		    			@elseif($value['status'] == 2)
		    				<td>Upapproved</td>
		    			@endif
		    		</tr>
    			@endforeach
    		</tbody>		
    	</table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#leave_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection