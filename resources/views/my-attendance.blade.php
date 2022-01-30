@extends('adminlte::page')

@section('title', 'My Attendance')

@section('content_header')
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="col-md-3">
                    <div style="text-align:center;width:95%;margin-bottom:10px;background-color:#B0E0E6;padding:9px 17px;font-weight: 600;border-radius: 22px;">More than or equal to 9 hours</div>
                </div>
                <div class="col-md-3">
                    <div style="text-align:center;width:95%;margin-bottom:10px;background-color:#FFFACD;padding:9px 17px;font-weight: 600;border-radius: 22px;">Between 8 to 9 hours</div>
                </div>
                <div class="col-md-3">
                    <div style="text-align:center;width:95%;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Less than 8 hours</div>
                </div>
            </div>
            
            <div class="col-md-9 col-md-offset-1" style="padding-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Attendance</div>
                    <div class="panel-body">{!! $calendar->calendar() !!}</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

        });
    </script>
    {!! $calendar->script() !!}
@stop