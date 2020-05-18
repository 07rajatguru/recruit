@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Interview</h2>
            @else
                <h2>Create Interview</h2>
            @endif
        </div>
        <div class="pull-right">
        @if( $action == 'edit')
            @if( $source == 'index')
                <a class="btn btn-primary" href="{{ route('interview.index') }}"> Back</a>
            @elseif( $source == 'tti')
                <a class="btn btn-primary" href="{{ route('interview.todaytomorrow') }}"> Back</a>
            @else( $source == 'ai')
                <a class="btn btn-primary" href="{{ route('interview.index') }}">Back</a>
            @endif
        @else
            <a class="btn btn-primary" href="{{ route('interview.index') }}"> Back</a>
        @endif
        </div>
    </div>
</div>

@if(isset($action))

    @if($action == 'edit')
        {!! Form::model($interview,['method' => 'PUT', 'files' => true, 'route' => ['interview.update', $interview['id'],$source],'id'=>'interview_form', 'novalidate'=>'novalidate','autocomplete' => 'off']) !!}
        {!! Form::hidden('candidateId', $interview['id'], array('id'=>'candidateId')) !!}
        {!! Form::hidden('source', $source, array('id'=>'source')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'interview.store','id'=>'interview_form', 'novalidate'=>'novalidate','autocomplete' => 'off']) !!}
    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">

                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            {{--<div class="form-group {{ $errors->has('interview_name') ? 'has-error' : '' }}">
                                <strong>Interview Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('interview_name', null, array('id'=>'interview_name','placeholder' => 'Interview Name','class' => 'form-control')) !!}
                                @if ($errors->has('interview_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interview_name') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                                <strong>Posting Name: <span class = "required_fields">*</span></strong>
                                {!! Form::select('posting_title', $postingArray , null, array('id'=>'posting_title', 'class' => 'form-control', 'tabindex' => '1' , 'onchange' => 'getCandidate()' )) !!}
                                {{--{!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '2' )) !!}--}}
                                @if ($errors->has('posting_title'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('posting_title') }}</strong>
                                </span>
                                @endif
                            </div>
                            
                            <input type="hidden" id="hidden_candidate_id" value="{{$hidden_candidate_id}}" name="hidden_candidate_id" />

                            <div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
                                <strong>Interview Date: <span class = "required_fields">*</span> </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('interview_date', isset($fromDateTime) ? $fromDateTime : null, array('id'=>'from','placeholder' => 'Interview Date','class' => 'form-control' , 'tabindex' => '3' )) !!}
                                </div>
                                @if ($errors->has('from'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('from') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                <strong>Status:</strong>
                                {!! Form::select('status', $status,null, array('id'=>'status','class' => 'form-control', 'tabindex' => '5' )) !!}
                                {{--  {!! Form::text('status', null, array('id'=>'status','placeholder' => 'Status','class' => 'form-control')) !!}--}}
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('round') ? 'has-error' : '' }}">
                                <strong>Select Round:</strong>
                                {!! Form::select('round', $round, $interview_round, array('id'=>'round','class' => 'form-control' )) !!}
                                @if ($errors->has('round'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('round') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('about') ? 'has-error' : '' }}">
                                <strong>About Client:</strong>
                                {!! Form::textarea('about', null, array('id'=>'about','placeholder' => 'About Client','class' => 'form-control', 'tabindex' => '7', 'rows' => '5' )) !!}
                                @if ($errors->has('about'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('about') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                                <strong>Comments:</strong>
                                {!! Form::textarea('comments', null, array('id'=>'comments','placeholder' => 'Comments','class' => 'form-control', 'tabindex' => '10', 'rows' => '5' )) !!}
                                @if ($errors->has('comments'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('comments') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>


                        <div class="box-body col-xs-6 col-sm-6 col-md-6">

                            <div class="form-group {{ $errors->has('candidate_id') ? 'has-error' : '' }}">
                                <strong>Candidate: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('candidate_id', array(''=>'Select Type List'),null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('candidate_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('candidate_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            

                           {{-- <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                                <strong>Client:</strong>
                                {!! Form::select('client_id', $client,null, array('id'=>'client_id','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('client_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('client_id') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong>Type:</strong>
                                {!! Form::select('type', $type,null, array('id'=>'type','class' => 'form-control', 'tabindex' => '4', 'onchange' => 'skype()' )) !!}
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

                            {{--<div class="form-group {{ $errors->has('interview_owner_id') ? 'has-error' : '' }}">
                                <strong>Interview Owner:</strong>
                                {!! Form::select('interview_owner_id', $users,\Auth::user()->id, array('id'=>'interview_owner_id','class' => 'form-control', 'tabindex' => '8','disabeled' )) !!}
                                @if ($errors->has('interview_owner_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interview_owner_id') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('interviewer_id') ? 'has-error' : '' }}">
                                <strong>Interview Cordination:</strong>
                                {!! Form::select('interviewer_id', $users, $interviewer_id, array('id'=>'interviewer_id','class' => 'form-control', 'tabindex' => '6')) !!}
                                @if ($errors->has('interviewer_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interviewer_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                                <strong>Interview Venue:</strong>
                                {!! Form::textarea('location', null, array('id'=>'location','placeholder' => 'Interview Venue','class' => 'form-control', 'tabindex' => '9' , 'rows' => '3')) !!}
                                @if ($errors->has('location'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('location') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                                <strong>Interview Location:</strong>
                                {!! Form::textarea('interview_location', null, array('id'=>'interview_location','placeholder' => 'Interview Location','class' => 'form-control', 'tabindex' => '9' , 'rows' => '3')) !!}
                                @if ($errors->has('interview_location'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interview_location') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_location') ? 'has-error' : '' }}">
                                <strong>Candidate Location:</strong>
                                {!! Form::textarea('candidate_location', null, array('id'=>'candidate_location','placeholder' => 'Candidate Location','class' => 'form-control', 'tabindex' => '9' , 'rows' => '3')) !!}
                                @if ($errors->has('candidate_location'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate_location') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>

                    </div>

                    </div>
                </div>
            </div>

        <div class="form-group">
            <div class="col-sm-2">&nbsp;</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($interview) ? 'Update' : 'Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
            </div>
        </div>
    </div>




    {!! Form::close() !!}
@else

    <div class="error-page">
        <h2 class="headline text-info"> 403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Whoops, looks like something went wrong.</h3>

        </div><!-- /.error-content -->
    </div>

@endif

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $("#interview_form").validate({
                rules: {
                    "posting_title": {
                        required: true
                    },
                    "candidate_id": {
                        required: true
                    },
                    "interview_date": {
                        required: true
                    },
                    /*"to": {
                        required: true
                    }*/
                },
                messages: {
                    "posting_title": {
                        required: "Posting Title is required."
                    },
                    "candidate_id": {
                        required: "Candidate is required."
                    },
                    "interview_date": {
                        required: "Interview Date is required."
                    },
                    /*"to": {
                        required: "To Date is required."
                    }*/
                }
            });

           // $("#about").wysihtml5();
            $("#from").datetimepicker({
                format:'DD-MM-YYYY h:mm A'
            });
            $("#to").datetimepicker({
                format:'DD-MM-YYYY HH:mm:ss'
            });

             $("#candidate_id").select2();
             $("#posting_title").select2();
             $("#interviewer_id").select2();

             getCandidate();
             skype();
        });

        function skype() {
            var type = $("#type").val();
            
            //if (type == 'General Interview') {
            if (type == 'Video Interview') {
                $(".skype").show();
            }
            else{
                $(".skype").hide();
            }
        }

         function getCandidate(){
             var app_url = "{!! env('APP_URL') !!}";
            var job_id = $("#posting_title").val();
            var hidden_candidate_id = $("#hidden_candidate_id").val();

            if(job_id>0){
                $.ajax({
                    url:app_url+'/job/associatedcandidate',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        $("#candidate_id").empty();
                        var response = data.data;
                        for(var i=0;i<response.length;i++){
                            $('#candidate_id').append($('<option></option>').val(response[i].id).html(response[i].value));
                            $("#candidate_id").select2('val',hidden_candidate_id);
                        }
                    }
                });

                 $.ajax({
                     url:app_url+'/interview/getclientinfos',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        var cabout = data.cabout;
                        
                        $('#about').val(cabout);
                    }
                });
            }
        }
    </script>
@endsection