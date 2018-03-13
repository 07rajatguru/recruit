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
            <a class="btn btn-primary" href="{{ route('interview.index') }}"> Back</a>
        </div>

    </div>

</div>

@if(isset($action))

    @if($action == 'edit')
        {!! Form::model($interview,['method' => 'PUT', 'files' => true, 'route' => ['interview.update', $interview['id']],'id'=>'interview_form', 'novalidate'=>'novalidate']) !!}
        {!! Form::hidden('candidateId', $interview['id'], array('id'=>'candidateId')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'interview.store','id'=>'interview_form', 'novalidate'=>'novalidate']) !!}
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
                            <div class="form-group {{ $errors->has('interview_name') ? 'has-error' : '' }}">
                                <strong>Interview Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('interview_name', null, array('id'=>'interview_name','placeholder' => 'Interview Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('interview_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interview_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_id') ? 'has-error' : '' }}">
                                <strong>Candidate: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('candidate_id', $candidate,null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '3' )) !!}
                                @if ($errors->has('candidate_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
                                <strong>Interview Date: <span class = "required_fields">*</span> </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('interview_date', isset($fromDateTime) ? $fromDateTime : null, array('id'=>'from','placeholder' => 'Interview Date','class' => 'form-control' , 'tabindex' => '7' )) !!}
                                </div>
                                @if ($errors->has('from'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('from') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                                <strong>Location:</strong>
                                {!! Form::text('location', null, array('id'=>'location','placeholder' => 'Location','class' => 'form-control', 'tabindex' => '9' )) !!}
                                @if ($errors->has('location'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('location') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                                <strong>Comments:</strong>
                                {!! Form::textarea('comments', null, array('id'=>'comments','placeholder' => 'Comments','class' => 'form-control', 'tabindex' => '11' )) !!}
                                @if ($errors->has('comments'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('comments') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                                <strong>Posting Name:</strong>
                                {!! Form::select('posting_title', $postingArray , null, array('id'=>'posting_title', 'class' => 'form-control', 'tabindex' => '2' )) !!}
                                {{--{!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '2' )) !!}--}}
                                @if ($errors->has('posting_title'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('posting_title') }}</strong>
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
                                {!! Form::select('type', $type,null, array('id'=>'type','class' => 'form-control', 'tabindex' => '6' )) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                <strong>Status:</strong>
                                {!! Form::select('status', $status,null, array('id'=>'status','class' => 'form-control', 'tabindex' => '10' )) !!}
                                {{--                                {!! Form::text('status', null, array('id'=>'status','placeholder' => 'Status','class' => 'form-control', 'tabindex' => '10' )) !!}--}}
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>

                            {{--<div class="form-group {{ $errors->has('interview_owner_id') ? 'has-error' : '' }}">
                                <strong>Interview Owner:</strong>
                                {!! Form::select('interview_owner_id', $users,\Auth::user()->id, array('id'=>'interview_owner_id','class' => 'form-control', 'tabindex' => '12','disabeled' )) !!}
                                @if ($errors->has('interview_owner_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interview_owner_id') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('interviewer_id') ? 'has-error' : '' }}">
                                <strong>Interview Cordination:</strong>
                                {!! Form::select('interviewer_id', $users, $user_id, array('id'=>'interviewer_id','class' => 'form-control', 'tabindex' => '5')) !!}
                                @if ($errors->has('interviewer_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('interviewer_id') }}</strong>
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
                    "interview_name": {
                        required: true
                    },
                    "candidate_id": {
                        required: true
                    },
                    /*"from": {
                        required: true
                    },
                    "to": {
                        required: true
                    }*/
                },
                messages: {
                    "interview_name": {
                        required: "Interview Name is required."
                    },
                    "candidate_id": {
                        required: "Candidate is required."
                    },
                    /*"from": {
                        required: "From Date is required."
                    },
                    "to": {
                        required: "To Date is required."
                    }*/
                }
            });

            $("#from").datetimepicker({
                format:'DD-MM-YYYY HH:mm:ss'
            });
            $("#to").datetimepicker({
                format:'DD-MM-YYYY HH:mm:ss'
            });

             $("#candidate_id").select2();
             $("#posting_title").select2();
             $("#interviewer_id").select2();

        });
    </script>
@endsection