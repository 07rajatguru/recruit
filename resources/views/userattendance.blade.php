@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
@stop

@section('content')

    <div class="container">
        <div class="row">
            @permission(('user-attendance'))
            <div class="col-md-12 col-md-offset-1">
                <div class="col-md-3"><div style="text-align:center;width:95%;margin-bottom:10px;background-color:#B0E0E6;padding:9px 17px;font-weight: 600;border-radius: 22px;">More than or equal to 9 hours</div></div>
                <div class="col-md-3"><div style="text-align:center;width:95%;margin-bottom:10px;background-color:#FFFACD;padding:9px 17px;font-weight: 600;border-radius: 22px;">Between 8 to 9 hours</div></div>
                <div class="col-md-3"><div style="text-align:center;width:95%;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Less than 8 hours</div>
                </div>
            </div>

            <div class="col-md-12 col-md-offset-1">
                <div class="col-md-5">
                    <strong>Select User :</strong><br/>
                    {!! Form::select('selected_user_id', $users_name,null, array('id'=>'selected_user_id','class' => 'form-control')) !!}
                    <br/><br/>
                </div>
              
                <div class="col-md-2"><br>
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary', 'onclick' => 'attendanceSubmit()']) !!}
                </div>
               
                <div class="col-md-2" style="margin-left:-70px;"><br>
                    @include('adminlte::partials.userRemarks', ['name' => 'UserAttendance','users' => $users_name,'isSuperAdmin' => $isSuperAdmin,'isAccountant' => $isAccountant])
                </div>
            </div>
            @else
                <div class="col-md-12 col-md-offset-1">
                    <div class="col-md-3"><div style="text-align:center;width:95%;margin-bottom:10px;background-color:#B0E0E6;padding:9px 17px;font-weight: 600;border-radius: 22px;">More than or equal to 9 hours</div></div>
                    <div class="col-md-3"><div style="text-align:center;width:95%;margin-bottom:10px;background-color:#FFFACD;padding:9px 17px;font-weight: 600;border-radius: 22px;">Between 8 to 9 hours</div></div>
                    <div class="col-md-3"><div style="text-align:center;width:95%;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Less than 8 hours</div>
                    </div>
                    <div class="col-md-3">
                        @include('adminlte::partials.userRemarks', ['name' => 'UserAttendance','users' => $users_name,'isSuperAdmin' => $isSuperAdmin,'isAccountant' => $isAccountant])
                    </div>
                </div>
            @endpermission
            
            <div class="col-md-9 col-md-offset-1" style="padding-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Attendance
                    </div>

                    <div class="panel-body">
                        {!! $calendar->calendar() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            
            $("#user_id").select2({width : '570px'});
            $("#selected_user_id").select2({width : '470px'});
        });

        function attendanceSubmit()
        {
            var selected_user_id = $("#selected_user_id").val();
            var app_url = "{!! env('APP_URL'); !!}";
            var url = app_url+'/userattendance';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="selected_user_id" value="'+selected_user_id+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
    {!! $calendar->script() !!}
@stop