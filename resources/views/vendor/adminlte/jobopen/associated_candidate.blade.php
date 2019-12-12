@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Associated Candidates to Job Opening : {{ $posting_title }}</h3>
                <span> </span>
            </div>

            <div class="pull-right">
                <button type="button" class="btn bg-blue" data-toggle="modal" data-target="#modal-shortlist" onclick="shortlistcandidate()">Shortlist Candidates</button>
                <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-mail" onclick="associatedmail()"> Send Mail</button>
                <a class="btn bg-blue" href="{{url()->previous()}}">Back</a>

               {{-- <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <a class="btn bg-red" class="dropdown-toggle" style="line-height: 3px " data-toggle="dropdown" aria-expanded="false">More Option</a>
                        @foreach ($candidates as $candidate)
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                        <ul class="menu" style="overflow: hidden; width: 100%;">
                                            <li>
                                                <a class="schedule-interview" data-toggle="modal" href="#modal-schedule-interview" >Schedule Interview</a>
                                            </li>
                                            <li>
                                                <a class="update-status-modal" data-toggle="modal" href="#modal-update-status">
                                                    Update Status
                                                </a>
                                            </li>
                                            <li>
                                                <a class="joining-date" data-toggle="modal" href="#modal-joining-date" >Add Joining Date</a>
                                            </li>
                                            @if(isset($access) && $access==true)
                                                <li>
                                                    <a class="sorted-candidate" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-shortlisted">Shortlist Candidate</a>
                                                </li>
                                                
                                                <li>
                                                    <a class="undo-candidate" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-undo">Undo Shortlisted Candidate</a>
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                </li>
                            </ul>
                        @endforeach
                    </li>
                    </ul>
                --}}
            </div>

            <!-- Schedule interview popup starts -->
            <div id="modal-schedule-interview"  class="modal text-left fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['method' => 'POST','files' => true, 'route' => ["jobopen.scheduleinterview" ]])!!}
                        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                            <h3 class="box-title">Schedule Interview</h3>
                            <div class="col-md-6 ">

                                {!! Form::hidden('candidate_id', null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '1' )) !!}
                                {!! Form::hidden('client_id', $client_id, array('id'=>'client_id','class' => 'form-control', 'tabindex' => '1' )) !!}
                                {!! Form::hidden('posting_title',  $job_id , array('id'=>'posting_title','class' => 'form-control', 'tabindex' => '1' )) !!}
                                {!! Form::hidden('job_id',  $job_id , array('id'=>'job_id','class' => 'form-control', 'tabindex' => '1' )) !!}

                                <div class="form-group {{ $errors->has('interview_date') ? 'has-error' : '' }}">
                                    <strong>Interview Date: <span class = "required_fields">*</span> </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('interview_date', isset($fromDateTime) ? $fromDateTime : null, array('id'=>'interview_date','placeholder' => 'Interview Date','class' => 'form-control' , 'tabindex' => '7' )) !!}
                                    </div>
                                    @if ($errors->has('interview_date'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('interview_date') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <strong>Status:</strong>
                                    {!! Form::select('status', $status,null, array('id'=>'status','class' => 'form-control', 'tabindex' => '10' )) !!}
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                                    <strong>Interview Venue:</strong>
                                    {!! Form::textarea('location', null, array('id'=>'location','placeholder' => 'Interview Venue','class' => 'form-control', 'tabindex' => '8' , 'rows' => '3')) !!}
                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('location') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                                    <strong>Interview Location:</strong>
                                    {!! Form::textarea('interview_location', null, array('id'=>'interview_location','placeholder' => 'Interview Location','class' => 'form-control', 'tabindex' => '10' , 'rows' => '3')) !!}
                                    @if ($errors->has('interview_location'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('interview_location') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <strong>Type:</strong>
                                    {!! Form::select('type',$type,null, array('id'=>'type','class' => 'form-control', 'tabindex' => '6', 'onchange' => 'skype()' )) !!}
                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group skype {{ $errors->has('skype_id') ? 'has-error' : '' }}" style="display: none;">
                                    <strong>Video Id:</strong>
                                    {!! Form::text('skype_id', null, array('id'=>'skype_id','class' => 'form-control', 'tabindex' => '4','placeholder' => 'Video Id')) !!}
                                    @if ($errors->has('skype_id'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('skype_id') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('interviewer_id') ? 'has-error' : '' }}">
                                    <strong>Interviewer:</strong>
                                    {!! Form::select('interviewer_id', $users, $user_id, array('id'=>'interviewer_id','class' => 'form-control', 'tabindex' => '5' )) !!}
                                    @if ($errors->has('interviewer_id'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('interviewer_id') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('candidate_location') ? 'has-error' : '' }}">
                                    <strong>Candidate Location:</strong>
                                    {!! Form::textarea('candidate_location', null, array('id'=>'candidate_location','placeholder' => 'Interview Venue','class' => 'form-control', 'tabindex' => '9' , 'rows' => '3')) !!}
                                    @if ($errors->has('candidate_location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('candidate_location') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    {!! Form::submit( 'Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- Schedule interview popup ends -->

            <!-- Add joining date popup starts -->
            <div id="modal-joining-date" class="modal text-left fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                            <div class="box-header with-border col-md-12 ">
                                <h3 class="box-title">Add Joining Date</h3>
                            </div>
                            {!! Form::hidden('candidate_id', null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '1' )) !!}

                            <div class="form-group">
                                <strong>Joining Date: <span class = "required_fields">*</span> </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('joining_date', null, array('id'=>'joining_date','placeholder' => 'Joining Date','class' => 'form-control' , 'tabindex' => '1' )) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button type="button"  onclick="addJoiningDate({{$job_id}})" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Add joining date popup ends -->

            <div id="modal-update-status" class="modal text-left fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['method' => 'POST','files' => true, 'route' => ["jobopen.updatecandidatestatus", $job_id ]])!!}
                        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                            <div class="box-header with-border col-md-6 ">
                                <h3 class="box-title">Select Status</h3>
                            </div>
                            {!! Form::hidden('candidate_id', null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '1' )) !!}
                            <div class="form-group {{ $errors->has('candiate_status_id') ? 'has-error' : '' }}">
                                {!! Form::select('candiate_status_id', $candidatestatus,null, array('id'=>'candiate_status_id','class' => 'form-control')) !!}
                                @if ($errors->has('candiate_status_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candiate_status_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="button"  onclick="update_candidate_status({{$job_id}})" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!-- Add Shortlisted Candidate popup -->
            <div id="modal-shortlisted" class="modal text-left fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['method' => 'POST','file' => true, 'route' => ["jobopen.shortlisted",$job_id]]) !!}

                         <div class="modal-header">

                            <h1 class="modal-title">Shortlist Candidate</h1>
                        </div>
                        <div class="modal-body">
                            {{--{!! Form::hidden('shortlisted', 1 , array('id'=>'shortlist','class' => 'form-control' )) !!}--}}
                            {!! Form::hidden('job_candidate_id', null , array('id'=>'job_candidate_id','class' => 'form-control' )) !!}
                            <p>
                                {!! Form::select('shortlist_type', $shortlist_type, null, array('id'=>'shortlist_type','class' => 'form-control')) !!}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"  class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
             <!-- End Shortlisted Candidate popup -->

             <!-- Add Undo Shortlisted Candidate popup -->
            <div id="modal-undo" class="modal text-left fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {!! Form::open(['method' => 'POST','file' => true, 'route' => ["jobopen.undo",$job_id]]) !!}

                         <div class="modal-header">

                            <h1 class="modal-title">Undo Shortlist Candidate</h1>
                        </div>
                        <div class="modal-body">
                            {!! Form::hidden('undoshortlisted', 0 , array('id'=>'undoshortlisted','class' => 'form-control' )) !!}
                            {!! Form::hidden('job_undo_candidate_id', null , array('id'=>'job_undo_candidate_id','class' => 'form-control' )) !!}
                            <p>
                                Are you sure want to undo shortlist Candidate ?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"  class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- End Undo Shortlisted Candidate popup -->
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>{{ Form::checkbox('candidate[]',0 ,null,array('id'=>'allcb')) }}</th>
            <th>Action</th>
            <th>Candidate Name</th>
            <th>Candidate Owner</th>
            <th>Candidate Email</th>
            <th>Candidate Status</th>
            <th>Round Cleared</th>
            <th>Associated Date/Time</th>

        </tr>
        <?php $i = 0; ?>
        @foreach ($candidates as $candidate)
            <?php
            $color='';
                 ?>
            <tr style="background-color: {{$color}}">
                <td>{{ Form::checkbox('candidate', $candidate->id,null,array('class'=>'others_cbs' ,'id'=>$candidate->id )) }}</td> 
                <td>
                    <ul class="nav navbar-nav">
                        <li class="dropdown messages-menu">
                            <a title="Select" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding:0!important;">
                                <i class="fa fa-sun-o"></i>
                            </a>
                            <ul class="dropdown-menu" style="margin-top: -140px !important;">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                        <ul class="menu" style="width: 100%;overflow: auto;height: 120px;">
                                            <li>
                                                <a class="schedule-interview" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-schedule-interview" >Schedule Interview</a>
                                            </li>
                                            <li>
                                                <a class="update-status-modal" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-update-status">
                                                    Update Status
                                                </a>
                                            </li>
                                            <li>
                                                <a class="joining-date" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-joining-date" >Add Joining Date</a>
                                            </li>
                                            @if(isset($access) && $access==true)
                                                <!-- <li>
                                                    <a class="sorted-candidate" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-shortlisted" >Shortlist Candidate</a>
                                                </li> -->
                                                @if($candidate->shortlisted==1 || $candidate->shortlisted==2 || $candidate->shortlisted==3)
                                                    <li>
                                                        <a class="undo-candidate" data-toggle="modal" data-id="{{$candidate->id}}" href="#modal-undo" >Undo Shortlisted Candidate</a>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                        <div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    &nbsp;
                    <a title="Edit Candidate Information"  href="/candidate/{{$candidate->id}}/edit?jobid={{$job_id}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    &nbsp;
                    <a title="Deassociate Candidate" onclick="deassociate_candidate('{{ $job_id }}' , '{{ $candidate->id }}');" style="cursor: pointer;">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </td>

                <?php
                    $utc = $candidate->job_associate_candidates_date;
                    $dt = new \DateTime($utc);
                    $tz = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

                    $dt->setTimezone($tz);
                    $associated_date = $dt->format('Y-m-d H:i:s');
                ?>
                @if($candidate->shortlisted==1 || $candidate->shortlisted==2)
                <td style="background:#FFFF00;"><a target="_blank" title="Show Candidate" href="{{ route('candidate.show',$candidate->cid) }}">{{ $candidate->fname or '' }}</a></td>
                <td>{{ $candidate->owner or '' }}</td>
                <td>{{ $candidate->email or '' }}</td>
                <td>{{ $candidate->status or '' }}</td>
                <td>{{ $shortlist_type[$candidate->shortlisted] or '-' }}</td>
                <td>{{ date('d-m-Y h:i A' , strtotime($associated_date)) }}</td>

                @elseif($candidate->shortlisted==3)
                <td style="background:#32CD32"><a target="_blank" title="Show Candidate" href="{{ route('candidate.show',$candidate->cid) }}" style="color:blue;">
                   {{ $candidate->fname or '' }}</a></td>
                <td>{{ $candidate->owner or '' }}</td>
                <td>{{ $candidate->email or '' }}</td>
                <td>{{ $candidate->status or '' }}</td>
                <td>{{ $shortlist_type[$candidate->shortlisted] or '-' }}</td>
                <td>{{ date('d-m-Y h:i A' , strtotime($associated_date)) }}</td>

                @else
                <td><a target="_blank" title="Show Candidate" href="{{ route('candidate.show',$candidate->cid) }}">{{ $candidate->fname or '' }}</a></td>
                <td>{{ $candidate->owner or '' }}</td>
                <td>{{ $candidate->email or '' }}</td>
                <td>{{ $candidate->status or '' }}</td>
                <td>{{ $shortlist_type[$candidate->shortlisted] or '-' }}</td>
                <td>{{ date('d-m-Y h:i A' , strtotime($associated_date)) }}</td>
                @endif

            </tr>
        @endforeach

    </table>
    <input type="hidden" name="token" id="token" value="{{ csrf_token() }}">

<div id="modal-mail" class="modal text-left fade candidate-mail" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Associated Candidate Mail</h1>
            </div>
            
            <div class="modal-body check-id">
                
            </div>
            <input type="hidden" name="candi_ids" id="candi_ids" value="">
            <input type="hidden" name="posting_title" id="posting_title" value="{{ $posting_title }}">
            <input type="hidden" name="job_id" id="job_id" value="{{ $job_id }}">
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submit">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-mail" class="modal text-left fade candidate-mail-user" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Select User for send mail</h2>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => 'jobs.associatedcandidatemail'])!!}
            <div class="modal-body mail_users">
                
            </div>
            <input type="hidden" name="can_ids" id="can_ids" value="">
            <input type="hidden" name="posting_title" id="posting_title" value="{{ $posting_title }}">
            <input type="hidden" name="job_id" id="job_id" value="{{ $job_id }}">
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Send</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-shortlist" class="modal text-left fade shortlist-candidate" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Shortlist Candidate</h2>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => 'jobs.shortlistedcandidate'])!!}

            <div class="modal-body">
                <div class="check-all-candidate-ids" style="display:none;">
                
                </div>

                <div class="shortlist-round" style="display:none;">
                    <p>
                    {!! Form::select('shortlist_type', $shortlist_type, null, array('id'=>'shortlist_type','class' => 'form-control')) !!}
                    </p>
                </div>

                <input type="hidden" name="all_can_ids" id="all_can_ids" value="">
                <input type="hidden" name="posting_title" id="posting_title" value="{{ $posting_title }}">
                <input type="hidden" name="job_id" id="job_id" value="{{ $job_id }}">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="shortlist-btn">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

   
@stop



@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#allcb').change(function () {
                if ($(this).prop('checked')) {
                    $('tbody tr td input[type="checkbox"]').each(function () {
                        $(this).prop('checked', true);
                    });
                } else {
                    $('tbody tr td input[type="checkbox"]').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });
            $('.others_cbs').change(function () {
                if ($(this).prop('checked')) {
                    if ($('.others_cbs:checked').length == $('.others_cbs').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else {
                    $("#allcb").prop('checked', false);
                }
            });

            $("#interview_date").datetimepicker({
                format:'DD-MM-YYYY h:mm A'
            });

            $("#joining_date").datetimepicker({
                format:'DD-MM-YYYY'
            });

            $(".schedule-interview").click(function() {
                $('#candidate_id').val($(this).data('id'));
            });

            $(".joining-date").click(function() {
                $('#candidate_id').val($(this).data('id'));
            });
            $(".update-status-modal").click(function() {
                $('#candidate_id').val($(this).data('id'));
            });

            $(".sorted-candidate").click(function() {
                $('#job_candidate_id').val($(this).data('id'));
            });

            $(".undo-candidate").click(function() {
                $('#job_undo_candidate_id').val($(this).data('id'));
            });

        });

        function deassociate_candidate(jobid,candidate_id) {
            var msg = "Do you really want to deassociate the candidate ?";
            var res = confirm(msg);

            var candidate_ids = new Array();
            var token = $("#token").val();
            var app_url = "{!! env('APP_URL'); !!}";

            if (res) {

                /*$("input:checkbox[name=candidate]:checked").each(function () {
                    candidate_ids.push($(this).val());
                });*/

                var url = app_url+'/jobs/deassociate_candidate';

                if (candidate_id > 0) {
                    var form = $('<form action="' + url + '" method="post">' +
                            '<input type="hidden" name="_token" value="' + token + '" />' +
                            '<input type="text" name="jobid" value="' + jobid + '" />' +
                            '<input type="text" name="candidate_id" value="' + candidate_id + '" />' +
                            '</form>');

                    $('body').append(form);
                    form.submit();

                }
                else {
                    alert("Select candidate to deassociate");
                }
            }
        }

        function update_candidate_status(jobid){
            var status_id = jQuery("#candiate_status_id > option:selected").val();
            var token = $("#token").val();
            var candidate_id = $("#candidate_id").val();
            var app_url = "{!! env('APP_URL'); !!}";
           /* $("input:checkbox[name=candidate]:checked").each(function () {
                candidate_ids.push($(this).val());
            });*/

            if (candidate_id > 0) {

                var url = app_url+'/jobs/updatecandidatestatus';

                var form = $('<form action="' + url + '" method="post">' +
                        '<input type="hidden" name="_token" value="' + token + '" />' +
                        '<input type="text" name="jobid" value="' + jobid + '" />' +
                        '<input type="text" name="candidate_id" value="' + candidate_id + '" />' +
                        '<input type="text" name="status_id" value="' + status_id + '" />' +
                        '</form>');

                $('body').append(form);
                form.submit();
            }
            else{
                alert("Select candidate to update status");
            }
        }

        
        function addJoiningDate(jobid) {

            var joining_date = $("#joining_date").val();
            var candidate_id = $("#candidate_id").val();
            var token = $("#token").val();
            var app_url = "{!! env('APP_URL'); !!}";

            if(joining_date!=''){

                var url = app_url+'/jobs/addjoiningdate';
                var form = $('<form action="' + url + '" method="post">' +
                    '<input type="hidden" name="_token" value="' + token + '" />' +
                    '<input type="text" name="jobid" value="' + jobid + '" />' +
                    '<input type="text" name="joining_date" value="' + joining_date + '" />' +
                    '<input type="text" name="candidate_id" value="' + candidate_id + '" />' +
                    '</form>');

                $('body').append(form);
                form.submit();

            }
            else{
                alert("Add joining date");
            }
        }

        function skype() {
            var type = $("#type").val();
            
            if (type == 'General Interview') {
                $(".skype").show();
            }
            else{
                $(".skype").hide();
            }
        }

       /* function associated_candidates_get(jobid){
            var candidate_ids = new Array();
            var token = $("#token").val();

            if(jobid>0){

                $("input:checkbox[name=candidate]:checked").each(function(){
                    candidate_ids.push($(this).val());
                });

                var url = '/jobs/associated_candidates_get';

                if(candidate_ids.length > 0){
                    var form = $('<form action="' + url + '" method="get">' +
                            '<input type="hidden" name="_token" value="'+token+'" />' +
                            '<input type="text" name="jobid" value="'+jobid+'" />' +
                            '<input type="text" name="candidate_ids" value="'+candidate_ids+'" />' +
                            '</form>');

                    $('body').append(form);
                    form.submit();
                }
            }
        }*/

        function associatedmail(){
            var token = $("#token").val();           
            var candidate_ids = new Array();
            var app_url = "{!! env('APP_URL'); !!}";
            
            $("input:checkbox[name=candidate]:checked").each(function(){
                candidate_ids.push($(this).val());
            });
            //alert(candidate_ids);

            $("#candi_ids").val(candidate_ids);
            $(".check-id").empty();
            $.ajax({
                type: 'POST',
                url: app_url+'/jobs/checkids',
                data: { can_ids:candidate_ids, '_token':token },
                success: function(msg){   
                    $(".candidate-mail").show();
                    if (msg.success == 'success') {
                        $(".check-id").append(msg.mail);
                        $('#submit').attr('onclick','usertosendmail()');
                    }
                    else{
                        $(".check-id").append(msg.err);
                    }
                }
            });
        }

        function usertosendmail(){
            var token = $("#token").val();           
            var candidate_ids = new Array();
            var app_url = "{!! env('APP_URL'); !!}";
            
            $("input:checkbox[name=candidate]:checked").each(function(){
                candidate_ids.push($(this).val());
            });
            //alert(candidate_ids);
            var job_id = $("#job_id").val();
            $("#can_ids").val(candidate_ids);
            $(".mail_users").empty();

            $.ajax({
                type: 'POST',
                url: app_url+'/jobs/usersforsendmail',
                data: { job_id:job_id, '_token':token },
                success: function(msg){   
                    $(".candidate-mail").modal('hide');
                    $(".candidate-mail-user").modal('show');
                    if (msg.success == 'success') {
                        $(".mail_users").append(msg.mail);
                    }
                    else{
                        $(".mail_users").append(msg.err);
                    }
                }
            });
        }

        function shortlistcandidate() {

            var token = $("#token").val();  
            var candidate_ids = new Array();
            var app_url = "{!! env('APP_URL'); !!}";
            
            $("input:checkbox[name=candidate]:checked").each(function(){
                candidate_ids.push($(this).val());
            });

            $("#all_can_ids").val(candidate_ids);
            $(".check-all-candidate-ids").empty();

            $.ajax(
            {
                type: 'POST',
                url: app_url+'/jobs/checkcandidateids',
                data: { can_ids:candidate_ids, '_token':token },
                success: function(msg)
                { 
                    $(".shortlist-candidate").show();
                    if (msg.success == 'success') {

                        $(".shortlist-round").show();
                        document.getElementById("shortlist-btn").disabled = false;
                    }
                    else{

                        $(".check-all-candidate-ids").show();
                        $(".check-all-candidate-ids").append(msg.err);
                        document.getElementById("shortlist-btn").disabled = true;
                    }
                }
            });
        }
    </script>
@endsection