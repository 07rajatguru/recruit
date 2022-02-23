@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
@endsection

@extends('adminlte::page')

@section('title', 'Work From Home Request')

@section('content_header')
@stop

@section('content')
    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="pending active">
                <a href="#div_pending_requests_table" role="tab" data-toggle="tab"  style="font-size:15px;color: black;" title="Pending"><b>Pending</b></a>
            </li>

            <li role="presentation" class="approved">
                <a href="#div_approved_requests_table" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Approved"><b>Approved</b></a>
            </li>

            <li role="presentation" class="rejected">
                <a href="#div_rejected_requests_table" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Rejected"><b>Rejected</b></a>
            </li>
        </ul>
    </div>
    
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane pending active" id="div_pending_requests_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Work From Home Request ({{ $pending_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="pending_requests_table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%">Action</th>
                        <th width="11%">From Date</th>
                        <th width="11%">To Date</th>
                        <th width="13%">Username</th>
                        <th>Subject</th>
                        <th width="11%">Status</th>
                    </tr>
                </thead>
                <?php $i=0; ?>
                <tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_pending_wfh_requests) && sizeof($team_pending_wfh_requests) > 0)
                            @foreach($team_pending_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($all_pending_wfh_requests) && sizeof($all_pending_wfh_requests) > 0)
                            @foreach($all_pending_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($pending_wfh_requests) && sizeof($pending_wfh_requests) > 0)
                            @foreach($pending_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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

        <div role="tabpanel" class="tab-pane approved" id="div_approved_requests_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Work From Home Request ({{ $approved_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="approved_requests_table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%">Action</th>
                        <th width="11%">From Date</th>
                        <th width="11%">To Date</th>
                        <th width="13%">Username</th>
                        <th>Subject</th>
                        <th width="11%">Status</th>
                    </tr>
                </thead>
                <?php $i=0; ?>
                <tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_approved_wfh_requests) && sizeof($team_approved_wfh_requests) > 0)
                            @foreach($team_approved_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($all_approved_wfh_requests) && sizeof($all_approved_wfh_requests) > 0)
                            @foreach($all_approved_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($approved_wfh_requests) && sizeof($approved_wfh_requests) > 0)
                            @foreach($approved_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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

        <div role="tabpanel" class="tab-pane rejected" id="div_rejected_requests_table">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Work From Home Request ({{ $rejected_count }})</h2>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="rejected_requests_table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%">Action</th>
                        <th width="11%">From Date</th>
                        <th width="11%">To Date</th>
                        <th width="13%">Username</th>
                        <th>Subject</th>
                        <th width="11%">Status</th>
                    </tr>
                </thead>
                <?php $i=0; ?>
                <tbody>
                    @if($user_id == $super_admin_userid)
                        @if(isset($team_rejected_wfh_requests) && sizeof($team_rejected_wfh_requests) > 0)
                            @foreach($team_rejected_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td style="background-color:#C4D79B;">{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($all_rejected_wfh_requests) && sizeof($all_rejected_wfh_requests) > 0)
                            @foreach($all_rejected_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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
                        @if(isset($rejected_wfh_requests) && sizeof($rejected_wfh_requests) > 0)
                            @foreach($rejected_wfh_requests as $key => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                                    </td>

                                    <td>{{ $value['from_date'] }}</td>
                                    <td>{{ $value['to_date'] }}</td>
                                    <td>{{ $value['user_name'] }}</td>
                                    <td>{{ $value['subject'] }}</td>

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

            var pending_requests_table = jQuery('#pending_requests_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! pending_requests_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( pending_requests_table );
            }

            var approved_requests_table = jQuery('#approved_requests_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! approved_requests_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( approved_requests_table );
            }

            var rejected_requests_table = jQuery('#rejected_requests_table').DataTable({
                responsive: true,
                "pageLength": 25,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! rejected_requests_table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( rejected_requests_table );
            }
        });
    </script>
@endsection