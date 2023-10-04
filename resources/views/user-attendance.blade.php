@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Attendance Sheet')

@section('content_header')
    <h1>Attendance Sheet</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2 month_div">
                <select class="form-control" name="month" id="month">
                    @foreach($month_list as $key=>$value)
                        <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 year_div">
                <select class="form-control" name="year" id="year">
                    @foreach($year_list as $key=>$value)
                        <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 year_div">
                <select class="form-control" name="attendance_type" id="attendance_type">
                    @foreach($attendance_type as $key=>$value)
                        <option value={{ $key }} @if($key==$selected_attendance_type) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 attendance_submit">
                <input class="btn btn-primary" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:100px;"/>
            </div>

            <div class="col-md-2 attendance_submit">
                <input class="btn btn-primary" type="button" value="As per New Policy" name ="filter" id="filter" onClick="filter_data_new();"/>
            </div>
        </div>

        {{-- <div class="col-md-1">
            @include('adminlte::partials.userRemarks', ['name' => $department_nm,'users' => $users_name,'month' => $month,'year' => $year])
        </div> --}}

        <div class="col-md-12" style="margin-top: 10px;">
            <div class="col-md-6"></div>
            @if(isset($list) && sizeof($list)>0)
                @permission(('display-attendance-of-all-users-in-admin-panel'))
                    <div class="col-md-2">
                        <input class="btn bg-maroon" type="button" value="Download Excel Sheet" name ="excel" id="excel" onClick="export_data()" style="width:170px;"/>
                    </div>

                    <div class="col-md-3">
                        <input class="btn bg-maroon" type="button" value="Download Excel As Per New Policy" name ="excel" id="excel" onClick="export_new_data()"/>
                    </div>
                @endpermission
            @endif

            @permission('edit-user-attendance')
                @if($user_id == $superadmin_userid || $user_id == $hr_user_id)
                    <div class="col-md-1" style="margin-left: -40px;">
                        @include('adminlte::partials.editUserAttendance', ['users' => $users_name,'attendance_value' => $attendance_value,'name' => $department_nm,'month' => $month,'year' => $year])
                    </div>
                @else        
                    @if(isset($edit_date_valid) && $edit_date_valid != '')
                        @if(date('Y-m-d') <= $edit_date_valid)
                            <div class="col-md-1" style="margin-left: -40px;">
                                @include('adminlte::partials.editUserAttendance', ['users' => $users_name,'attendance_value' => $attendance_value,'name' => $department_nm,'month' => $month,'year' => $year])
                            </div>
                        @endif
                    @endif
                @endif
            @endpermission
        </div>
    </div><br/>

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if(isset($new_list) && sizeof($new_list)>0)
        <div class="row">
            <div class="col-sm-12">
                @section('cotable_panel_body')
                    <div style ="overflow-x:scroll;">
                        @foreach($new_list as $key=>$value)
                            <?php 
                                $full_year =  $year;
                                $year_display = substr($full_year, -2);
                                $month_display = date('F', mktime(0, 0, 0, $month, 10));
                            ?>
                        
                            <table class="table table-striped table-bordered nowrap" cellspacing="0" id="attendance_table_{{ $key }}" style="font-family:Calibri;font-size: 11;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black;padding-left: 900px;"colspan="49">{{ $key }} - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid black;text-align: center;" rowspan="2">
                                        <br/><br/>Sr. No.</th>
                                        <th style="border: 1px solid black;background-color:#d6e3bc;">ADLER EMPLOYEES</th>
                                        <th colspan="47" style="border: 1px solid black;padding-left: 760px;">DATE</th>
                                    </tr>

                                    <th style="border: 1px solid black;background-color:#d6e3bc;">NAME OF PERSON</th>

                                    <th style="border: 1px solid black;background-color:#d6e3bc;">Department</th>
                                    <th style="border: 1px solid black;">Employment Type</th>
                                    <th style="border: 1px solid black;">Working Hours</th>
                                    <th style="border: 1px solid black;">Date of Joining</th>

                                    @if(isset($list) && sizeof($list)>0)
                                        @foreach($list as $list_key => $list_value)
                                            @foreach($list_value as $list_key1=>$list_value1)
                                                <?php
                                                    $con_dt = date("j", mktime(0, 0, 0, 0, $list_key1, 0));
                                                ?>
                                                <th style="border: 1px solid black;width: 1px;">{{ $con_dt }}</th>
                                            @endforeach
                                            @break
                                        @endforeach
                                    @endif

                                    <th style="border: 1px solid black;">Present</th>
                                    <th style="border: 1px solid black;">H</th>
                                    <th style="border: 1px solid black;">PH</th>
                                    <th style="border: 1px solid black;">SL</th>
                                    <th style="border: 1px solid black;">PL</th>
                                    <th style="border: 1px solid black;">HD</th>
                                    <th style="border: 1px solid black;">HD</th>
                                    <th style="border: 1px solid black;">UL</th>
                                    <th style="border: 1px solid black;">AB</th>
                                    <th style="border: 1px solid black;">Days</th>
                                    <th style="border: 1px solid black;">Total Leave</th>
                                    <th style="border: 1px solid black;">Total Days</th>
                                </thead>
                                <tbody>
                                
                                    <?php $i=1;?>
                                    @foreach($value as $key1=>$value1)
                                        <tr style="border: 1px solid black;">
                                            <?php

                                                $values_array = explode(",", $key1);

                                                $user_name = $values_array[0];
                                                $new_user_name = str_replace("-"," ", $user_name);

                                                $department = $values_array[1];
                                                $joining_date = $values_array[4];

                                                if($values_array[3] != '') {

                                                    $working_hours = $values_array[3];
                                                    $working_hours = explode(':', $working_hours);
                                                }
                                                else {
                                                    $working_hours = '';
                                                }

                                                if($values_array[2] != '') {
                                                    $employment_type = $values_array[2];
                                                }
                                                else {
                                                    $employment_type = '';
                                                } 

                                                $present = 0;$week_off = 0;$ph = 0;
                                                $pl = 0;$sl = 0;$ul = 0;
                                                $half_day = 0;$half_day_actual = 0;$absent = 0;
                                                $days =0;$total_leaves =0;$total_days = 0;
                                            ?>

                                            <td style="border: 1px solid black;;text-align: center;background-color: #fac090;">{{ $i }}</td>

                                            <td style="color: black; border: 1px solid black;;text-align: center;">{{ $new_user_name }}</td>

                                            @if($department == 'Recruitment')
                                                <td style="color: black; border: 1px solid black;background-color: #F2DBDB;"><center>{{ $department }}</center></td>
                                            @elseif($department == 'HR Advisory')
                                                <td style="color: black; border: 1px solid black;background-color: #DBE5F1;"><center>{{ $department }}</center></td>
                                            @elseif($department == 'Operations')
                                                <td style="color: black; border: 1px solid black;background-color: #EAF1DD;"><center>{{ $department }}</center></td>
                                            @else
                                                <td style="color: black; border: 1px solid black;background-color: #B1A0C7;"><center>{{ $department }}</center></td>
                                            @endif

                                            <td style="color: black; border: 1px solid black;"><center>{{ $employment_type }}</center></td>

                                            @if($working_hours != '')
                                                <td style="color: black; border: 1px solid black;">
                                                <center>{{ $working_hours[0] }} Hours</center></td>
                                            @else
                                                <td style="color: black; border: 1px solid black;"></td>
                                            @endif

                                            @if(strpos($joining_date,'1970') !== false)
                                                <td style="color: black; border: 1px solid black;"></td>
                                            @else
                                                <td style="color: black; border: 1px solid black;">
                                                <center>{{ $joining_date }}</center></td>
                                            @endif
                                        
                                            <?php $jj=0; $kk=0; ?>

                                            @foreach($value1 as $key2=>$value2)
                                                <?php
                                                    $kk++;
                                                    $get_cur_dt = date('d');
                                                    $get_cur_month = date('m');
                                                    $get_cur_yr = date('Y');

                                                    $sunday_date = $key2."-".$month."-".$year;   
                                                    $today_day = date('l',strtotime($sunday_date));

                                                    // September Fixed Date
                                                    $fixed_date = '10-9-2022';

                                                    //$user_id = App\User::getUserIdByBothName($user_name);

                                                    //$user_holidays = App\Holidays::getHolidaysByUserID($user_id,$month,$year);

                                                    $joining_date_array = explode('/', $joining_date);
                                                    
                                                    if($key2 < $joining_date_array[0] && $joining_date_array[1] == $month && $year <= $joining_date_array[2]) {
                                                        $attendance = 'O';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'CO') {
                                                        $attendance = 'CO';
                                                    }
                                                    else if($working_hours == '') {

                                                        $attendance = 'B';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'A') {

                                                        $attendance = 'A';
                                                        $jj++;
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WPP') {
                                                        $attendance = 'WPP';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                                        $attendance = 'HDPL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                                        $attendance = 'HDPL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                                        $attendance = 'HDSL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                                        $attendance = 'HDSL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD') {
                                                        $attendance = 'HD';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                                        $attendance = 'WFHHDPL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                                        $attendance = 'WFHHDSL';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD') {
                                                        $attendance = 'WFHHD';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HDR') {
                                                        $attendance = 'HDR';
                                                    }
                                                    else if(isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                                        $attendance = 'PL';
                                                    }
                                                    else if(isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                                        $attendance = 'SL';
                                                    }
                                                    else if(isset($value2['unapproved_leave']) && $value2['unapproved_leave'] == 'Y') {
                                                        $attendance = 'UL';
                                                    }
                                                    else if((isset($value2['fixed_holiday']) && $value2['fixed_holiday'] == 'Y') || $fixed_date == $sunday_date) {
                                                        $attendance = 'PH';
                                                    }
                                                    else if(isset($value2['optional_holiday']) && $value2['optional_holiday'] == 'Y') {
                                                        $attendance = 'OH';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'H') {

                                                        $kk = $kk-1;

                                                        if($kk==$jj) {

                                                            if($today_day == 'Sunday') {
                                                                $attendance = 'H';
                                                                $jj=0;
                                                                $kk=0;
                                                            }
                                                            else {
                                                                $attendance = 'A';
                                                                $jj++;
                                                                $kk++;
                                                            }
                                                        }
                                                        else {
                                                            $attendance = 'H';
                                                            $jj=0;
                                                            $kk=0;
                                                        }
                                                    } // For third saturday
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'TS') {

                                                        $attendance = 'TS';
                                                    }
                                                    else if($key2 == $get_cur_dt && $month == $get_cur_month) {

                                                        $attendance = 'WPP';
                                                    }
                                                    else if(($key2 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                                        $attendance = 'N';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == '') {

                                                        $attendance = 'A';
                                                        $jj++;
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P') {
                                                        $attendance = 'P';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHP') {
                                                        $attendance = 'WFHP';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'FR') {
                                                        $attendance = 'FR';
                                                    }
                                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHR') {
                                                        $attendance = 'WFHR';
                                                    }
                                                    else {
                                                        $attendance = 'N';
                                                    }
                                                ?>

                                                <!-- For Set Blank Values -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'N')
                                                    <td style="border: 1px solid black;text-align: center;" title="Remarks Added"></td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'O')
                                                    <td style="border: 1px solid black;text-align: center;" title="Remarks Added"></td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'B')
                                                    <td style="border: 1px solid black;text-align: center;" title="Remarks Added"></td>

                                                @elseif($attendance == 'N' || $attendance == 'O' || $attendance == 'B')
                                                    <td style="border: 1px solid black;text-align: center;"></td>
                                                @endif

                                                <!-- For Set Pending Work Planning -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WPP')
                                                    <td style="border: 1px solid black;text-align: center;background-color: #8FB1D5;cursor: pointer;" title="Pending Work Planning (Remarks Added)"></td>
                                                @elseif($attendance == 'WPP')
                                                    <td style="border: 1px solid black;text-align: center;background-color: #8FB1D5;cursor: pointer;" title="Pending Work Planning"></td>
                                                @endif

                                                <!-- For Set Half Day Paid Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'HDPL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $pl = $pl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day Paid Leave (Remarks Added)">HD</td>

                                                @elseif($attendance == 'HDPL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $pl = $pl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day Paid Leave">HD</td>
                                                @endif

                                                <!-- For Set Work From Home Half Day Paid Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WFHHDPL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $pl = $pl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Paid Leave (Work From Home - Remarks Added)">HD</td>

                                                @elseif($attendance == 'WFHHDPL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $pl = $pl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Paid Leave (Work From Home)">HD</td>
                                                @endif

                                                <!-- For Set Half Day Sick Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'HDSL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $sl = $sl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day Sick Leave (Remarks Added)">HD</td>

                                                @elseif($attendance == 'HDSL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $sl = $sl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day Sick Leave">HD</td>
                                                @endif

                                                <!-- For Set Work From Home Half Day Sick Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WFHHDSL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $sl = $sl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Sick Leave (Work From Home - Remarks Added)">HD</td>

                                                @elseif($attendance == 'WFHHDSL')

                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                        $sl = $sl + 0.5;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Sick Leave (Work From Home)">HD</td>
                                                @endif

                                                <!-- For Set Half Day -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'HD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day (Remarks Added)">HD</td>

                                                @elseif($attendance == 'HD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day">HD</td>
                                                @endif

                                                <!-- For Set Half Day Rejection -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'HDR')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: #FFFF00;" title="Half Day Rejection (Remarks Added)">HD</td>

                                                @elseif($attendance == 'HDR')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: #FFFF00;" title="Half Day Rejection">HD</td>
                                                @endif

                                                <!-- For Set Work From Home Half Day -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WFHHD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Work From Home (Remarks Added)">HD</td>

                                                @elseif($attendance == 'WFHHD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Work From Home">HD</td>
                                                @endif

                                                <!-- For Set Paid Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'PL')
                                                    <?php 
                                                        $pl++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#8db3e2;text-align: center;cursor: pointer;" title="Paid Leave (Remarks Added)">PL</td>

                                                @elseif($attendance == 'PL')
                                                    <?php 
                                                        $pl++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#8db3e2;text-align: center;cursor: pointer;" title="Paid Leave">PL</td>
                                                @endif

                                                <!-- For Set Sick Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'SL')
                                                    <?php 
                                                        $sl++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#c075f8;text-align: center;cursor: pointer;" title="Sick Leave (Remarks Added)">SL</td>

                                                @elseif($attendance == 'SL')
                                                    <?php 
                                                        $sl++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#c075f8;text-align: center;cursor: pointer;" title="Sick Leave">SL</td>
                                                @endif

                                                <!-- For Set Unapproved Leave -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'UL')
                                                    <?php 
                                                        $ul++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#fac090;text-align: center;cursor: pointer;" title="Unapproved Leave (Remarks Added)">UL</td>

                                                @elseif($attendance == 'UL')
                                                    <?php 
                                                        $ul++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#fac090;text-align: center;cursor: pointer;" title="Unapproved Leave">UL</td>
                                                @endif

                                                <!-- For Set Half & Full Paid Holiday -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'PH' && $working_hours[0] == '04')
                                                    <?php 
                                                        $ph = $ph + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Paid Holiday (Remarks Added)">PH</td>

                                                @elseif($attendance == 'PH' && $working_hours[0] == '04')
                                                    <?php 
                                                        $ph = $ph + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Paid Holiday">PH</td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'PH')
                                                    <?php 
                                                        $ph++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;" title="Paid Holiday (Remarks Added)">PH</td>

                                                @elseif($attendance == 'PH')
                                                    <?php 
                                                        $ph++;
                                                    ?>

                                                    @if($fixed_date == $sunday_date)
                                                        <td style="border: 1px solid black;background-color:#E6812F;text-align: center;cursor: pointer;" title="Paid Holiday">PH</td>
                                                    @else
                                                        <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;" title="Paid Holiday">PH</td>
                                                    @endif
                                                @endif

                                                <!-- For Set Half & Full  Optional Holiday -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'OH' && $working_hours[0] == '04')
                                                    <?php 
                                                        $ph = $ph + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color:white;" title="Half Optional Holiday (Remarks Added)">PH</td>

                                                @elseif($attendance == 'OH' && $working_hours[0] == '04')
                                                    <?php 
                                                        $ph = $ph + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color:white;" title="Half Optional Holiday">PH</td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'OH')
                                                    <?php 
                                                        $ph++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;color:white;" title="Optional Holiday (Remarks Added)">PH</td>

                                                @elseif($attendance == 'OH')
                                                    <?php 
                                                        $ph++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;color:white;" title="Optional Holiday">PH</td>
                                                @endif

                                                <!-- For Set Half & Full Sunday -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'H' && $working_hours[0] == '04')
                                                    <?php 
                                                        $week_off = $week_off + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Sunday (Remarks Added)">H</td>

                                                @elseif($attendance == 'H' && $working_hours[0] == '04')
                                                    <?php 
                                                        $week_off = $week_off + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Sunday">H</td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'H')
                                                    <?php 
                                                        $week_off++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;cursor: pointer;" title="Sunday (Remarks Added)">H</td>

                                                @elseif($attendance == 'H')
                                                    <?php 
                                                        $week_off++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;cursor: pointer;" title="Sunday">H</td>
                                                @endif

                                                <!-- For Set Half & Full Third Saturday -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'TS' && $working_hours[0] == '04')
                                                    <?php 
                                                        $week_off = $week_off + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Third Saturday (Remarks Added)">H</td>

                                                @elseif($attendance == 'TS' && $working_hours[0] == '04')
                                                    <?php 
                                                        $week_off = $week_off + 0.5;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Third Saturday">H</td>

                                                @elseif(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'TS')
                                                    <?php 
                                                        $week_off++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;cursor: pointer;" title="Third Saturday (Remarks Added)">H</td>

                                                @elseif($attendance == 'TS')
                                                    <?php 
                                                        $week_off++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;cursor: pointer;" title="Third Saturday">H
                                                    </td>
                                                @endif

                                                <!-- For Set Full Day Present -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'P')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;" title="Present (Remarks Added)">P</td>

                                                @elseif($attendance == 'P')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;" title="Present">P</td>
                                                @endif

                                                <!-- For Set Full Day Present Work From Home -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WFHP')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;color: blue;" title="Present - Work From Home (Remarks Added)">P</td>

                                                @elseif($attendance == 'WFHP')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;color: blue;" title="Present - Work From Home">P</td>
                                                @endif

                                                <!-- For Set Full Day Compensatory Off -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'CO')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#eedc82;text-align: center;cursor: pointer;" title="Compensatory Off (Remarks Added)">CO</td>

                                                @elseif($attendance == 'CO')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#eedc82;text-align: center;cursor: pointer;" title="Compensatory Off">CO</td>
                                                @endif

                                                <!-- For Set Full Day Absent -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'A')
                                                    <?php 
                                                        $absent++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;" title="Absent (Remarks Added)">A</td>

                                                @elseif($attendance == 'A')
                                                    <?php 
                                                        $absent++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;" title="Absent">A</td>
                                                @endif

                                                <!-- For Set Full Day Rejection -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'FR')
                                                    <?php 
                                                        $absent++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#FFFF00;" title="Full Day Rejection (Remarks Added)">A</td>

                                                @elseif($attendance == 'FR')
                                                    <?php 
                                                        $absent++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#FFFF00;" title="Full Day Rejection">A</td>
                                                @endif

                                                <!-- For Set Work From Home Request Rejection -->
                                                @if(isset($value2['remarks']) && $value2['remarks'] != '' && $attendance == 'WFHR')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#0000FF;" title="Work From Home Request Reject (Remarks Added)">P</td>

                                                @elseif($attendance == 'WFHR')
                                                    <?php 
                                                        $present++;
                                                    ?>

                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#0000FF;" title="Work From Home Request Reject">P
                                                    </td>
                                                @endif

                                            @endforeach

                                            @if($attendance == 'B')

                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                </td>
                                            @else

                                                <?php
                                                    $days = $present + $week_off + $ph + $half_day_actual - $ul;
                                                    $total_leaves = $sl + $pl;
                                                    $total_days = $sl + $days + $total_leaves;
                                                ?>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $present }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $week_off }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $ph }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $sl }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $pl }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $half_day }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $half_day_actual }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $ul }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $absent }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $days }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $total_leaves }}</td>
                                                <td style="border: 1px solid black;text-align:center;">
                                                {{ $total_days }}</td>
                                            @endif
                                        </tr>
                                    <?php $i++;?>
                                    @endforeach
                                </tbody>
                            </table><br/>
                        @endforeach
                    </div>
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
            </div>
        </div>
    @else
        <div class="col-sm-12" style="margin-top:2%;">
            <table class="table table-striped table-bordered nowrap" cellspacing="0" id="attendance_table">
                <thead></thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;border: 2px solid black;" class="button">No Data Found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
    @foreach($list1 as $key=>$value)

        <?php
            $values_array = explode(",", $key);
            $user_name = $values_array[0];
        ?>

        @foreach($value as $key1=>$value1)
            <div id="remarksModel-{{ $user_name }}-{{ str_replace(' ','',$key1) }}" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h2 id="modalTitle" class="modal-title">Remarks</h2>
                        </div>
                        <div id="modalBody" class="modal-body">
                            @if($value1 != '')
                                @foreach($value1 as $k1 => $v1)
                                    <b>Date : </b> {{ date("d/m/Y", strtotime($k1)) }}<br/><br/>
                                    @if($v1 != '')
                                        <b>Remarks : </b> <br/>
                                        <ul>
                                            @foreach($v1 as $k2 => $v2)
                                                <li>{{ $v2 }}<br/></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@stop

