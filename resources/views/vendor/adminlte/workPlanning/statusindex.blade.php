@extends('adminlte::page')

@section('title', 'Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Work Planning Sheet</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('workplanning.create') }}">Add Work Planning</a>
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

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
        </div>
    </div> 
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})</div>
            </a>
        </div>

        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div>
            </a>
        </div>

        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#FF3C28;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Rejected ({{ $rejected }})</div>
            </a>
        </div>

        <div class="col-md-2" style="width: 25%;">
            <a href="{{ route('workplanning.status',array('approval_post_discussion',$month,$year)) }}" style="text-decoration: none;color: black;">
                <div style="margin:5px;height:35px;background-color:#ffb347;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approval Post Discussion">Approval Post Discussion ({{ $approval_post_discussion }})</div>
            </a>
        </div>
    </div>
</div><br/>

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table">
    <thead>
        <tr>
	       <th width="5%">No</th>
           <th width="8%">Action</th>
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
                <?php

                    $added_date = date('Y-m-d',strtotime($value['added_date']));
                    $wfh_data = array();
                    $wfh_data = App\WorkFromHome::getWorkFromHomeRequestByDate($added_date,$value['added_by_id']);
                ?>
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>

                        <a class="fa fa-edit" href="{{ route('workplanning.edit',$value['id']) }}" title="Edit"></a>
                        
                        @permission(('work-planning-delete'))
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'workplanning','display_name'=>'Work Planning'])
                        @endpermission

                        @if($user_id == $value['added_by_id'])
                            @include('adminlte::partials.sendWorkPlanningReport', ['data' => $value, 'name' => 'workplanning'])
                        @endif
                    </td>

                    @if($value['status'] == 0)
                        <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                    @elseif($value['status'] == 1 && $value['post_discuss_status'] == 1)
                        <td style="background-color:#ffb347;">{{ $value['added_date'] }}</td>
                    @elseif($value['status'] == 1)
                        <td style="background-color:#32CD32;">{{ $value['added_date'] }}</td>
                    @else
                        <td style="background-color:#FF3C28;">{{ $value['added_date'] }}</td>
                    @endif

                    <td>{{ $value['added_by'] }}</td>

                    @if(isset($wfh_data) && sizeof($wfh_data) > 0)
                        <td style="background-color:#BEBEBE;cursor: pointer;" title="Work From Home Request">{{ $value['work_type'] }}</td>
                    @else
                        <td>{{ $value['work_type'] }}</td>
                    @endif

                    @if($value['added_day'] == 'Saturday')

                        @if($value['actual_login_time'] > '10:30:00')
                            <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                        @elseif($value['total_actual_time'] == '')
                            <td>{{ $value['loggedin_time'] }}</td>
                        @elseif($value['total_actual_time'] > '06:00:00')
                            <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 06:00">{{ $value['loggedin_time'] }}</td>
                        @elseif($value['total_actual_time'] == '04:30:00')
                            <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                        @elseif($value['total_actual_time'] < '05:30:00')
                            <td style="background-color:#d99594;cursor: pointer;" title="Working Hours Less than 05:30">{{ $value['loggedin_time'] }}</td>
                        @else
                            <td>{{ $value['loggedin_time'] }}</td>
                        @endif
                            
                    @else

                        @if($value['added_by_id'] == $manager_user_id)

                            @if($value['actual_login_time'] > '10:30:00')
                                <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '')
                                <td>{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] > '07:30:00')
                                <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 07:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '06:00:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] < '07:00:00')
                                <td style="background-color:#d99594;cursor: pointer;" title="Working Hours Less than 07:00">{{ $value['loggedin_time'] }}</td>
                            @else
                                <td>{{ $value['loggedin_time'] }}</td>
                            @endif
                        @else

                            @if($value['actual_login_time'] > '10:30:00')
                                <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '')
                                <td>{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] > '08:30:00')
                                <td style="background-color:#B0E0E6;cursor: pointer;" title="Working Hours More than 08:30">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] == '07:00:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Late in / Early Go">{{ $value['loggedin_time'] }}</td>
                            @elseif($value['total_actual_time'] < '08:00:00')
                                <td style="background-color:#d99594;cursor: pointer;" title="Working Hours Less than 08:00">{{ $value['loggedin_time'] }}</td>
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
    </tbody>
</table>

<input type="hidden" name="status" id="status" value="{{ $status }}">
<input type="hidden" name="post_discuss_status" id="post_discuss_status" value="{{ $post_discuss_status }}">

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

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();
            var status = $("#status").val();
            var post_discuss_status = $("#post_discuss_status").val();

            if(status == '0') {
                status = 'pending';
            }
            else if(status == '1' && post_discuss_status == '1') {
                status = 'approval_post_discussion';
            }
            else if(status == '1') {
                status = 'approved';
            }
            else if(status == '2') {
                status = 'rejected';
            }

            var url = app_url+'/work-planning/'+status+'/'+month+'/'+year;            
            
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '<input type="hidden" name="status" value="'+status+'" />' +
                '<input type="hidden" name="post_discuss_status" value="'+post_discuss_status+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection