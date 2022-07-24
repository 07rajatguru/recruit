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

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Wise Leave Balance</h2>
            </div>
            <div class="pull-right">
                {{-- <a class="btn btn-success" href="{{ route('leave.userwisecreate') }}">Add Leave Balance
                </a> --}}
                <a class="btn btn-primary" href="monthwise-leave-balance" target="_blank">View Monthwise Balance</a>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_leave_table">
    	<thead>
    		<tr>
	    		<th>No</th>
                <th width="15%">User Name</th>
                <th>Total PL</th>
                <th>Opted PL</th>
                <th>PL Balance</th>
                <th>Total SL</th>
                <th>Opted SL</th>
                <th>SL Balance</th>
	    	</tr>
    	</thead>

    	<?php $i=0; ?>
    	<tbody>
    		@foreach($leave_balance_data as $key => $value)
	    		<tr>
		    		<td>{{ ++$i }}</td>
		    		<td>{{ $value['user_name'] }}</td>
		    		<td>{{ $value['pl_total'] }}</td>
		    		<td>{{ $value['pl_taken'] }}</td>
		    		<td>{{ $value['pl_remaining'] }}</td>
                    <td>{{ $value['sl_total'] }}</td>
                    <td>{{ $value['sl_taken'] }}</td>
                    <td>{{ $value['sl_remaining'] }}</td>
		    	</tr>
    		@endforeach
    	</tbody>		
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#user_leave_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });
            
            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection