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
        </div>
    </div><br/>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" style="border: 2px solid black;">
            <thead>
                <tr style="background-color: #7598d9">
                   <th width="5%" style="border: 2px solid black;text-align: center;">No</th>
                   <th width="8%" style="border: 2px solid black;text-align: center;">Action
                   </th>
                   <th style="border: 2px solid black;text-align: center;">Date</th>
                   <th style="border: 2px solid black;text-align: center;">Username</th>
                   <th style="border: 2px solid black;text-align: center;">Work Location</th>
                   <th style="border: 2px solid black;text-align: center;">Logged-in Time</th>
                   <th style="border: 2px solid black;text-align: center;">Logged-out Time</th>
                   <th style="border: 2px solid black;text-align: center;">Work Planning Time
                   </th>
                   <th style="border: 2px solid black;text-align: center;">Status Time</th>
                </tr>
            </thead>
            <?php $j = 0;?>
            @if(isset($work_planning_res) && $work_planning_res != '')
                @foreach($work_planning_res as $key => $value)
                    <?php
                        $i = 0;
                        $user = explode("-", $key);
                        $report_to_id = App\User::getReportsToById($user[0]);
                    ?>
                    <tbody>
                        @if(isset($report_to_id) && $report_to_id == $superadminuserid && $user_id == $superadminuserid)
                            <tr>
                                <td colspan="9" style="text-align: center;background-color:#C4D79B;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                            </tr>
                        @elseif(isset($report_to_id) && $report_to_id != $superadminuserid)
                            <tr>
                                <td colspan="9" style="text-align: center;background-color: #FABF8F;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                            </tr>
                        @else
                             <tr>
                                <td colspan="9" style="text-align: center;background-color: #FABF8F;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{ $user[1] }}</b></td>
                            </tr>
                        @endif
                    </tbody>
                        
                    <tbody id="data_{{$j}}">
                        @if(isset($value) && sizeof($value) >0)
                            @foreach($value as $k => $v)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a class="fa fa-circle" href="{{ route('workplanning.show',$v['id']) }}" title="Show">
                                        </a>

                                        @if($user_id == $v['added_by_id'])
                                            <a class="fa fa-edit" href="{{ route('workplanning.edit',$v['id']) }}" title="Edit"></a>
                                        @endif
                                            
                                        @permission(('work-planning-delete'))
                                            @include('adminlte::partials.deleteModal', ['data' => $v, 'name' => 'workplanning','display_name'=>'Work Planning'])
                                        @endpermission

                                        @if($user_id == $v['added_by_id'])
                                            @include('adminlte::partials.sendWorkPlanningReport', ['data' => $v, 'name' => 'workplanning'])
                                        @endif
                                    </td>

                                    @if($v['status'] == 0)
                                        <td style="background-color:#8FB1D5;">{{ $v['added_date'] }}</td>
                                    @elseif($v['status'] == 1)
                                        <td style="background-color:#32CD32;">{{ $v['added_date'] }}</td>
                                    @else
                                        <td style="background-color:#FF3C28;">{{ $v['added_date'] }}</td>
                                    @endif
                         
                                    <td>{{ $v['added_by'] }}</td>
                                    <td>{{ $v['work_type'] }}</td>

                                    @if($v['added_day'] == 'Saturday')

                                        @if($v['actual_login_time'] > '10:30:00')
                                            <td style="background-color:lightpink;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] == '')
                                            <td>{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] >= '06:00:00')
                                            <td style="background-color:#B0E0E6;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] == '04:30:00')
                                            <td style="background-color:#fff59a;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] < '05:30:00')
                                            <td style="background-color:#F08080;">{{ $v['loggedin_time'] }}</td>
                                        @else
                                            <td>{{ $v['loggedin_time'] }}</td>
                                        @endif
                                    @else

                                        @if($v['actual_login_time'] > '10:30:00')
                                            <td style="background-color:lightpink;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] == '')
                                            <td>{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] >= '08:30:00')
                                            <td style="background-color:#B0E0E6;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] == '07:00:00')
                                            <td style="background-color:#fff59a;">{{ $v['loggedin_time'] }}</td>
                                        @elseif($v['total_actual_time'] < '08:00:00')
                                            <td style="background-color:#F08080;">{{ $v['loggedin_time'] }}</td>
                                        @else
                                            <td>{{ $v['loggedin_time'] }}</td>
                                        @endif
                                    @endif

                                    <td>{{ $v['loggedout_time'] }}</td>
                                    <td>{{ $v['work_planning_time'] }}</td>
                                    <td>{{ $v['work_planning_status_time'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
                                <td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
                                <td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
                                <td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
                                <td style="border: 1px solid black;"></td>
                            </tr>
                        @endif
                    </tbody>
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

<input type="hidden" name="status" id="status" value="{{ $status }}">
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
            var status = $("#status").val();

            if(status == '0') {
                status = 'pending';
            }
            else if(status == '1') {
                status = 'approved';
            }
            else if(status == '2') {
                status = 'rejected';
            }
                
            var url = app_url+'/team-work-planning/'+status+'/'+month+'/'+year;
            
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '<input type="hidden" name="status" value="'+status+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection