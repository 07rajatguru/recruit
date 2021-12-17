@extends('adminlte::page')

@section('title', 'Attendance Sheet')

@section('content_header')
    <h1>Attendance Sheet</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 month_div">
            <select class="form-control" name="month" id="month">
                @foreach($month_list as $key=>$value)
                    <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 year_div">
            <select class="form-control" name="year" id="year">
                @foreach($year_list as $key=>$value)
                    <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1 attendance_submit">
            <input class="btn btn-primary" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:100px;"/>
        </div>

        <div class="col-md-1">
            @if(isset($page) && $page == 'Department')
                @include('adminlte::partials.userRemarks', ['name' => $page,'users' => $users_name,'department_id' => $department_id])
            @else
                @if(isset($users_name) && sizeof($users_name) > 0)
                    @include('adminlte::partials.userRemarks', ['name' => $page,'users' => $users_name])
                @else
                    @include('adminlte::partials.userRemarks', ['name' => $page])
                @endif
            @endif
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
                                    <th style="border: 1px solid black;padding-left: 900px;"colspan="41">Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;text-align: center;" rowspan="2"><br/><br/>Sr. No.</th>
                                    <th style="border: 1px solid black;background-color:#d6e3bc;">ADLER EMPLOYEES</th>
                                    <th colspan="39" style="border: 1px solid black;padding-left: 760px;">DATE</th>
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
                                <th style="border: 1px solid black;">WO</th>
                                <th style="border: 1px solid black;">PH</th>
                                <th style="border: 1px solid black;">HD</th>
                                <th style="border: 1px solid black;">Total Days</th>
                            </thead>
                            <tbody>
                                
                                <?php $i=1; ?>

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

                                            $present = 0;
                                            $half_day = 0;
                                            $ph = 0;
                                            $week_off = 0;
                                            $total_days = 0;
                                        ?>
                                        <td style="border: 1px solid black;;text-align: center;">
                                        {{ $i }}</td>
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
                                        
                                        @foreach($value as $key1=>$value1)
                                            <?php

                                                $get_cur_dt = date('d');
                                                $get_cur_month = date('m');
                                                $get_cur_yr = date('Y');

                                                //$user_id = App\User::getUserIdByBothName($user_name);

                                                //$user_holidays = App\Holidays::getHolidaysByUserID($user_id,$month,$year);

                                                $joining_date_array = explode('/', $joining_date);

                                                if($key1 < $joining_date_array[0] && $joining_date_array[1] == $month) {
                                                    $attendance = 'O';
                                                }
                                                else if(in_array($key1, $sundays)) {
                                                    $attendance = 'H';
                                                }
                                                else if(isset($value1['holiday']) && $value1['holiday'] == 'Y') {
                                                    $attendance = 'PH';
                                                }
                                                else if(($key1 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                                    $attendance = 'N';
                                                }
                                                else if(isset($value1['attendance']) && $value1['attendance'] == '') {
                                                    $attendance = 'A';
                                                }
                                                else {

                                                    if(isset($value1['attendance'])) {
                                                        $attendance = $value1['attendance'];
                                                    }
                                                    else {
                                                        $attendance = 'N';
                                                    }
                                                }
                                            ?>
                                            @if(isset($value1['remarks']) && $value1['remarks'] != '')
                                                @if($attendance == 'N')
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}"></td>
                                                @elseif($attendance == 'F')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">P</td>
                                                @elseif($attendance == 'HD')
                                                    <?php $half_day++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">{{ $attendance }}</td>
                                                @elseif($attendance == 'PH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">{{ $attendance }}</td>
                                                @elseif($attendance == 'H')
                                                    <?php $week_off++; ?>
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">{{ $attendance }}</td>
                                                @else
                                                    <td style="border: 1px solid black;background-color:#92D050;cursor: pointer;text-align: center;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">{{ $attendance }}</td>
                                                @endif
                                            @else
                                                @if($attendance == 'O')
                                                    <td style="border: 1px solid black;text-align: center;"></td>
                                                @elseif($attendance == 'H')
                                                    <?php $week_off++; ?>
                                                    <td style="border: 1px solid black;background-color:#ffc000;text-align: center;">{{ $attendance }}</td>
                                                @elseif($attendance == 'PH')
                                                    <?php $ph++; ?>
                                                    <td style="border: 1px solid black;background-color:#76933C;text-align: center;">{{ $attendance }}</td>
                                                @elseif($attendance == 'F')
                                                    <?php $present++; ?>
                                                    <td style="border: 1px solid black;background-color:#d8d8d8;text-align: center;">P</td>
                                                @elseif($attendance == 'N')
                                                    <td style="border: 1px solid black;text-align: center;"></td>
                                                @elseif($attendance == 'A')
                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;">{{ $attendance }}</td>
                                                @elseif($attendance == 'HD')
                                                    <?php $half_day++; ?>
                                                    <td style="border: 1px solid black;background-color:#d99594;text-align: center;">{{ $attendance }}</td>
                                                @else
                                                    <td style="border: 1px solid black;background-color:#ff0000;text-align: center;">A</td>
                                                @endif
                                            @endif
                                        @endforeach

                                        <?php
                                            $total_days = $present + $week_off + $ph + $half_day;
                                        ?>
                                        <td style="border: 1px solid black;text-align:center;">     {{ $present }}</td>
                                        <td style="border: 1px solid black;text-align:center;">
                                            {{ $week_off }}</td>
                                        <td style="border: 1px solid black;text-align:center;">     {{ $ph }}</td>
                                        <td style="border: 1px solid black;text-align:center;">     {{ $half_day }}</td>
                                        <td style="border: 1px solid black;text-align:center;">
                                            {{ $total_days }}</td>
                                    </tr>
                                <?php $i++; ?>
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

    <input type="hidden" name="page" id="page" value="{{ $page }}">

    @if(isset($page) && $page == 'Department')
        <input type="hidden" name="department_id" id="department_id" value="{{ $department_id }}">
    @endif
@stop

@section('customscripts')
    <script type="text/javascript">

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
            var page = $("#page").val();
            var department_id = $("#department_id").val();

            if(department_id >= 0) {

                var url = '/users-attendance/'+department_id+'';
            }
            else {

                if(page == 'Self') {
                    
                    var url = '/self-user-attendance';
                }
                if(page == 'Team') {

                    var url = '/team-user-attendance';
                }
            }

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        function export_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();
            var page = $("#page").val();
            var department_id = $("#department_id").val();

            var url = '/attendance/export';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="page" value="'+page+'" />' +
            '<input type="hidden" name="department_id" value="'+department_id+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@stop