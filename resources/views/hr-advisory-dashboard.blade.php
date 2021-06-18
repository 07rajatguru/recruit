@extends('adminlte::page')

@section('title', 'HR Advisory Dashboard')

@section('content_header')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>HR Advisory Dashboard</h2>
            </div>
        </div>
    </div>
@stop

@section('content')
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
                <a href="/monthwiseclient/{{ $month }}/{{ $year }}/{{ $department_id }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="hr-advisory-jobs" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="/associatedcvs/{{ $month }}/{{ $year }}/{{ $department_id }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3> {{ $shortlisted_count or 0}} </h3>
                    <p>No. of CVS shortlisted this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="/shortlistedcvs/{{ $month }}/{{ $year }}/{{ $department_id }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="attendedinterview/{{ $month }}/{{ $year }}/{{ $department_id }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="candidatejoin/{{ $month }}/{{ $year }}/{{ $department_id }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    @permission(('display-jobs-open-to-all'))
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Jobs open to all</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin" style="border: 1px solid #00c0ef;">
                                <thead>
                                <tr>
                                    <th style="border: 1px solid #00c0ef;">Company Name</th>
                                    <th style="border: 1px solid #00c0ef;">Position Title</th>
                                    <th style="border: 1px solid #00c0ef;">Location</th>
                                    <th style="border: 1px solid #00c0ef;">CA</th>
                                    <th style="border: 1px solid #00c0ef;">Min CTC<br/>(in Lacs)</th>
                                    <th style="border: 1px solid #00c0ef;">Max CTC<br/>(in Lacs)</th>
                                    <th style="border: 1px solid #00c0ef;">Added Date</th>
                                    <th style="border: 1px solid #00c0ef;">MB</th>
                                </tr>
                                </thead>
                                <tbody id="job_open_to_all"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <a href="jobs/create" class="btn btn-sm btn-info btn-flat pull-left">Add New job</a>
                        <a href="jobs/opentoall/{{ $department_id }}" class="btn btn-sm btn-default btn-flat pull-right">View All Jobs opened</a>
                    </div>
                </div>
            </div>
        </div>
    @endpermission

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Today's and Tomorrow's Interview ({{ $interviewCount }})</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="150px" style="border: 1px solid #00c0ef;">Posting Title</th>
                                <th width="150px" style="border: 1px solid #00c0ef;">Candidate Name</th>
                                <th width="150px" style="border: 1px solid #00c0ef;">Contact No.</th>
                                <th width="700px" style="border: 1px solid #00c0ef;">Time</th>
                                <th width="150px" style="border: 1px solid #00c0ef;">Candidate Owner</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($interviews) && sizeof($interviews) > 0)
                                @foreach($interviews as $interview)
                                    <?php
                                        $link = 'interview/' . $interview->id . "/show";
                                    ?>
                                    <tr>

                                        @if(isset($interview->remote_working) && $interview->remote_working != '')
                                            <td style="border: 1px solid #00c0ef;">
                                                <a href="{{ $link }}" target="_blank">
                                                {{ $interview->display_name }} - {{ $interview->posting_title }}, Remote
                                                </a>
                                            </td>
                                        @else
                                            <td style="border: 1px solid #00c0ef;">
                                                <a href="{{ $link }}" target="_blank">
                                                {{ $interview->display_name }} - {{ $interview->posting_title }} , {{$interview->city}}
                                                </a>
                                            </td>
                                        @endif
                                        <td style="border: 1px solid #00c0ef;">{{ $interview->candidate_fname}} </td>
                                        <td style="border: 1px solid #00c0ef;">{{ $interview->contact }}</td>
                                        <td style="border: 1px solid #00c0ef;">{{ date('d-m-Y h:i A',strtotime($interview->interview_date)) }}</td>
                                        <td style="border: 1px solid #00c0ef;">{{ $interview->candidate_owner_name}} </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Interviews for Today</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <a href="interview/create" class="btn btn-sm btn-info btn-flat pull-left">Add New Interview</a>
                    <a href="todaytomorrow/{{ $department_id }}" class="btn btn-sm btn-default btn-flat pull-right">View All Interviews</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">To Do's</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th style="border: 1px solid #00c0ef;">Sr No.</th>
                                <th width="180px" style="border: 1px solid #00c0ef;">Subject</th>
                                <th style="border: 1px solid #00c0ef;">Assigned By</th>
                                <!-- <th>Assigned To</th> -->
                                <th style="border: 1px solid #00c0ef;">Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($toDos) && sizeof($toDos)>0)
                                <?php $i =1; ?>
                                @foreach($toDos as $toDo)
                                    <?php 
                                        $todo_link = 'todos/' . $toDo['id'];
                                    ?>
                                    <tr>
                                        <td style="border: 1px solid #00c0ef;">{{ $i }}</td>
                                        <td style="border: 1px solid #00c0ef;">
                                            <a href="{{ $todo_link }}" target="_blank">
                                                {{ $toDo['subject'] }}</a>
                                        </td>
                                        <td style="border: 1px solid #00c0ef;">{{ $toDo['am_name'] }}</td>
                                        <!-- <td>{{ $toDo['assigned_to'] }}</td> -->
                                        <td style="font-size:13px;border: 1px solid #00c0ef;">{{ date('d-m-Y h:i A',strtotime($toDo['due_date'] ))}}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan = "4">No Task</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <a href="todos/create" class="btn btn-sm btn-info btn-flat pull-left">Add New ToDo's</a>
                    <a href="todos" class="btn btn-sm btn-default btn-flat pull-right">View All ToDo's</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('customscripts')
    <script>
        jQuery(document).ready(function () {
            opentoalljob();
        });

        function opentoalljob() {

            var app_url = "{!! env('APP_URL'); !!}";

            $.ajax({

                url:app_url+'/hr-advisory-dashboard/opentoalljob',
                dataType:'json',
                success: function(job_opened) {

                    if (job_opened.length > 0) {

                        for (var i = 0; i <= job_opened.length; i++) {

                            var link = /jobs/+job_opened[i].id+/associated_candidates/;
                            var html = '';
                            var show_job_link = /jobs/+job_opened[i].id+'/';

                            html += '<tr>';
                            html += '<td style="background-color: '+job_opened[i].color+';border: 1px solid #00c0ef;">'+job_opened[i].display_name+'</td>';

                            html += '<td style="white-space: pre-wrap; word-wrap: break-word;border: 1px solid #00c0ef;"><a href="'+show_job_link+'" target="_blank">'+job_opened[i].posting_title+'</a></td>';

                            html += '<td style="white-space: pre-wrap; word-wrap: break-word;border: 1px solid #00c0ef;">'+job_opened[i].location+'</td>';

                            html += '<td style="border: 1px solid #00c0ef;"><a title="Show Associated Candidates" href="'+link+'" target="_blank">'+job_opened[i].associate_candidate_cnt+'</td>';

                            html += '<td style="border: 1px solid #00c0ef;">'+job_opened[i].min_ctc+'</td>';

                            html += '<td style="border: 1px solid #00c0ef;">'+job_opened[i].max_ctc+'</td>';

                            html += '<td style="border: 1px solid #00c0ef;">'+job_opened[i].created_date+'</td>';

                            html += '<td style="white-space: pre-wrap; word-wrap: break-word;border: 1px solid #00c0ef;">'+job_opened[i].am_name+'</td>';

                            html += '</tr>';

                            $("#job_open_to_all").append(html);
                        }
                    }
                    else {
                        var html = '';
                        html += '<tr>';
                        html += '<td colspan="8" style="border: 1px solid #00c0ef;">No Jobs open to all</td>';
                        html += '</tr>';

                        $("#job_open_to_all").append(html);
                    }
                }
            });
        }
    </script>
@stop