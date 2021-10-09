@extends('adminlte::page')

@section('title', 'Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">

            @if($work_planning['status'] == 0)
                <h4>{{ $work_planning['added_date'] }}</h4>
            @elseif($work_planning['status'] == 1)
                <h4>{{ $work_planning['added_date'] }} ( Approved By {{ $appr_rejct_by }} )</h4>
            @else
                <h4>{{ $work_planning['added_date'] }} ( Rejected By {{ $appr_rejct_by }} )</h4>
            @endif
        </div>
        <div class="pull-right">
            @if($loggedin_user_id == $added_by_id)

            @else

                @if($work_planning['status'] == 0)
                    <button type="submit" class="btn btn-primary" onclick="permission('Approved')">Approved</button>
                    <button type="submit" class="btn btn-primary" onclick="permission('Rejected')">Rejected</button>
                @else
                    <button type="submit" class="btn btn-primary" onclick="permission('Approved')" disabled="disabled">Approved</button>
                    <button type="submit" class="btn btn-primary" onclick="permission('Rejected')" disabled="disabled">Rejected</button>
                @endif
            @endif

            @if($loggedin_user_id == $added_by_id)
                <a class="btn btn-primary" href="{{ route('workplanning.edit',$work_planning['id']) }}">Edit</a>
            @endif
            <a class="btn btn-primary" href="{{ route('workplanning.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>User Name :</th>
                        <td>{{ $work_planning['added_by'] }}</td>
                        <th>Work Location :</th>
                        <td>{{ $work_planning['work_type'] }}</td>
                    </tr>
                    <tr>
                        <th>Logged-in Time :</th>
                        <td>{{ $work_planning['loggedin_time'] }}</td>
                        <th>Logged-out Time :</th>
                        <td>{{ $work_planning['loggedout_time'] }}</td>
                    </tr>
                    <tr>
                        <th>Work Planning Time :</th>
                        <td>{{ $work_planning['work_planning_time'] }}</td>
                        <th>Status Time :</th>
                        <td>{{ $work_planning['work_planning_status_time'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if(isset($work_planning_list) && sizeof($work_planning_list) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%" style="border:1px solid black;text-align: center;">Sr No.</th>
                            <th width="35%" style="border:1px solid black;">Task</th>
                            <th width="10%" style="border:1px solid black;text-align: center;">Projected Time</th>
                            <th width="10%" style="border:1px solid black;text-align: center;">Actual Time</th>
                            <th style="border:1px solid black;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($work_planning_list as $key=>$value)
                            <tr>
                                <td style="border:1px solid black;text-align: center;">{{ $i++ }}</td>
                                <td style="border:1px solid black;">{!! $value['description'] !!}</td>
                                <?php

                                    $projected_time = array();
                                    $actual_time = array();

                                    if(isset($value['projected_time']) && $value['projected_time'] != '') {
                                        $projected_time = explode(':', $value['projected_time']);
                                    }

                                    if(isset($value['actual_time']) && $value['actual_time'] != '') {
                                        $actual_time = explode(':', $value['actual_time']);
                                    }
                                ?>

                                @if(isset($projected_time)  && sizeof($projected_time) > 0)
                                    @if($projected_time[0] == 0)
                                        <td style="border:1px solid black;text-align: center;">{{ $projected_time[1] }} Min.</td>
                                    @else
                                        <td style="border:1px solid black;text-align: center;">{{ $projected_time[0] }}:{{ $projected_time[1] }} Hours</td>
                                    @endif
                                @else
                                    <td style="border:1px solid black;text-align: center;">{{ $value['projected_time'] }}</td>
                                @endif

                                @if(isset($actual_time) && sizeof($actual_time) > 0)
                                    @if($actual_time[0] == 0)
                                        <td style="border:1px solid black;text-align: center;">{{ $actual_time[1] }} Min.</td>
                                    @else
                                        <td style="border:1px solid black;text-align: center;">{{ $actual_time[0] }}:{{ $actual_time[1] }} Hours</td>
                                    @endif
                                @else
                                    <td style="border:1px solid black;text-align: center;">{{ $value['actual_time'] }}</td>
                                @endif

                                <td style="border:1px solid black;">{!! $value['remarks'] !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

<input type="hidden" name="wp_id" id="wp_id" value="{{ $id }}">

@endsection

@section('customscripts')
<script type="text/javascript">

    function permission(check) {
        
        var wp_id = $("#wp_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();

        $.ajax({
            type: 'POST',
            url:app_url+'/work-planning/'+wp_id+'/show',
            data: {wp_id: wp_id, 'check':check, '_token':token},
            dataType:'json',

            success: function(data) {

                if (data == 'success') { 

                    window.location.href = app_url+'/work-planning';

                    if(check == 'Approved') {
                        alert("Report Approved Successfully.");
                    }
                    if(check == 'Rejected') {
                        alert("Report Rejected.");
                    }
                }
            }
        });
    }
</script>
@endsection