@extends('adminlte::page')

@section('title', 'Team Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Work Planning Sheet</h2>
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
                <a href="{{ route('teamworkplanning.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})
                </div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('teamworkplanning.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('teamworkplanning.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#FF3C28;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Rejected ({{ $rejected }})</div></a>
            </div>

            <div class="col-md-2" style="width: 25%;">
                <a href="{{ route('teamworkplanning.status',array('approval_post_discussion',$month,$year)) }}" style="text-decoration: none;color: black;">
                    <div style="margin:5px;height:35px;background-color:#ffb347;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approval Post Discussion">Approval Post Discussion ({{ $approval_post_discussion }})</div>
                </a>
            </div>
        </div>
    </div><br/>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" style="border: 2px solid black;">
            <thead>
                <tr style="background-color: #7598d9">
        	       <th width="5%" style="border: 2px solid black;text-align: center;">No</th>
                   <th width="8%" style="border: 2px solid black;text-align: center;">Action</th>
                   <th style="border: 2px solid black;text-align: center;">Date</th>
                   <th style="border: 2px solid black;text-align: center;">Username</th>
                   <th style="border: 2px solid black;text-align: center;">Work Location</th>
                   <th style="border: 2px solid black;text-align: center;">Logged-in Time</th>
        	       <th style="border: 2px solid black;text-align: center;">Logged-out Time</th>
                   <th style="border: 2px solid black;text-align: center;">Work Planning Time</th>
                   <th style="border: 2px solid black;text-align: center;">Status Time</th>
                   <th style="border: 2px solid black;text-align: center;">Actual Working Hours</th>

        	    </tr>
            </thead>
            <?php $j = 0; ?>
            @if(isset($work_planning_res) && $work_planning_res != '')

                <?php
                    // Get All Saturday dates of current month
                    // $date = "$year-$month-01";
                    // $first_day = date('N',strtotime($date));
                    // $first_day = 6 - $first_day + 1;
                    // $last_day =  date('t',strtotime($date));
                    // $saturdays = array();

                    // for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
                    //     $saturdays[] = $i;
                    // }

                    // // Get Saturday Date
                    // if ($year == '2023' && $month == '01') {
                    //     $saturday_date = $year."-".$month."-".$saturdays[3];
                    // } else {
                    //     $saturday_date = $year."-".$month."-".$saturdays[2];
                    // }
                    $third_saturday = App\Date::getThirdSaturdayOfMonth($month,$year);
                    $saturday_date = $third_saturday['full_date'];

                    // Because start from August'22
                    $august_date = '2022-08-01';

                    // September Fixed Date
                    $fixed_date = '2022-09-10';

                    // Get Today's date
                    $today_date = date('Y-m-d');
                ?>

                @foreach($work_planning_res as $key => $value)
                    <?php
                        $i = 0;
                        $user = explode("-", $key);
                        $report_to_id = App\User::getReportsToById($user[0]);
                        $user_data = App\User::getAllDetailsByUserID($user[0]);
                        $type = $user_data['type'];
                    ?>

                    @if(isset($value) && sizeof($value) > 0)
                        <tbody>
                            @if(isset($report_to_id) && $report_to_id == $superadminuserid && $user_id == $superadminuserid)
                                <tr>
                                    <td colspan="9" style="text-align: center;background-color:#C4D79B;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                                </tr>
                            @elseif(isset($report_to_id) && $report_to_id != $superadminuserid && $user_id == $superadminuserid && (isset($type) && $type == '3'))
                                <tr>
                                    <td colspan="9" style="text-align: center;background-color:#F0E68C;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                                </tr>
                            @elseif(isset($report_to_id) && $report_to_id == $manager_user_id && $user_id == $manager_user_id)
                                <tr>
                                    <td colspan="9" style="text-align: center;background-color:#C4D79B;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                                </tr>
                            @elseif(isset($report_to_id) && $report_to_id != $superadminuserid)
                                <tr>
                                    <td colspan="10" style="text-align: center;background-color: #FABF8F;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                                </tr>
                            @else
                                 <tr>
                                    <td colspan="10" style="text-align: center;background-color: #FABF8F;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                                </tr>
                            @endif
                        </tbody>
                    @endif
                        
                    @if(isset($value) && sizeof($value) >0)  
                        <tbody id="data_{{$j}}" style="display:none;">
                            @foreach($value as $k => $v)
                                <?php

                                    $holiday_data = array();
                                    $half_day_leave_data = array();
                                    $leave_data = array();
                                    $unapproved_leave_data = array();
                                    $wfh_data = array();

                                    $added_date = date('Y-m-d',strtotime($v['added_date']));
                                    $holiday_data = App\Holidays::getHolidayByDateAndID($added_date,$v['added_by_id'],'');

                                    $half_day_leave_data = App\UserLeave::getLeaveByDateAndID($added_date,$v['added_by_id'],'1','Half Day');

                                    $leave_data = App\UserLeave::getLeaveByDateAndID($added_date,$v['added_by_id'],'1','Full Day');
                                    $unapproved_leave_data = App\UserLeave::getLeaveByDateAndID($added_date,$v['added_by_id'],'2','Full Day');
                                    
                                    $wfh_data = App\WorkFromHome::getWorkFromHomeRequestByDate($added_date,$v['added_by_id'],1);

                                    // For hide edit icon
                                    $edit_date = date('Y-m-d', strtotime($v['added_date'].'first day of +1 month'));
                                    $edit_date_valid = date('Y-m-d', strtotime($edit_date."+3days"));

                                    $added_day = date('l', strtotime($added_date));

                                ?>
                                <tr>

                        @if($v['added_day'] == 'Sunday')
                                    <td>{{ ++$i }}</td><td></td>

                              @if ($v['loggedin_time'] == '') 
                                    <td style="background-color:#ffc000;">{{ $v['added_date'] }}</td>
                                    <td colspan="7"><center><b>Sunday</b></center></td>
                              @else
                                    <td>{{ ++$i }}</td>
                                    <td>
                                    <a class="fa fa-circle" href="{{ route('workplanning.show', Crypt::encrypt($v['id'])) }}" title="Show"></a>
                                @if (date('Y-m-d') <= $edit_date_valid)
                                    <a class="fa fa-edit" href="{{ route('workplanning.edit', Crypt::encrypt($valvue['id'])) }}" title="Edit"></a>
                                @if ($user_id == $v['added_by_id'])
                                    @include('adminlte::partials.sendWorkPlanningReport', ['data' => $v, 'name' => 'workplanning'])
                                   @endif
                                @endif

                             @permission(('work-planning-delete'))
                                 @include('adminlte::partials.deleteModal', ['data' => $valvue, 'name' => 'workplanning', 'display_name' => 'Work Planning'])
                             @endpermission
                                 </td>
                                  @if ($v['status'] == 0)
                                    <td style="background-color:#8FB1D5;">{{ $v['added_date'] }}</td>
                                  @elseif ($v['status'] == 1 && $v['post_discuss_status'] == 1)
                                    <td style="background-color:#ffb347;">{{ $v['added_date'] }}</td>
                                  @elseif ($v['status'] == 1)
                                    <td style="background-color:#32CD32;">{{ $v['added_date'] }}</td>
                                  @else
                                    <td style="background-color:#FF3C28;">{{ $v['added_date'] }}</td>
                                  @endif
                                    <td>{{ $v['added_by'] }}
                                  @if (isset($wfh_data) && sizeof($wfh_data) > 0)
                                    <td style="background-color:#BEBEBE;cursor: pointer;" title="Work From Home">{{ $v['work_type'] }}</td>
                                  @else
                                    <td>{{ $v['work_type'] }}</td>
                                 @endif
                                    <td>{{ $v['loggedin_time'] }}</td>
                                    <td>{{ $v['loggedout_time'] }}</td>
                                    <td>{{ $v['work_planning_time'] }}</td>
                                    <td>{{ $v['work_planning_status_time'] }}</td>
                                    <td>{{ $v['total_actual_time'] }}</td>
                               @endif



                        @elseif($v['loggedin_time'] != '')

                                        <td>{{ ++$i }}</td>
                                        <td>
                                            <a class="fa fa-circle" href="{{ route('workplanning.show',\Crypt::encrypt($v['id'])) }}" title="Show">
                                            </a>
                                            
                                            @if($superadminuserid == $user_id)
                                                <a class="fa fa-edit" href="{{ route('workplanning.edit',\Crypt::encrypt($v['id'])) }}" title="Edit"></a>
                                            @else
                                                @if(date('Y-m-d') <= $edit_date_valid)
                                                    <a class="fa fa-edit" href="{{ route('workplanning.edit',\Crypt::encrypt($v['id'])) }}" title="Edit"></a>

                                                    @if($user_id == $v['added_by_id'])
                                                        @include('adminlte::partials.sendWorkPlanningReport', ['data' => $v, 'name' => 'workplanning'])
                                                    @endif
                                                @endif
                                            @endif
                                                
                                            @permission(('work-planning-delete'))
                                                @include('adminlte::partials.deleteModal', ['data' => $v, 'name' => 'workplanning','display_name'=>'Work Planning'])
                                            @endpermission
                                        </td>

                                        @if($v['status'] == 0)
                                            <td style="background-color:#8FB1D5;">
                                            {{ $v['added_date'] }}</td>
                                        @elseif($v['status'] == 1 && $v['post_discuss_status'] == 1)
                                            <td style="background-color:#ffb347;">
                                            {{ $v['added_date'] }}</td>
                                        @elseif($v['status'] == 1)
                                            <td style="background-color:#32CD32;">
                                            {{ $v['added_date'] }}</td>
                                        @else
                                            <td style="background-color:#FF3C28;">
                                            {{ $v['added_date'] }}</td>
                                        @endif

                                        <td>{{ $v['added_by'] }}</td>

                                        @if(isset($wfh_data) && sizeof($wfh_data) > 0)
                                            <td style="background-color:#BEBEBE;cursor: pointer;" title="Work From Home">{{ $v['work_type'] }}
                                            </td>
                                        @else
                                            <td>{{ $v['work_type'] }}</td>
                                        @endif

                                       @if($v['added_day'] == 'Saturday')
                                        @if($v['actual_login_time'] > '10:30:00')
                                            <td style="background-color:lightpink;cursor: pointer;" title="Login After 10:30">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] < '10:30:00')
                                            <td style="background-color:;cursor: pointer">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] == '04:30:00')
                                            <td style="background-color:#fff59a;cursor: pointer;" title="Late in">{{ $v['loggedin_time'] }}</td>
                                        @else
                                             <td>{{ $v['loggedin_time'] }}</td>
                                        @endif

                               @else

                              @if($v['added_by_id'] == $manager_user_id)                               
                                @if($v['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $v['loggedin_time'] }}</td>
                                @elseif($v['total_actual_time'] < '10:30:00')
                                    <td style="background-color:;cursor: pointer">{{ $v['loggedin_time'] }}</td>
                                @elseif($v['total_actual_time'] == '06:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in">{{ $v['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $v['loggedin_time'] }}</td>
                                @endif
                             @else
                                @if($v['actual_login_time'] > '10:30:00')
                                    <td style="background-color:lightpink;cursor: pointer" title="Login After 10:30">{{ $v['loggedin_time'] }}</td>
                                @elseif($v['actual_login_time'] < '10:30:00')
                                    <td style="background-color:;cursor: pointer">{{ $v['loggedin_time'] }}</td>
                                @elseif($v['total_actual_time'] == '07:00:00')
                                    <td style="background-color:#fff59a;cursor: pointer;" title="Late in">{{ $v['loggedin_time'] }}</td>
                                @else
                                    <td>{{ $v['loggedin_time'] }}</td>
                                @endif
                            @endif
                        @endif

                        @php
                           $loginTime = strtotime($v['loggedin_time']);
                           $logoutTime = strtotime($v['loggedout_time']);
                           $timeDifference = ($logoutTime - $loginTime) / 3600; // Difference in hours
                        @endphp

                        @if($v['added_day'] == 'Saturday')
                            @if(isset($half_day_leave_data) && $half_day_leave_data != '')
                                 <td style="background-color:#D8BFD8;cursor: pointer" title="HD">{{ $v['loggedout_time'] }}</td>
                            @elseif($timeDifference < 6 && $v['total_actual_time'] != '04:30:00')
                                <td style="background-color:#FF7E74;cursor: pointer;" title="Log-out Time < 6 hours">{{ $v['loggedout_time'] }}</td>
                            @elseif($timeDifference >= 6)
                                <td style="background-color: ;cursor: pointer;" title="Log-out Time > 6 hours">{{ $v['loggedout_time'] }}</td>
                            @elseif($v['total_actual_time'] == 4)
                                <td style="background-color:#fff59a;cursor: pointer;" title="Early Go">{{ $v['loggedout_time'] }}</td>
                            @else
                                <td>{{ $v['loggedout_time'] }}</td>
                            @endif
                        @else
                            @if(isset($half_day_leave_data) && $half_day_leave_data != '')
                                 <td style="background-color:#D8BFD8;cursor: pointer" title="HD">{{ $v['loggedout_time'] }}</td>
                            @elseif($timeDifference < 9  && $v['total_actual_time'] != '07:00:00')
                                <td style="background-color:#FF7E74;cursor: pointer;" title="Log-out Time < 9 hours">{{ $v['loggedout_time'] }}</td>
                            @elseif($timeDifference >= 9)
                                <td style="background-color: ;cursor: pointer;" title="Log-out Time > 9 hours">{{ $v['loggedout_time'] }}</td>
                            @elseif($v['total_actual_time'] == '07:00:00')
                                <td style="background-color:#fff59a;cursor: pointer;" title="Early Go">{{ $v['loggedout_time'] }}</td>
                            @else
                                <td>{{ $v['loggedout_time'] }}</td>
                          @endif
                        @endif

                        @if($v['loggedin_time'] != '' && $v['work_planning_time'] != '')
                            @php
                                $loginTime = strtotime($v['loggedin_time']);
                                $workPlanningTime = strtotime($v['work_planning_time']);
                                $timeDifference = ($workPlanningTime - $loginTime) / 60; // Difference in minutes
                            @endphp
                            @if($timeDifference <= 60)
                                <td>{{ $v['created_at'] }} - {{ $v['work_planning_time'] }}</td>
                            @else
                                <td style="background-color: #FFBB70;" title="Work Planning Delayed">{{ $v['created_at'] }} - {{ $v['work_planning_time'] }}</td>
                            @endif
                        @endif

                        @if(isset($v['status_date']) && $v['status_date'] != '')
                            <td >{{ $v['status_date'] }} - {{ $v['work_planning_status_time'] }}</td>
                        @else
                            <td style="background-color: #FFBB70;" title="Status Not Updated">{{ $v['work_planning_status_time'] }}</td>
                        @endif

                        @if($v['added_day'] == 'Saturday')
                            @if(isset($half_day_leave_data) && $half_day_leave_data != '')
                                 <td style="background-color:#D8BFD8;cursor: pointer" title="HD">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] > '05:00:00')
                                <td style="background-color:#90EE90;cursor: pointer;" title="Working Hours > 6 hours">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] < '05:00:00')
                                <td style="background-color:#E2A76F ;cursor: pointer;" title=" Working Hours < 6 hours">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] == '05:00:00')
                                <td style="background-color:;cursor: pointer;" >{{ $v['total_actual_time'] }}</td>
                            @else
                                <td>{{ $v['total_actual_time'] }}</td>
                            @endif
                        @else
                            @if(isset($half_day_leave_data) && $half_day_leave_data != '')
                                 <td style="background-color:#D8BFD8;cursor: pointer" title="HD">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] > '08:00:00')
                                <td style="background-color:#90EE90;cursor: pointer;" title="Working Hours > 8 hours">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] < '08:00:00')
                                <td style="background-color:#E2A76F ;cursor: pointer;" title="Working Hours < 8 hours">{{ $v['total_actual_time'] }}</td>
                            @elseif($v['total_actual_time'] == '08:00:00')
                                <td style="background-color:;cursor: pointer;" >{{ $v['total_actual_time'] }}</td>
                            @else
                                <td>{{ $v['total_actual_time'] }}</td>
                          @endif
                        @endif

                       @elseif(isset($leave_data) && $leave_data != '')

                            <td>{{ ++$i }}</td><td></td>
                                     @php
                                         $next_day = date('Y-m-d', strtotime($v['added_date'] . ' +1 day'));
                                         $next_leave_data = App\UserLeave::getLeaveByDateAndID($next_day, $v['added_by_id'], '1', 'Full Day');
              
                                         $sunday_day = date('Y-m-d', strtotime($v['added_date'] . ' +1 day'));
                                         $monday_day = date('Y-m-d', strtotime($v['added_date'] . ' +2 days'));
                                         $is_saturday_lwp = ($leave_data->category == 'LWP' && $v['added_day'] == 'Saturday');
                                         $is_monday_lwp = ($next_leave_data && $next_leave_data->category == 'LWP');

                                        // Check if Saturday or Sunday or Monday is LWP
                                         $is_weekend_lwp = ($is_saturday_lwp || $is_monday_lwp);
                           
                                     @endphp
  

                                        @if($is_weekend_lwp)
                                            <td style="background-color:#fd5e53;">{{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>LWP</b></center></td>
                                        @elseif($leave_data->category == 'Privilege Leave')
                                            <td style="background-color:#8db3e2;">
                                            {{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>{{ $leave_data->category }}</b></center></td>
                                        @elseif($leave_data->category == 'Sick Leave')
                                            <td style="background-color:#c075f8;">
                                            {{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>{{ $leave_data->category }}</b></center></td>
                                        @elseif($leave_data->category == 'LWP')
                                            <td style="background-color:#fd5e53;">
                                            {{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>{{ $leave_data->category }}</b></center></td>
                                        @else
                                            <td style="background-color:#fd5e53;">
                                            {{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>Absent</b></center></td>
                                        @endif

                                    @elseif(isset($unapproved_leave_data) && $unapproved_leave_data != '')

                                        <td>{{ ++$i }}</td><td></td>

                                        <td style="background-color:#fac090;">
                                        {{ $v['added_date'] }}</td>
                                        <td colspan="7"><center><b>{{ $unapproved_leave_data->category }} - Unapproved</b></center></td>

                                    @elseif(isset($holiday_data) && sizeof($holiday_data) > 0)

                                        @if($v['loggedin_time'] == '' && $added_date == $fixed_date)

                                            <td>{{ ++$i }}</td><td></td>
                                            <td style="background-color:#E6812F;">{{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>{{ $holiday_data['title'] }}
                                            </b></center></td>
                                        @else

                                            <td>{{ ++$i }}</td><td></td>
                                            <td style="background-color:#76933C;">{{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>{{ $holiday_data['title'] }} - ({{ $holiday_data['type'] }})</b></center></td>
                                        @endif

                                    @elseif($v['added_day'] == 'Saturday' && $v['loggedin_time'] == '' && $added_date == $saturday_date && $saturday_date > $august_date && $today_date > $saturday_date)

                                        <td>{{ ++$i }}</td><td></td>
                                        <td style="background-color:#ffc000;">
                                        {{ $v['added_date'] }}</td>
                                        <td colspan="7"><center><b>Saturday</b></center></td>

                                    @elseif($v['attendance'] == 'CO')

                                        <td>{{ ++$i }}</td><td></td>
                                        <td style="background-color:#eedc82;">{{ $v['added_date'] }}</td>
                                        <td colspan="7"><center><b>Compensatory Off</b></center>
                                        </td>

                                    @elseif(isset($half_day_leave_data) && $half_day_leave_data != '')
                                    @else
                                        @if($v['added_day'] == 'Saturday' && $v['loggedin_time'] == '' && $added_date == $saturday_date)
                                        @else
                                            <td>{{ ++$i }}</td><td></td>
                                            <td style="background-color:#fd5e53;">
                                            {{ $v['added_date'] }}</td>
                                            <td colspan="7"><center><b>Absent</b></center></td>
                                        @endif        
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                    <?php $j++;?>
                @endforeach
            @else
                <tbody>
                    <tr>
                        <td colspan="9" style="text-align: center;border: 2px solid black;" class="button">No Records Found.</td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $(".button").click(function(){

                var $toggle = $(this);
                var id = "#data_" + $toggle.data('id');
                $(id).toggle();
            });
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();

            var url = app_url+'/team-work-planning';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection