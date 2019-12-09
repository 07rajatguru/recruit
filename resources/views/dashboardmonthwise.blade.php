@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif
    <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Dashboard Monthwise Data</h2>
        </div>

       <!--  <div class="pull-right">
            @include('adminlte::partials.login', ['name' => 'dashboard'])
            @include('adminlte::partials.logout', ['name' => 'dashboard'])
        </div>
 -->
    </div>
</div>
@stop

@section('content')
    <div class="row">
        <div class="filter_section">
            <div class="month_div col-md-5" >
                <select class="form-control" name="month" id="month">
                    @foreach($month_list as $key=>$value)
                        <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="year_div col-md-5">
                <select class="form-control" name="year" id="year">
                    @foreach($year_list as $key=>$value)
                        <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="attendance_submit col-md-2">
                <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" />
            </div>
          
        </div>
    </div>
    <br/><br/><br/>
    <div class="row">
        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $clientCount or 0 }}</h3>
                    <p>No. of Clients added this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="/monthwiseclient/{{ $month }}/{{ $year }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $jobCount or 0 }}</h3>
                    <p>No. of Current Job Openings</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/jobs" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3> {{ $interviewCount or 0}} </h3>
                    <p>Today's and Tomorrow's Interviews</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="/todaytomorrow" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ $associatedCount or 0}}</h3>
                    <p>No. of CVS associated this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/associatedcvs/{{ $month }}/{{ $year }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3> {{ $interviewAttendCount or 0}} </h3>
                    <p>Interviews attended this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/attendedinterview/{{ $month }}/{{ $year }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3> {{$candidatejoinCount or 0}}</h3>
                    <p>Candidate Joining this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/candidatejoin/{{ $month }}/{{ $year }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

@stop

@section('customscripts')
    <script type="text/javascript">

        function filter_data(){
            var month = $("#month :selected").val();
            var year = $("#year :selected").val();

            var url = '/dashboard/monthwise';

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