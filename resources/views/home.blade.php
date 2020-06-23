@extends('adminlte::page')

@section('title', 'HRM')

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

           <!--  <div class="attendance_submit col-md-2 col-sm-6 col-xs-12">
                <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" />
            </div>
            <?php if($isSuperAdmin || $isAccountant || $isAdmin || $isOperationsExecutive) {?>
            <div class="filter-ex-btn pull-right col-md-2 col-sm-6 col-xs-12">
                <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()"> Export</a>
            </div>
            <?php   }?> -->

            <?php if($isSuperAdmin || $isAccountant || $isAdmin || $isOperationsExecutive) {?>

                <div class="attendance_submit col-md-1 col-sm-4">
                    <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:100px;" />
                </div>
                
                <div class="filter-ex-btn col-md-1 col-sm-4">
                    <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()" style="width:100px;">Export</a>
                </div>

                <div class="attendance_submit col-md-1 col-sm-4">
                   @include('adminlte::partials.userRemarks', ['name' => 'HomeAttendance','users' => $users_name,'isSuperAdmin' => $isSuperAdmin,'isAccountant' => $isAccountant,'isOperationsExecutive' => $isOperationsExecutive])
                </div>
            <?php   
            }
            else{
            ?>
                <div class="attendance_submit col-md-1 col-sm-4">
                    <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:100px;"/>
                </div>

                <div class="col-md-1 col-sm-4">
                    @include('adminlte::partials.userRemarks', ['name' => 'HomeAttendance','users' => $users_name])
                </div>
            <?php
            }
            ?>
          
        </div>

        <div class="col-sm-12" style="margin-top:2%;">
            @section ('cotable_panel_body')
                <div style ="overflow-x:scroll;">
                    <table class="table table-bordered" id="attendance_table">
                        <thead>
                        <td style="border: 1px solid black;">
                            @if(isset($list) && sizeof($list)>0)
                                @foreach($list as $key => $value)
                                    @foreach($value as $key1=>$value1)
                                        <?php
                                            $con_dt = date("j S", mktime(0, 0, 0, 0, $key1, 0));
                                        ?>
                                        <th style="border: 1px solid black;">{{ $con_dt }}</th>
                                    @endforeach
                                    @break
                                @endforeach
                            @endif
                        </td>
                        </thead>
                        <tbody>
                        @foreach($list as $key=>$value)
                        <tr style="border: 1px solid black;">
                            <?php
                                $replce_nm = str_replace("-"," ", $key);
                            ?>
                            <th style="color: red; border: 1px solid black;">{{ $replce_nm }}<hr>
                                <span> Login </span><hr>
                                <span> Logout </span><hr>
                                <span> Total </span>
                            </th>
                            
                            @foreach($value as $key1=>$value1)
                                @if($value1['remarks'] != '')
                                    <td style="border: 1px solid black;background-color:#B0E0E6;" data-toggle="modal" data-target="#remarksModel-{{ str_replace(' ','',$key) }}{{ str_replace(' ','',$key1) }}">
                                        {{ $value1['login'] }}<hr style="border-top: 1px solid #B0E0E6;">
                                        {{ $value1['logout'] }}<hr style="border-top: 1px solid #B0E0E6;">
                                        {{ $value1['total'] }}
                                    </td>
                                @else
                                    <td style="border: 1px solid black;">
                                        {{ $value1['login'] }}<hr style="border-top: 1px solid #FFFFFF;">
                                        {{ $value1['logout'] }}<hr style="border-top: 1px solid #FFFFFF;">
                                        {{ $value1['total'] }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        </div>
    </div>
    @foreach($list1 as $key=>$value)
        @foreach($value as $key1=>$value1)
            <div id="remarksModel-{{ str_replace(' ','',$key) }}{{ str_replace(' ','',$key1) }}" class="modal fade">
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

            /*$('#calendar').fullCalendar({
                header: {
                    left: 'title',
                    center: '',
                    right: 'month,basicWeek,basicDay prev,next'
                },
                buttonIcons: {
                    prev: ' fa fa-caret-left',
                    next: ' fa fa-caret-right'
                },
                defaultDate: '2018-06-12',
                defaultView: 'month',
                editable: true,
                //events: 'calender.php',
                events: function(start, end, timezone, callback) {
                $.ajax({
                    url: 'home/calender',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                         start: start.format(),
                         end: end.format(),
                        _token: "<?php echo csrf_token() ?>"
                    },
                    success: function(doc) {
        var events = [];
        $(doc).find('event').each(function() {
          events.push({
            title: $(this).attr('title'),
            start: $(this).attr('start') // will be parsed
          });
        });
        callback(events);
      }
                    success: function(doc) {
                    /*var events = [];
                    $(doc).find('event').each(function() {
                        events.push({
                            title: title,
                            start: start, // will be parsed
                        });
                    });
                    callback(events);*/
                    /*var events = [];
                if(!!doc.result){
                    $.map( doc.result, function( r ) {
                        events.push({
                            //id: '1',
                            title: r.title,
                            start: r.start,
                            end: r.end
                        });
                    });
                }
                callback(events);
                    }
                });
                }
            });*/

        var table = $('#attendance_table').DataTable( {
            scrollY: true,
            scrollX: true,
            paging: false,
            searching: false,
            info: false,
            sort: false,
            fixedColumns: {
            leftColumns: 1
        }
        } );
    } );
        function filter_data(){

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();

            var url = '/home';

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

            var url = '/home/export';

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