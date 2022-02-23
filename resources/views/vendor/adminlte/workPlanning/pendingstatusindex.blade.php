@extends('adminlte::page')

@section('title', 'Pending Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Pending Work Planning ({{ $count or '0' }})</h2>
        </div>
        <div class="pull-right"></div>
    </div>
</div>

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table">
    <thead>
        <tr>
	       <th width="5%">No</th>
           <th width="5%">Action</th>
           <th>Date</th>
           <th>Username</th>
           <th>Work Location</th>
           <th>Logged-in Time</th>
	       <th>Logged-out Time</th>
           <th>Work Planning Time</th>
           <th>Status Time</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>

        @if($user_id == $super_admin_userid)
            @if(isset($team_work_planning_res) && $team_work_planning_res != '')
                @foreach ($team_work_planning_res as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>
                        </td>

                        <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                        <td style="background-color:#C4D79B;">{{ $value['added_by'] }}</td>
                        <td>{{ $value['work_type'] }}</td>

                        @if($value['added_day'] == 'Saturday')

                            @if($value['actual_login_time'] > '10:30:00')
                                <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '')
                                <td>{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] >= '06:00:00')
                                <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 06:00">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] == '04:30:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] < '05:30:00')
                                <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 05:30">{{ $value['loggedin_time'] }}
                                </td>
                            @else
                                <td>{{ $value['loggedin_time'] }}</td>
                            @endif

                        @else

                            @if($value['added_by_id'] == $manager_user_id)

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '07:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 07:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '06:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '07:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 07:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @else

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '08:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 08:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '07:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '08:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 08:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @endif
                        @endif

                        <td>{{ $value['loggedout_time'] }}</td>
                        <td>{{ $value['added_date'] }} - {{ $value['work_planning_time'] }}</td>

                        @if(isset($value['status_date']) && $value['status_date'] != '')
                            <td>{{ $value['status_date'] }} - {{ $value['work_planning_status_time'] }}</td>
                        @else
                            <td>{{ $value['work_planning_status_time'] }}</td>
                        @endif
                    </tr>
                @endforeach
            @endif

            @if(isset($all_work_planning_res) && $all_work_planning_res != '')
                @foreach ($all_work_planning_res as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>
                        </td>

                        <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                        <td>{{ $value['added_by'] }}</td>
                        <td>{{ $value['work_type'] }}</td>

                        @if($value['added_day'] == 'Saturday')

                            @if($value['actual_login_time'] > '10:30:00')
                                <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '')
                                <td>{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] >= '06:00:00')
                                <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 06:00">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] == '04:30:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] < '05:30:00')
                                <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 05:30">{{ $value['loggedin_time'] }}
                                </td>
                            @else
                                <td>{{ $value['loggedin_time'] }}</td>
                            @endif

                        @else

                            @if($value['added_by_id'] == $manager_user_id)

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '07:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 07:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '06:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '07:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 07:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @else

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '08:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 08:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '07:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '08:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 08:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @endif
                        @endif

                        <td>{{ $value['loggedout_time'] }}</td>
                        <td>{{ $value['added_date'] }} - {{ $value['work_planning_time'] }}</td>

                        @if(isset($value['status_date']) && $value['status_date'] != '')
                            <td>{{ $value['status_date'] }} - {{ $value['work_planning_status_time'] }}</td>
                        @else
                            <td>{{ $value['work_planning_status_time'] }}</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        @else

            @if(isset($work_planning_res) && $work_planning_res != '')
                @foreach ($work_planning_res as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>
                        </td>

                        <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                        <td>{{ $value['added_by'] }}</td>
                        <td>{{ $value['work_type'] }}</td>

                        @if($value['added_day'] == 'Saturday')

                            @if($value['actual_login_time'] > '10:30:00')
                                <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '')
                                <td>{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] >= '06:00:00')
                                <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 06:00">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] == '04:30:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}
                                </td>
                            @elseif($value['total_actual_time'] < '05:30:00')
                                <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 05:30">{{ $value['loggedin_time'] }}
                                </td>
                            @else
                                <td>{{ $value['loggedin_time'] }}</td>
                            @endif

                        @else

                            @if($value['added_by_id'] == $manager_user_id)

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '07:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 07:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '06:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '07:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 07:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @else

                                @if($value['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '')
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] >= '08:30:00')
                                    <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 08:30">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] == '07:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                                @elseif($value['total_actual_time'] < '08:00:00')
                                    <td style="background-color:#F08080;cursor: pointer;" title="Working Hours Less than 08:00">{{ $value['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $value['loggedin_time'] }}</td>
                                @endif
                            @endif
                        @endif

                        <td>{{ $value['loggedout_time'] }}</td>
                        <td>{{ $value['added_date'] }} - {{ $value['work_planning_time'] }}</td>

                        @if(isset($value['status_date']) && $value['status_date'] != '')
                            <td>{{ $value['status_date'] }} - {{ $value['work_planning_status_time'] }}</td>
                        @else
                            <td>{{ $value['work_planning_status_time'] }}</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        @endif
    </tbody>
</table>

@stop 

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#work_planning_table').DataTable({
                responsive: true,
                stateSave : true,
                "order" : [0,'asc'],
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection