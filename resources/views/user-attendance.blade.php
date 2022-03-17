@extends('adminlte::page')

@section('title', 'Attendance Sheet')

@section('content_header')
    <h1>Attendance Sheet</h1>
@stop

@section('content')
    <div class="row">
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

        <div class="col-md-1">
            @include('adminlte::partials.userRemarks', ['name' => $department_nm,'users' => $users_name])
        </div>

        @if(isset($list) && sizeof($list)>0)
            @permission(('display-attendance-of-all-users-in-admin-panel'))
                <div class="col-md-2" style="margin-left: 10px;">
                    <input class="btn bg-maroon" type="button" value="Download Excel Sheet" name ="excel" id="excel" onClick="export_data()" style="width:170px;"/>
                </div>
            @endpermission
        @endif
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

    <div class="row">
        @if(isset($list) && sizeof($list)>0)
            <div class="col-sm-12" style="margin-top:2%;">
                @section('cotable_panel_body')
                    <div style ="overflow-x:scroll;">
                        <?php 
                            $full_year =  $year;
                            $year_display = substr($full_year, -2);
                            $month_display = date('F', mktime(0, 0, 0, $month, 10));
                        ?>
                        <table class="table table-striped table-bordered nowrap" cellspacing="0" id="attendance_table">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black;padding-left: 900px;"colspan="48">Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;text-align: center;" rowspan="2"><br/><br/>Sr. No.</th>
                                    <th style="border: 1px solid black;background-color:#d6e3bc;">ADLER EMPLOYEES</th>
                                    <th colspan="46" style="border: 1px solid black;padding-left: 760px;">DATE</th>
                                </tr>

                                <th style="border: 1px solid black;background-color:#d6e3bc;">NAME OF PERSON</th>

                                @if(isset($list) && sizeof($list)>0)
                                    <th style="border: 1px solid black;background-color:#d6e3bc;">Department</th>
                                    <th style="border: 1px solid black;">Working Hours</th>
                                    <th style="border: 1px solid black;">Date of Joining</th>
                                    @foreach($list as $key => $value)
                                        @foreach($value as $key1=>$value1)
                                            <?php
                                                $con_dt = date("j", mktime(0, 0, 0, 0, $key1, 0));
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

                                @foreach($list as $key=>$value)
                                    <tr style="border: 1px solid black;">
                                        <?php

                                            $values_array = explode(",", $key);

                                            $user_name = $values_array[0];
                                            $new_user_name = str_replace("-"," ", $user_name);

                                            $department = $values_array[1];
                                            $joining_date = $values_array[3];

                                            $working_hours = $values_array[2];
                                            $working_hours = explode(':', $working_hours);

                                            $present = 0;$week_off = 0;$ph = 0;
                                            $pl = 0;$sl = 0;$ul = 0;
                                            $half_day = 0;$half_day_actual = 0;$absent = 0;
                                            $days =0;$total_leaves =0;$total_days = 0;
                                        ?>

                                        <td style="border: 1px solid black;;text-align: center;">{{ $i }}</td>
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

                                        @if($working_hours[0] != '')
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

                                        @foreach($value as $key1=>$value1)
                                            <?php

                                                $kk++;
                                                $get_cur_dt = date('d');
                                                $get_cur_month = date('m');
                                                $get_cur_yr = date('Y');

                                                //$user_id = App\User::getUserIdByBothName($user_name);

                                                //$user_holidays = App\Holidays::getHolidaysByUserID($user_id,$month,$year);

                                                $joining_date_array = explode('/', $joining_date);
                                                
                                                if($key1 < $joining_date_array[0] && $joining_date_array[1] == $month && $year <= $joining_date_array[2]) {
                                                    $attendance = 'O';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WPP') {
                                                    $attendance = 'WPP';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'HD') {
                                                    $attendance = 'HD';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHHD') {
                                                    $attendance = 'WFHHD';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'HDR') {
                                                    $attendance = 'HDR';
                                                }
                                                else if(isset($value1['privilege_leave']) && $value1['privilege_leave'] == 'Y') {
                                                    $attendance = 'PL';
                                                }
                                                else if(isset($value1['sick_leave']) && $value1['sick_leave'] == 'Y') {
                                                    $attendance = 'SL';
                                                }
                                                else if(isset($value1['unapproved_leave']) && $value1['unapproved_leave'] == 'Y') {
                                                    $attendance = 'UL';
                                                }
                                                else if(isset($value1['fixed_holiday']) && $value1['fixed_holiday'] == 'Y') {
                                                    $attendance = 'PH';
                                                }
                                                else if(isset($value1['optional_holiday']) && $value1['optional_holiday'] == 'Y') {
                                                    $attendance = 'OH';
                                                }
                                                else if(in_array($key1, $sundays)) {

                                                    $kk = $kk-1;

                                                    if($kk==$jj) {
                                                        $attendance = 'A';
                                                        $jj++;
                                                        $kk++;
                                                    }
                                                    else{
                                                        $attendance = 'H';
                                                        $jj=0;
                                                        $kk=0;
                                                    }
                                                }
                                                else if(($key1 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                                    $attendance = 'N';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == '') {

                                                    $attendance = 'A';
                                                    $jj++;
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'P') {
                                                    $attendance = 'P';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHP') {
                                                    $attendance = 'WFHP';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'FR') {
                                                    $attendance = 'FR';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHR') {
                                                    $attendance = 'WFHR';
                                                }
                                                else {
                                                    $attendance = 'N';
                                                }
                                            ?>
                                            @if(isset($value1['remarks']) && $value1['remarks'] != '')
                                                @if($attendance == 'N' || $attendance == 'O' || $attendance == 'WPP')
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">
                                                    </td>
                                                @elseif($attendance == 'HD')
                                                    <?php 
                                                        $half_day++; 
                                                        $half_day_actual = $half_day / 2;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">HD</td>
                                                @elseif($attendance == 'HDR' || $attendance == 'WFHHD')
                                                    <?php 
                                                        $half_day++; 
                                                        $half_day_actual = $half_day / 2;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;color:white;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">HD</td>
                                                @elseif($attendance == 'PL')
                                                    <?php $pl++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">PL</td>
                                                @elseif($attendance == 'SL')
                                                    <?php $sl++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">SL</td>
                                                @elseif($attendance == 'UL')
                                                    <?php $ul++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">UL</td>
                                                @elseif($attendance == 'PH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">PH</td>
                                                @elseif($attendance == 'OH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">OH</td>
                                                @elseif($attendance == 'H')
                                                    <?php $week_off++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">H</td>
                                                @elseif($attendance == 'P')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">P</td>
                                                @elseif($attendance == 'WFHP')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;color: white;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">P</td>
                                                @elseif($attendance == 'A')
                                                    <?php $absent++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">A</td>
                                                @elseif($attendance == 'FR')
                                                    <?php $absent++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;color: white;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">A</td>
                                                @elseif($attendance == 'WFHR')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;color: #0000FF;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}" title="Remarks Added">P</td>
                                                @endif
                                            @else
                                                @if($attendance == 'N' || $attendance == 'O')
                                                    <td style="border: 1px solid black;text-align: center;"></td>
                                                @elseif($attendance == 'WPP')
                                                    <td style="border: 1px solid black;text-align: center;background-color: #8FB1D5;cursor: pointer;" title="Pending Work Planning"></td>
                                                @elseif($attendance == 'HD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;" title="Half Day">HD</td>
                                                @elseif($attendance == 'HDR')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: #FFFF00;" title="Half Day Rejection">HD</td>
                                                @elseif($attendance == 'WFHHD')
                                                    <?php 
                                                        $half_day++;
                                                        $half_day_actual = $half_day / 2;
                                                    ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;cursor: pointer;color: blue;" title="Half Day Work From Home">HD</td>
                                                @elseif($attendance == 'PL')
                                                    <?php $pl++; ?>
                                                    <td style="border: 1px solid black;background-color:#8db3e2;text-align: center;cursor: pointer;" title="Privilege Leave">PL</td>
                                                @elseif($attendance == 'SL')
                                                    <?php $sl++; ?>
                                                    <td style="border: 1px solid black;background-color:#c075f8;text-align: center;cursor: pointer;" title="Sick Leave">SL</td>
                                                @elseif($attendance == 'UL')
                                                    <?php $ul++; ?>
                                                    <td style="border: 1px solid black;background-color:#fac090;text-align: center;cursor: pointer;" title="Unapproved Leave">UL</td>
                                                @elseif($attendance == 'PH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;" title="Paid Holiday">PH</td>
                                                @elseif($attendance == 'OH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;cursor: pointer;" title="Optional Holiday">OH</td>
                                                @elseif($attendance == 'H')
                                                    <?php $week_off++; ?>
                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;cursor: pointer;" title="Sunday">H</td>
                                                @elseif($attendance == 'P')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;" title="Present">P</td>
                                                @elseif($attendance == 'WFHP')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;cursor: pointer;color: blue;" title="Work From Home">P</td>
                                                @elseif($attendance == 'A')
                                                    <?php $absent++; ?>
                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;" title="Absent">A</td>
                                                @elseif($attendance == 'FR')
                                                    <?php $absent++; ?>
                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#FFFF00;" title="Full Day Rejection">A
                                                    </td>
                                                @elseif($attendance == 'WFHR')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;cursor: pointer;color:#0000FF;" title="Work From Home Request Reject">P</td>
                                                @endif
                                            @endif
                                        @endforeach

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
                                    </tr>
                                <?php $i++;?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
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
    </div>
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

            $("#user_id").select2({width : '570px'});

            var table = $('#attendance_table').DataTable({
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });
        });

        function filter_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var attendance_type = $("#attendance_type :selected").val();

            var url = '/users-attendance/'+attendance_type+'/'+month+'/'+year;

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

            var url = '/attendance/export';

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