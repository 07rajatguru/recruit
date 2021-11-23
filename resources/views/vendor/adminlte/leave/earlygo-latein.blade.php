@extends('adminlte::page')

@section('title', 'Early Go - Late In')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Early Go - Late In Leave Applications ({{ $count }})</h2>
            </div>
            <div class="pull-right">
                
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="leave_table">
    	<thead>
    		<tr>
	    		<th width="5%">No</th>
                <th width="5%">Action</th>
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
            @if(isset($leave_details) && sizeof($leave_details) > 0)
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
    		    			<td style="background-color:#8FB1D5;">Pending</td>
    		    		@elseif($value['status'] == 1)
    		    			<td style="background-color:#32CD32;">Approved</td>
    		    		@elseif($value['status'] == 2)
    		    			<td style="background-color:#F08080;">Rejected</td>
    		    		@endif
    		    	</tr>
        		@endforeach
            @endif
    	</tbody>		
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#leave_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection