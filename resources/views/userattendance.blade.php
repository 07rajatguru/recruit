@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Attendance</div>

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

        });

    </script>
    {!! $calendar->script() !!}
@stop