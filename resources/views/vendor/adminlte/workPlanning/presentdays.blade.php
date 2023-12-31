@extends('adminlte::page')

@section('title', 'Present Days')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Present Days ({{ $count or '0' }})</h2>
        </div>
        <div class="pull-right">
        </div>
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

        @if(isset($work_planning_res) && $work_planning_res != '')
            @foreach ($work_planning_res as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>
                    </td>

                    @if($value['status'] == 0)
                        <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                    @elseif($value['status'] == 1)
                        <td style="background-color:#32CD32;">{{ $value['added_date'] }}</td>
                    @else
                        <td style="background-color:#FF3C28;">{{ $value['added_date'] }}</td>
                    @endif
     
                    <td>{{ $value['added_by'] }}</td>
                    <td>{{ $value['work_type'] }}</td>

                    @if($value['actual_login_time'] > '10:30:00')
                        <td style="background-color:lightpink;">{{ $value['loggedin_time'] }}
                        </td>
                    @elseif($value['total_projected_time'] >= '08:30:00')
                        <td style="background-color:#B0E0E6;">{{ $value['loggedin_time'] }}
                        </td>
                    @elseif($value['total_projected_time'] == '07:00:00')
                        <td style="background-color:#fff59a;">{{ $value['loggedin_time'] }}
                        </td>
                    @elseif($value['total_projected_time'] < '08:00:00')
                        <td style="background-color:#F08080;">{{ $value['loggedin_time'] }}
                        </td>
                    @else
                        <td>{{ $value['loggedin_time'] }}</td>
                    @endif

                    <td>{{ $value['loggedout_time'] }}</td>
                    <td>{{ $value['work_planning_time'] }}</td>
                    <td>{{ $value['work_planning_status_time'] }}</td>
                </tr>
            @endforeach
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
                "order" : [2,'desc'],
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