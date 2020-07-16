@extends('adminlte::page')

@section('title', 'Leave Balance')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Wise Leave Balance</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('leave.userwisecreate') }}"> Add User Leave Balance</a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"" id="user_leave_table">
    		<thead>
    			<tr>
	    			<th>No</th>
                    <th>Action</th>
                    <th>User Name</th>
                    <th>Total Leave</th>
                    <th>Taken Leave</th>
                    <th>Remaining Leave</th>
	    		</tr>
    		</thead>
    		<?php $i=0; ?>
    		<tbody>
    			@foreach($user_leave_data as $key => $value)
	    			<tr>
		    			<td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-edit" href="{{ route('leave.userwiseedit',$value['id']) }}" title="Edit"></a>
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'leaveuserwise','display_name'=>'User Leave Balance'])
                        </td>
		    			<td>{{ $value['user_name'] }}</td>
		    			<td>{{ $value['leave_total'] }}</td>
		    			<td>{{ $value['leave_taken'] }}</td>
		    			<td>{{ $value['leave_remaining'] }}</td>
		    		</tr>
    			@endforeach
    		</tbody>		
    	</table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#user_leave_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });
            
            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection