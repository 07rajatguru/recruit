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
            @if($action == 'edit')
                <h2>Edit Benchmark</h2>
            @else
                <h2>Add New Benchmark</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('rolewisebenchmark.index') }}">Back</a>
        </div>
    </div>
</div>

@if(isset($action))

    @if($action == 'edit')
        {!! Form::model($bench_mark,['method' => 'PATCH', 'files' => true, 'route' => ['rolewisebenchmark.update', $bench_mark['id']],'class'=> 'form-horizontal','id'=>'benchmarkForm']) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'rolewisebenchmark.store', 'class'=> 'form-horizontal','id'=>'benchmarkForm']) !!}
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">

                        <div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
                            <strong>Select Role: <span class = "required_fields">*</span> </strong>
                            {!! Form::select('role_id',$all_roles,$select_role_id, array('id'=>'role_id','class' => 'form-control','tabindex' => '1')) !!}
                            @if ($errors->has('role_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('role_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('no_of_resumes') ? 'has-error' : '' }}">
                            <strong>No Of Resumes : <span class = "required_fields">*</span></strong>
                            {!! Form::number('no_of_resumes', null, array('placeholder' => 'No Of Resumes','class' => 'form-control', 'tabindex' => '2','id' => 'no_of_resumes')) !!}
                            @if ($errors->has('no_of_resumes'))
                                <span class="help-block">
                                <strong>{{ $errors->first('no_of_resumes') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('shortlist_ratio') ? 'has-error' : '' }}">
                            <strong>Shortlist Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('shortlist_ratio', null, array('placeholder' => 'Shortlist Ratio','class' => 'form-control', 'tabindex' => '3','id' => 'shortlist_ratio','onfocusout' => 'checkShortlistRatio();')) !!}
                            @if ($errors->has('shortlist_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('shortlist_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('interview_ratio') ? 'has-error' : '' }}">
                            <strong>Interview Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('interview_ratio', null, array('placeholder' => 'Interview Ratio','class' => 'form-control', 'tabindex' => '4','id' => 'interview_ratio','onfocusout' => 'checkInterviewRatio();')) !!}
                            @if ($errors->has('interview_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('interview_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('selection_ratio') ? 'has-error' : '' }}">
                            <strong>Selection Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('selection_ratio', null, array('placeholder' => 'Selection Ratio','class' => 'form-control', 'tabindex' => '5','id' => 'selection_ratio','onfocusout' => 'checkSelectionRatio();')) !!}
                            @if ($errors->has('selection_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('selection_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('offer_acceptance_ratio') ? 'has-error' : '' }}">
                            <strong>Offer Acceptance Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('offer_acceptance_ratio', null, array('placeholder' => 'Offer Acceptance Ratio','class' => 'form-control', 'tabindex' => '6','id' => 'offer_acceptance_ratio','onfocusout' => 'checkOfferAcceptanceRatio();')) !!}
                            @if ($errors->has('offer_acceptance_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('offer_acceptance_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('joining_ratio') ? 'has-error' : '' }}">
                            <strong>Joining Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('joining_ratio', null, array('placeholder' => 'Joining Ratio','class' => 'form-control', 'tabindex' => '7','id' => 'joining_ratio','onfocusout' => 'checkJoiningRatio();')) !!}
                            @if ($errors->has('joining_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('joining_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('after_joining_success_ratio') ? 'has-error' : '' }}">
                            <strong>After Joining Success Ratio (Add Percentage) : <span class = "required_fields">*</span></strong>
                            {!! Form::number('after_joining_success_ratio', null, array('placeholder' => 'After Joining Success Ratio','class' => 'form-control', 'tabindex' => '8','id' => 'after_joining_success_ratio','onfocusout' => 'checkAfterJoiningSuccessRatio();')) !!}
                            @if ($errors->has('after_joining_success_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('after_joining_success_ratio') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($bench_mark) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}
@else
    <div class="error-page">
        <h2 class="headline text-info">403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Whoops, looks like something went wrong.</h3>
        </div>
    </div>
@endif

@section('customscripts')
    <script>
        $(document).ready(function() {

            $("#role_id").select2();

            $('#no_of_resumes').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#shortlist_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#interview_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#selection_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#offer_acceptance_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#joining_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $('#after_joining_success_ratio').keypress(function (e) {
                if(e.which == 44 || e.which == 46) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $("#benchmarkForm").validate({
                rules: {
                    "no_of_resumes": {
                        required: true
                    },
                    "shortlist_ratio": {
                        required: true
                    },
                    "interview_ratio": {
                        required: true
                    },
                    "selection_ratio": {
                        required: true
                    },
                    "offer_acceptance_ratio": {
                        required: true
                    },
                    "joining_ratio": {
                        required: true
                    },
                    "after_joining_success_ratio": {
                        required: true
                    }
                },
                messages: {
                    "no_of_resumes": {
                        required: "No Of Resumes is Required."
                    },
                    "shortlist_ratio": {
                        required: "Shortlist Ratio is Required."
                    },
                    "interview_ratio": {
                        required: "Interview Ratio is Required."
                    },
                    "selection_ratio": {
                        required: "Selection Ratio is Required."
                    },
                    "offer_acceptance_ratio": {
                        required: "Offer Acceptance Ratio is Required."
                    },
                    "joining_ratio": {
                        required: "Joining Ratio is Required."
                    },
                    "after_joining_success_ratio": {
                        required: "After Joining Success Ratio is Required."
                    }
                }
            });
        });

        function checkShortlistRatio() {

            var shortlist_ratio = $("#shortlist_ratio").val();

            if(shortlist_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("shortlist_ratio").value = '';
                document.getElementById("shortlist_ratio").focus(); 
                return false;
            }
            return true;
        }

        function checkInterviewRatio() {

            var interview_ratio = $("#interview_ratio").val();

            if(interview_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("interview_ratio").value = '';
                document.getElementById("interview_ratio").focus(); 
                return false;
            }
            return true;
        }

        function checkSelectionRatio() {

            var selection_ratio = $("#selection_ratio").val();

            if(selection_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("selection_ratio").value = '';
                document.getElementById("selection_ratio").focus(); 
                return false;
            }
            return true;
        }

        function checkOfferAcceptanceRatio() {

            var offer_acceptance_ratio = $("#offer_acceptance_ratio").val();

            if(offer_acceptance_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("offer_acceptance_ratio").value = '';
                document.getElementById("offer_acceptance_ratio").focus(); 
                return false;
            }
            return true;
        }

        function checkJoiningRatio() {

            var joining_ratio = $("#joining_ratio").val();

            if(joining_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("joining_ratio").value = '';
                document.getElementById("joining_ratio").focus(); 
                return false;
            }
            return true;
        }

        function checkAfterJoiningSuccessRatio() {

            var after_joining_success_ratio = $("#after_joining_success_ratio").val();

            if(after_joining_success_ratio > 100) {

                alert('Please Enter Percentage Between 1 to 100.');
                document.getElementById("after_joining_success_ratio").value = '';
                document.getElementById("after_joining_success_ratio").focus(); 
                return false;
            }
            return true;
        }
    </script>
@endsection