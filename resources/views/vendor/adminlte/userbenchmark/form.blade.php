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
                <h2>Edit User Bench Mark</h2>
            @else
                <h2>Add New User Bench Mark</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('userbenchmark.index') }}"> Back</a>
        </div>
    </div>
</div>

@if(isset($action))
    @if($action == 'edit')
        {!! Form::model($user_bench_mark,['method' => 'PATCH', 'files' => true, 'route' => ['userbenchmark.update', $user_bench_mark['id']],'class'=> 'form-horizontal','id'=>'userbenchmarkForm']) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'userbenchmark.store', 'class'=> 'form-horizontal','id'=>'userbenchmarkForm']) !!}
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                            <strong>Select User: <span class = "required_fields">*</span> </strong>
                            {!! Form::select('user_id',$all_users,$select_user_id, array('id'=>'user_id','class' => 'form-control','tabindex' => '1')) !!}
                            @if ($errors->has('user_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('no_of_resumes') ? 'has-error' : '' }}">
                            <strong>No Of Resumes : <span class = "required_fields">*</span></strong>
                            {!! Form::number('no_of_resumes', null, array('placeholder' => 'No Of Resumes (Add Percentage)','class' => 'form-control', 'tabindex' => '2')) !!}
                            @if ($errors->has('no_of_resumes'))
                                <span class="help-block">
                                <strong>{{ $errors->first('no_of_resumes') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('shortlist_ratio') ? 'has-error' : '' }}">
                            <strong>Shortlist Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('shortlist_ratio', null, array('placeholder' => 'Shortlist Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '3')) !!}
                            @if ($errors->has('shortlist_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('shortlist_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('interview_ratio') ? 'has-error' : '' }}">
                            <strong>Interview Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('interview_ratio', null, array('placeholder' => 'Interview Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '4')) !!}
                            @if ($errors->has('interview_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('interview_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('selection_ratio') ? 'has-error' : '' }}">
                            <strong>Selection Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('selection_ratio', null, array('placeholder' => 'Selection Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '5')) !!}
                            @if ($errors->has('selection_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('selection_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('offer_acceptance_ratio') ? 'has-error' : '' }}">
                            <strong>Offer Acceptance Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('offer_acceptance_ratio', null, array('placeholder' => 'Offer Acceptance Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '6')) !!}
                            @if ($errors->has('offer_acceptance_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('offer_acceptance_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('joining_ratio') ? 'has-error' : '' }}">
                            <strong>Joining Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('joining_ratio', null, array('placeholder' => 'Joining Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '7')) !!}
                            @if ($errors->has('joining_ratio'))
                                <span class="help-block">
                                <strong>{{ $errors->first('joining_ratio') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('after_joining_success_ratio') ? 'has-error' : '' }}">
                            <strong>After Joining Success Ratio : <span class = "required_fields">*</span></strong>
                            {!! Form::number('after_joining_success_ratio', null, array('placeholder' => 'After Joining Success Ratio (Add Percentage)','class' => 'form-control', 'tabindex' => '8')) !!}
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
            {!! Form::submit(isset($user_bench_mark) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
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

            $("#user_id").select2();

            $("#userbenchmarkForm").validate({
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
    </script>
@endsection