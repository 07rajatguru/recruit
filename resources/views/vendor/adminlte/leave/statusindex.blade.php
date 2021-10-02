@extends('adminlte::page')

@section('title', 'Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('leave.add') }}">Add New Leave Application</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Leave Applications ({{ $count }})</h2>
            </div>
        </div>
    </div>

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
        <div class="col-md-12">
            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status','pending') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status','approved') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status','not-approved') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#F08080;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Not Approved ({{ $not_approved }})</div></a>
            </div>
        </div>
    </div><br/>

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

                            <a class="fa fa-edit" title="edit" href="{{ route('leave.edit',$value['id']) }}"></a>

                            @permission(('leave-delete'))
                                @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'leave','display_name'=>'Leave Application'])
                            @endpermission

                            @include('adminlte::partials.sendLeaveEmail', ['data' => $value, 'name' => 'leave'])
                        </td>
		    			<td>{{ $value['user_name'] }}</td>
		    			<td>{{ $value['subject'] }}</td>
		    			<td>{{ $value['from_date'] }}</td>
		    			<td>{{ $value['to_date'] }}</td>
		    			<td>{{ $value['leave_type'] }}</td>
		    			<td>{{ $value['leave_category'] }}</td>

		    			@if($value['status'] == 0)
		    				<td style="background-color:#8FB1D5;">Pending</td>
		    			@elseif($value['status'] == 1)
		    				<td style="background-color:#32CD32;">Approved</td>
		    			@elseif($value['status'] == 2)
		    				<td style="background-color:#F08080;">Upapproved</td>
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
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection