@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
    <h1>Attendance</h1>
@stop

@section('content')

    <div class="row">

        <div class="filter_section">
            <div class="month_div col-md-4 col-sm-6 col-xs-12">
                <select class="form-control" name="month" id="month">
                    @foreach($month_list as $key=>$value)
                        <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="year_div col-md-4 col-sm-6 col-xs-12">
                <select class="form-control" name="year" id="year">
                    @foreach($year_list as $key=>$value)
                        <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="attendance_submit col-md-1 col-sm-4">
                <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:100px;"/>
            </div>

            <div class="col-md-1 col-sm-4">
                @include('adminlte::partials.userRemarks', ['name' => 'User-Attendance','users' => $users_name])
            </div>
        </div>

        <div class="col-sm-12" style="margin-top:2%;">
            @section('cotable_panel_body')
                <div style ="overflow-x:scroll;">
                    <table class="table table-bordered" id="attendance_table">
                        <tbody>
                            <?php 
                                $full_year =  $year;
                                $year_display = substr($full_year, -2);
                                $month_display = date('F', mktime(0, 0, 0, $month, 10));
                            ?>
                            <tr>
                                <td style="border: 1px solid black;"colspan="35"><center><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></center></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;" rowspan="2"><br/><br/><b>Sr. No.</b>
                                <td style="border: 1px solid black;background-color:#d6e3bc"><b>ADLER EMPLOYEES</b></td>
                                <td colspan="32" style="border: 1px solid black;"><center><b>DATE</b></center></td>
                            </tr>
                            <td style="border: 1px solid black;background-color:#d6e3bc"><b>NAME OF PERSON</b>
                                @if(isset($list) && sizeof($list)>0)
                                    <th style="border: 1px solid black;">Date of Joining<br/>(DD/MM/YYYY)</th>
                                    @foreach($list as $key => $value)
                                        @foreach($value as $key1=>$value1)
                                            <?php
                                                $con_dt = date("j", mktime(0, 0, 0, 0, $key1, 0));
                                            ?>
                                            <th style="border: 1px solid black;">{{ $con_dt }}</th>
                                        @endforeach
                                        @break
                                    @endforeach
                                @endif
                            </td>
                        <?php $i=1; ?>

                        @foreach($list as $key=>$value)
                        <tr style="border: 1px solid black;">
                            <?php
                                $values_array = explode(",", $key);
                                $user_name = $values_array[0];
                                $new_user_name = str_replace("-"," ", $user_name);
                                $joining_date = $values_array[1];
                            ?>
                            <td style="border: 1px solid black;"><center>{{ $i }}</center></td>
                            <td style="color: black; border: 1px solid black;">{{ $new_user_name }}
                            </td>

                            @if($joining_date == '00')
                                <td style="color: black; border: 1px solid black;"></td>
                            @else
                                <td style="color: black; border: 1px solid black;">{{ $joining_date }}</td>
                            @endif
                            
                            @foreach($value as $key1=>$value1)
                                @if(isset($value1['remarks']) && $value1['remarks'] != '')
                                    <?php 

                                        if($value1['attendance'] == 'P') 
                                            $attendance = 'P';
                                        else
                                            $attendance = 'A';
                                    ?>
                                    <td style="border: 1px solid black;background-color:#B0E0E6;cursor: pointer;" data-toggle="modal" data-target="#remarksModel-{{ $user_name }}-{{ $key1 }}">{{ $attendance }}</td>
                                @else
                                    <?php 
                                        if($value1['attendance'] == 'P') 
                                            $attendance = 'P';
                                        else
                                            $attendance = 'A';
                                    ?>

                                    @if($attendance == 'P')
                                        <td style="border: 1px solid black;background-color:#d8d8d8;">{{ $attendance }}</td>
                                    @else
                                        <td style="border: 1px solid black;background-color:#ff0000;">{{ $attendance }}</td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                        <?php $i++; ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        </div>
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

        $(document).ready(function() {

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#user_id").select2({width : '570px'});

            var table = $('#attendance_table').DataTable({
                scrollY: true,
                scrollX: true,
                paging: false,
                searching: false,
                info: false,
                sort: false,
                fixedColumns: {
                    leftColumns: 1
                }
            });
        });

        function filter_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();

            var url = '/user-attendance';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@stop