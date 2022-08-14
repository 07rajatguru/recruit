@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
    tbody > tr > td:first-child {
      text-align: center;
    }
</style>
@endsection

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

@if($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($work_planning['status'] == 0)
                <h4>{{ $work_planning['added_date'] }} - {{ $added_day }}</h4>
            @elseif($work_planning['status'] == 1)
                <h4>{{ $work_planning['added_date'] }} - {{ $added_day }} ( Approved By {{ $appr_rejct_by }})</h4>
            @else
                <h4>{{ $work_planning['added_date'] }} - {{ $added_day }} ( Rejected By {{ $appr_rejct_by }})</h4>
            @endif
        </div>

        <div class="pull-right">
            @if($loggedin_user_id == $added_by_id)
                @if(date('Y-m-d') <= $edit_date_valid)
                    <a class="btn btn-primary" href="{{ route('workplanning.edit',$work_planning['id']) }}">Edit</a>
                @endif
                <a class="btn btn-primary" href="{{ route('workplanning.index') }}">Back</a>
            @else
                @if($work_planning['evening_status'] == 1)
                    @if($work_planning['status'] == 1)
                        <button type="submit" class="btn btn-success" onclick="updateStatus('Approved')" disabled="disabled">Approve</button>
                    @else
                        <button type="submit" class="btn btn-success" onclick="updateStatus('Approved')">Approve</button>
                    @endif
                    <button type="submit" class="btn btn-danger" onclick="updateStatus('Rejected')">Reject</button>
                @endif
                @if($work_planning['evening_status'] == 0)
                    <button type="submit" class="btn btn-danger" onclick="updateStatus('Rejected')">Reject</button>
                @endif
                <a class="btn btn-primary" href="{{ route('teamworkplanning.index') }}">Back</a>
            @endif
        </div>
    </div>
</div>

@if( $work_planning['loggedin_time'] != '')
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
                            <td>{{ $work_planning['created_at'] }} - {{ $work_planning['work_planning_time'] }}</td>
                            <th>Status Time :</th>

                            @if(isset($work_planning['status_date']) && $work_planning['status_date'] != '')
                                <td>{{ $work_planning['status_date'] }} - {{ $work_planning['work_planning_status_time'] }}</td>
                            @else
                                <td>{{ $work_planning['work_planning_status_time'] }}</td>
                            @endif
                        </tr>

                        @if(isset($work_planning['link']) && $work_planning['link'] != '')
                            <tr>
                                <th>Link :</th>
                                <td colspan="4"><a href="{{ $work_planning['link'] }}" target="_blank">{{ $work_planning['link'] }}</a></td>
                            </tr>
                        @endif

                        @if(isset($work_planning['report_delay']) && $work_planning['report_delay'] != '')
                            <tr>
                                <th>Reason of Delay Report :</th>

                                @if(isset($work_planning['report_delay_content']) && $work_planning['report_delay_content'] != '')
                                    <td colspan="3">{{ $work_planning['report_delay'] }} - {{ $work_planning['report_delay_content'] }}</td>
                                @else
                                    <td colspan="3">{{ $work_planning['report_delay'] }}</td>
                                @endif
                            </tr>
                        @endif

                        @if(isset($work_planning['reason_of_rejection']) && $work_planning['reason_of_rejection'] != '')
                            <tr>
                                <th>Reason of Rejection :</th>

                                @if(isset($work_planning['reject_reply']) && $work_planning['reject_reply'] != '')
                                    <td colspan="3">For {{ $work_planning['reject_reply'] }} - {{ $work_planning['reason_of_rejection'] }}</td>
                                @else
                                    <td colspan="3">{{ $work_planning['reason_of_rejection'] }}</td>
                                @endif
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($work_planning_list) && sizeof($work_planning_list) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="border:1px solid black;text-align: center;">Sr No.
                                </th>
                                <th style="border:1px solid black;">Task</th>
                                <th style="border:1px solid black;text-align: center;">Projected <br/>Time</th>
                                <th style="border:1px solid black;text-align: center;">Actual
                                <br/>Time</th>
                                <th style="border:1px solid black;">Remarks</th>
                                <th style="border:1px solid black;">Reporting Manager / HR Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i=1;$wp_list_id = '';
                            ?>
                            @foreach($work_planning_list as $key=>$value)
                                <?php

                                    if($wp_list_id == '') {
                                        $wp_list_id = $value['work_planning_list_id'];
                                    }
                                    else {
                                        $wp_list_id = $wp_list_id . "," . $value['work_planning_list_id'];    
                                    }
                                ?>
                                <tr>
                                    <td style="border:1px solid black;text-align: center;width: 5%;">{{ $i++ }}</td>
                                    <td style="border:1px solid black;width: 20%;">
                                    {!! $value['task'] !!}</td>
                                    <?php
                                        $projected_time = array();$actual_time = array();

                                        if(isset($value['projected_time']) && $value['projected_time'] != '') {
                                            $projected_time = explode(':', $value['projected_time']);
                                        }

                                        if(isset($value['actual_time']) && $value['actual_time'] != '') {
                                            $actual_time = explode(':', $value['actual_time']);
                                        }
                                    ?>

                                    @if(isset($projected_time)  && sizeof($projected_time) > 0)
                                        @if($projected_time[0] == 0)
                                            <td style="border:1px solid black;text-align: center;width: 8%;">{{ $projected_time[1] }} Min.</td>
                                        @else
                                            <td style="border:1px solid black;text-align: center;width: 8%;">{{ $projected_time[0] }}:{{ $projected_time[1] }} Hours</td>
                                        @endif
                                    @else
                                        <td style="border:1px solid black;text-align: center;width: 8%;">{{ $value['projected_time'] }}</td>
                                    @endif

                                    @if(isset($actual_time) && sizeof($actual_time) > 0)
                                        @if($actual_time[0] == 0)
                                            <td style="border:1px solid black;text-align: center;width: 7%;">{{ $actual_time[1] }} Min.</td>
                                        @else
                                            <td style="border:1px solid black;text-align: center;width: 7%;">{{ $actual_time[0] }}:{{ $actual_time[1] }} Hours</td>
                                        @endif
                                    @else
                                        <td style="border:1px solid black;text-align: center;width: 7%;">{{ $value['actual_time'] }}</td>
                                    @endif

                                    <td style="border:1px solid black;width: 30%;">{!! $value['remarks'] !!}</td>

                                    @if($value['rm_hr_remarks'] == '')

                                        @if($loggedin_user_id != $added_by_id)
                                            <td style="border:1px solid black;width: 30%;">
                                                @include('adminlte::partials.addWorkPlanningRemarks', ['data' => $value, 'name' => 'workplanning','work_planning' => $work_planning])
                                            </td>
                                        @else
                                            <td style="border:1px solid black;"></td>
                                        @endif
                                    @else

                                        @if($loggedin_user_id != $added_by_id)
                                            <td style="border:1px solid black;width: 30%;">
                                            {!! $value['rm_hr_remarks'] !!}
                                                <button type="button" data-toggle="modal" data-target="#modal-edit-remarks-{!! $value['work_planning_list_id']!!}">Edit</button>
                                            </td>
                                        @else
                                            <td style="border:1px solid black;width: 30%;">{!! $value['rm_hr_remarks'] !!}</td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach

                            @if(isset($work_planning['total_projected_time']) && $work_planning['total_projected_time'] != '')
                                <tr>
                                    <td style="border:1px solid black;text-align: center;">
                                    </td>
                                    <td style="border:1px solid black;text-align: center;">
                                    </td>

                                    @if(isset($work_planning['total_projected_time']) && $work_planning['total_projected_time'] != '')
                                        <td align="center" width="10%" style="border:1px solid black;text-align: center;">
                                            <b>{{ $work_planning['total_projected_time'] }} Hours</b>
                                        </td>
                                    @else
                                        <td style="border:1px solid black;text-align: center;">
                                        </td>
                                    @endif

                                    @if(isset($work_planning['total_actual_time']) && $work_planning['total_actual_time'] != '')
                                        <td align="center" width="10%" style="border:1px solid black;text-align: center;">
                                            <b>{{ $work_planning['total_actual_time'] }} Hours
                                            </b>
                                        </td>
                                    @else
                                        <td style="border:1px solid black;text-align: center;">
                                        </td>
                                    @endif

                                    <td style="border:1px solid black;text-align:center;"></td>
                                    <td style="border:1px solid black;text-align:center;"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    @if($loggedin_user_id == $superadmin_userid)
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <h4>Comments</h4>
                        </div>
                        <div class="col-md-12">
                            <div>
                                @include('adminlte::workPlanning.postnew',array('wp_id' => $wp_id,'user_id'=>$loggedin_user_id,'added_by_id'=>$added_by_id))   
                            </div>

                            <div>
                                @include('adminlte::workPlanning.postlist',array('post' => $work_planning_post,'edit_date_valid' => $edit_date_valid, 'superadmin_userid' => $superadmin_userid))
                            </div>
                        </div>
                    @else
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <h4>Comments</h4>
                        </div>
                        <div class="col-md-12">
                            @if(date('Y-m-d') <= $edit_date_valid)
                                <div>
                                    @include('adminlte::workPlanning.postnew',array('wp_id' => $wp_id,'user_id'=>$loggedin_user_id,'added_by_id'=>$added_by_id))   
                                </div>
                            @endif

                            <div>
                                @include('adminlte::workPlanning.postlist',array('post' => $work_planning_post,'edit_date_valid' => $edit_date_valid, 'superadmin_userid' => $superadmin_userid))
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="wp_list_id_string" id="wp_list_id_string" value="{{ $wp_list_id }}">
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($user_details->daily_report) && $user_details->daily_report == 'Yes')
    @if($associate_count > 0 || $interview_count > 0 || $lead_count > 0)
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header col-md-6"><h3>Daily Activity</h3></div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
                            <table cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="5" style="text-align:left;">
                                        <u><b><h4>No of CVs Associated : {{ $associate_count or '0'}}</h4></b></u>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_cv_table">
                                <thead>
                                    <tr style="background-color: #f39c12;">
                                        <th width="5%">View</th>
                                        <th width="30%">Position Name</th>
                                        <th width="30%">Company Name</th>
                                        <th width="20%">Location</th>
                                        <th width="15%">No of Resumes</th>
                                    </tr>
                                </thead>
                                <?php $i = 0;?>
                                @foreach($associate_daily as $key => $value)
                                    <tr>
                                        <td>
                                            @if(isset($value['associate_candidate_count']) && $value['associate_candidate_count'] == 1)
                                                <a href="/{{ $value['candidate_resume'] }}" target="_blank">CV</a>
                                            @else
                                                <a href="/candidate-list/{{ $added_by_id }}/{{ $value['job_id'] }}/{{ $added_date }}" target="_blank">Candidate List</a>
                                            @endif
                                        </td>
                                        <td>{{ $value['posting_title'] }}</td>
                                        <td>{{ $value['company'] }}</td>
                                        <td>{{ $value['location'] }}</td>
                                        <td>{{ $value['associate_candidate_count'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')
                            <table cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="7" style="text-align:left;">
                                        <u><b><h4>No of Interview Scheduled : {{ $interview_count or '0'}}</h4></b></u>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_interview_table">
                                <thead>
                                    <tr style="background-color: #7598d9">
                                        <th>No</th>
                                        <th>Position</th>
                                        <th>Candidate Name</th>
                                        <th>Interview Date</th>
                                        <th>Interview Time</th>
                                        <th>Contact No.</th>
                                        <th>Email ID</th>
                                    </tr>
                                </thead>
                                <?php $i=0;?>
                                @foreach($interview_daily as $key=>$value)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] }}</td>
                                        <td>{{ $value['cname'] }}</td>
                                        <td>{{ date('d/m/Y',strtotime($value['interview_date'])) }}</td>
                                        <td>{{ date('h:i A',strtotime($value['interview_time'])) }}</td>
                                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['cmobile'] }}</td>
                                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['cemail'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                            <table cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="7" style="text-align:left;">
                                        <u><b><h4>No of Leads Added : {{$lead_count or '0'}}</h4></b></u>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_leads_table">
                                <thead>
                                    <tr style="background-color: #C4D79B">
                                        <th>No</th>
                                        <th>Company Name</th>
                                        <th>Contact Point</th>
                                        <th>Email ID</th>
                                        <th>Mobile No.</th>
                                        <th>City</th>
                                        <th>Website</th>
                                    </tr>
                                </thead>
                                <?php $i=0;?>
                                @foreach($leads_daily as $key=>$value)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $value['company_name'] }}</td>
                                        <td>{{ $value['contact_point'] }}</td>
                                        <td>{{ $value['email'] }}</td>
                                        <td>{{ $value['mobile'] }}</td>
                                        <td>{{ $value['city'] }}</td>
                                        <td>{{ $value['website'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        <br/><center><a class="btn btn-success" title="Click here for more information" target="_blank" onclick="viewDailyReport();">Click here for more information</a></center><br/>
                        <input type="hidden" name="users_id" id="users_id" value="{{ $added_by_id }}">

                        <?php
                            $added_date = date('Y-m-d',strtotime($work_planning['added_date']));
                        ?>
                        <input type="hidden" name="date" id="date" value="{{ $added_date }}">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

<input type="hidden" name="wp_id" id="wp_id" value="{{ $wp_id }}">

@foreach($work_planning_list as $k1=>$v1)
    <div id="modal-edit-remarks-{!! $v1['work_planning_list_id']!!}" class="modal text-left fade" style="width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'POST', 'route' => ["workplanning.addremarks", $v1['work_planning_list_id']]])!!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">{{ $work_planning['added_by'] }} - {{ $work_planning['added_date'] }}</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea id="rm_hr_remarks_{{ $v1['work_planning_list_id'] }}" name="rm_hr_remarks" class="form-control" rows="5" placeholder = "RM / HR Remarks">{{ $v1['rm_hr_remarks'] }}</textarea>
                    </div>
                </div>

                <input type="hidden" name="wp_id" id="wp_id" value="{{ $v1['work_planning_id'] }}">
                <input type="hidden" name="task_id" id="task_id" value="{{ $v1['work_planning_list_id'] }}">
                <input type="hidden" name="action" id="action" value="Edit">
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Start -->
<div class="modal text-left fade" id="rejectionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Reason of Rejection</h4>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => 'workplanning.rejection','id' => 'reject_form']) !!}
                <div class="modal-body">
                    <div class="form-group">

                        {!! Form::radio('reject_reply','Half Day',false,['id' => 'reject_reply']) !!}
                        {!! Form::label('For Half Day') !!} &nbsp;

                        {!! Form::radio('reject_reply','Full Day',false,['id' => 'reject_reply']) !!}
                        {!! Form::label('For Full Day') !!}

                        <br/><br/>
                        <strong>&nbsp;Please specify reason of rejection: <span class = "required_fields">*</span></strong>
                        <br/><br/>
                        {!! Form::textarea('reason_of_rejection', null, array('id' => 'reason_of_rejection','placeholder' => 'Reason of Rejection','class' => 'form-control','rows' => '5')) !!}

                        <input type="hidden" name="wrok_planning_id" id="wrok_planning_id" value="{{ $wp_id }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                    </button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- Modal End -->

@endsection

@section('customscripts')
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
</link>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
<script type="text/javascript">

    $(document).ready(function() {

        // Set Editor for Remarks
        var wp_list_id_string = $("#wp_list_id_string").val();
        var wp_list_id_arr = wp_list_id_string.split(",");

        for (var i = 0; i < wp_list_id_arr.length; i++) {
            
            CKEDITOR.replace( 'rm_hr_remarks_'+wp_list_id_arr[i]+'', {
                customConfig: '/js/ckeditor_config.js',
            });
        }

        $("#reject_form").validate({
            rules: {
                "reason_of_rejection": {
                    required: true
                }
            },
            messages: {
                "reason_of_rejection": {
                    required: "Please Add Reason of Rejection."
                }
            }
        });

        var table1 = jQuery("#daily_report_cv_table").DataTable({
            responsive: true,
            "pageLength": 10,
            "columnDefs": [ {orderable: false, targets: [0]} ]
        });
        var table2 = jQuery("#daily_report_interview_table").DataTable({
            responsive: true,
            "pageLength": 10,
        });
        var table3 = jQuery("#daily_report_leads_table").DataTable({
            responsive: true,
            "pageLength": 10,
        });

        if ( ! table1.data().any() ) {
        }
        else {
            new jQuery.fn.dataTable.FixedHeader( table1 );
        }

        if ( ! table2.data().any() ) {
        }
        else {
            new jQuery.fn.dataTable.FixedHeader( table2 );
        }

        if ( ! table3.data().any() ) {
        }
        else {
            new jQuery.fn.dataTable.FixedHeader( table3 );
        }
    });

    function updateStatus(check) {

        var wp_id = $("#wp_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();

        if(check == 'Rejected') {

            $("#rejectionModal").modal('show');
        }
        else {

            $.ajax({
                type: 'POST',
                url:app_url+'/work-planning/'+wp_id+'/show',
                data: {wp_id: wp_id, 'check':check, '_token':token},
                dataType:'json',

                success: function(data) {

                    if (data == 'success') {

                        window.location.href = app_url+'/work-planning/'+wp_id+'/show';

                        if(check == 'Approved') {
                            alert("Report Approved Successfully.");
                        }
                    }
                }
            });
        }
    }

    function deletePost(id) {

        var msg = "Are you sure ?";
        var confirmvalue = confirm(msg);
        var csrf_token = $("#csrf_token").val();
        var app_url = "{!! env('APP_URL'); !!}";
        
        if(confirmvalue) {

            jQuery.ajax({

                url:app_url+'/post/delete/'+id,
                type:"POST",
                dataType:'json',
                data : {_token:csrf_token},

                success: function(response) {

                    if (response.returnvalue == 'valid') {
                        alert("Remarks Deleted Succesfully.");
                    }
                    else {
                        alert("Error while Deleting Remarks.");
                    }
                    window.location.reload();
                }
            });
        }
    }

    function sendEmail() {

        var wp_id = $("#wp_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();

        $.ajax({
            type: 'POST',
            url:app_url+'/work-planning/'+wp_id+'/updateremarks',
            data: {wp_id: wp_id, '_token':token},
            dataType:'json',

            success: function(data) {

                if (data == 'success') {

                    window.location.href = app_url+'/work-planning/'+wp_id+'/show';

                    alert("Email Sent Successfully.");
                }
            }
        });
    }

    function viewDailyReport() {

        var users_id = $("#users_id").val();
        var date = $("#date").val();
        var app_url = "{!! env('APP_URL'); !!}";

        var url = app_url+'/daily-report';

        var form = $('<form action="' + url + '" method="post">' +
        '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
        '<input type="hidden" name="users_id" value="'+users_id+'" />' +
        '<input type="hidden" name="date" value="'+date+'" />' +
        '</form>');

        $('body').append(form);
        form.attr('target', '_blank');
        form.submit();
    }
</script>
@endsection