@section('customscripts')
    <script type="text/javascript">

        $("#month").select2();
        $("#year").select2();
        $("#attendance_type").select2();

        $(document).ready(function() {

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#attendance_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#user_id").select2({width : '570px'});
            $("#attendance_user_id").select2({width : '570px'});
            $("#new_attendance").select2({width : '570px'});

            var table = $('#attendance_table').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });

            var table = $('#attendance_table_Employee').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });

            var table = $('#attendance_table_Professional').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });

            var table = $('#attendance_table_Trainee').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });

            var table = $('#attendance_table_Intern').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });

            $("#remarks_form").validate({

                rules: {
                    "user_id": {
                        required: true
                    },
                    "date": {
                        required: true
                    },
                    "remarks": {
                        required: true
                    },
                },
                messages: {
                    "user_id": {
                        required: "Please Select User."
                    },
                    "date": {
                        required: "Please Select Date."
                    },
                    "remarks": {
                        required: "Please Add Remarks."
                    },
                }
            });

            $("#attendance_form").validate({

                rules: {
                    "attendance_user_id": {
                        required: true
                    },
                    "attendance_date": {
                        required: true
                    },
                    "new_attendance": {
                        required: true
                    },
                },
                messages: {
                    "attendance_user_id": {
                        required: "Please Select User."
                    },
                    "attendance_date": {
                        required: "Please Select Date."
                    },
                    "new_attendance": {
                        required: "Please Add Attendance."
                    },
                }
            });
        });

        function filter_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var attendance_type = $("#attendance_type :selected").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/users-attendance/'+attendance_type+'/'+month+'/'+year;

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="attendance_type" value="'+attendance_type+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }

        function filter_data_new() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var attendance_type = $("#attendance_type :selected").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/users-attendance-new/'+attendance_type+'/'+month+'/'+year;

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="attendance_type" value="'+attendance_type+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }

        function export_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var attendance_type = $("#attendance_type :selected").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/attendance/export';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="attendance_type" value="'+attendance_type+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }

        function export_new_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var attendance_type = $("#attendance_type :selected").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/attendance/export-new';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="attendance_type" value="'+attendance_type+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@stop