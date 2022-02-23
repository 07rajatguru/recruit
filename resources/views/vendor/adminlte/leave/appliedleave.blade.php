@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
@endsection

@extends('adminlte::page')

@section('title', 'Applied Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="pending active">
                <a href="#div_pending_leave_table" role="tab" data-toggle="tab"  style="font-size:15px;color: black;" title="Pending"><b>Pending</b></a>
            </li>

            <li role="presentation" class="approved">
                <a href="#div_approved_leave_table" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Approved"><b>Approved</b></a>
            </li>

            <li role="presentation" class="rejected">
                <a href="#div_rejected_leave_table" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Rejected"><b>Rejected</b></a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane pending active" id="div_pending_leave_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Leave Applications ({{ $pending_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="pending_leave_table">
            	<thead>
            		<tr>
        	    		<th width="5%">No</th>
                        <th width="5%">Action</th>
                        <th>User Name</th>
                        <th>Subject</th>
                        <th>From date</th>
                        <th>To Date</th>
                        <th>Leave Type</th>
                        <th>Leave Category</th>
                        <th>Status</th>
        	    	</tr>
            	</thead>
                <?php $i=0; ?>
            	<tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_pending_leave_details) && sizeof($team_pending_leave_details) > 0)
                            @foreach($team_pending_leave_details as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" title="Show" href="{{ route('leave.reply',$value['id']) }}"></a>
                                    </td>

                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
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
                        @if(isset($all_pending_leave_details) && sizeof($all_pending_leave_details) > 0)
                            @foreach($all_pending_leave_details as $key => $value)
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
                    @else
                        @if(isset($pending_leave_details) && sizeof($pending_leave_details) > 0)
                    		@foreach($pending_leave_details as $key => $value)
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
                    @endif
            	</tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane approved" id="div_approved_leave_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Leave Applications ({{ $approved_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="approved_leave_table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="5%">Action</th>
                        <th>User Name</th>
                        <th>Subject</th>
                        <th>From date</th>
                        <th>To Date</th>
                        <th>Leave Type</th>
                        <th>Leave Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php $i=0; ?>
                <tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_approved_leave_details) && sizeof($team_approved_leave_details) > 0)
                            @foreach($team_approved_leave_details as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" title="Show" href="{{ route('leave.reply',$value['id']) }}"></a>
                                    </td>

                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
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
                        @if(isset($all_approved_leave_details) && sizeof($all_approved_leave_details) > 0)
                            @foreach($all_approved_leave_details as $key => $value)
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
                    @else
                        @if(isset($approved_leave_details) && sizeof($approved_leave_details) > 0)
                            @foreach($approved_leave_details as $key => $value)
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
                    @endif
                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane rejected" id="div_rejected_leave_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Leave Applications ({{ $rejected_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="rejected_leave_table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="5%">Action</th>
                        <th>User Name</th>
                        <th>Subject</th>
                        <th>From date</th>
                        <th>To Date</th>
                        <th>Leave Type</th>
                        <th>Leave Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php $i=0; ?>
                <tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_rejected_leave_details) && sizeof($team_rejected_leave_details) > 0)
                            @foreach($team_rejected_leave_details as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" title="Show" href="{{ route('leave.reply',$value['id']) }}"></a>
                                    </td>

                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
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
                        @if(isset($all_rejected_leave_details) && sizeof($all_rejected_leave_details) > 0)
                            @foreach($all_rejected_leave_details as $key => $value)
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
                    @else
                        @if(isset($rejected_leave_details) && sizeof($rejected_leave_details) > 0)
                            @foreach($rejected_leave_details as $key => $value)
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
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var pending_leave_table = jQuery('#pending_leave_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! pending_leave_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( pending_leave_table );
            }

            var approved_leave_table = jQuery('#approved_leave_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! approved_leave_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( approved_leave_table );
            }

            var rejected_leave_table = jQuery('#rejected_leave_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! rejected_leave_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( rejected_leave_table );
            }
        });
    </script>
@endsection