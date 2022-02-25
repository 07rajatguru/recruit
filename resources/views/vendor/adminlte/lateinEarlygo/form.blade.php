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
                <h2>Edit Request</h2>
            @else
                <h2>Add New Request</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('late-early.index') }}">Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($leave, ['method' => 'PATCH','files' => true,'route' => ['late-early.update', $leave->id], 'id' => 'leave_form', 'autocomplete' => 'off','onsubmit' => "return leaveValidation()"]) !!}
@else
    {!! Form::open(array('route' => 'late-early.store','method'=>'POST','files' => true, 'id' => 'leave_form', 'autocomplete' => 'off','onsubmit' => "return leaveValidation()")) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <strong>Subject: <span class = "required_fields">*</span></strong>
                        {!! Form::text('subject',null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1')) !!}
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                        <strong>Date: <span class = "required_fields">*</span></strong>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            {!! Form::text('date', isset($date) ? $date : null, array('id'=>'date','placeholder' => 'Date','class' => 'form-control', 'tabindex' => '2')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('leave_type') ? 'has-error' : '' }}">
                        <strong>Request Type: <span class = "required_fields">*</span></strong>
                        {!! Form::select('leave_type', $leave_type,$selected_leave_type, array('id' => 'leave_type','class' => 'form-control','tabindex' => '3')) !!}
                        @if ($errors->has('leave_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('leave_type') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                        <strong>Message: <span class = "required_fields">*</span></strong>
                        {!! Form::textarea('message', null, array('id'=>'message','placeholder' => 'Message','class' => 'form-control', 'tabindex' => '6')) !!}
                        @if ($errors->has('message'))
                            <span class="help-block">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($leave) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>

<input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{ $loggedin_user_id }}">

{!! Form::close() !!}

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#message").wysihtml5();

            $("#leave_form").validate({
                rules: {
                    "subject": {
                        required: true
                    },
                    "date": {
                        required: true
                    },
                    "leave_type": {
                        required: true
                    },
                },
                messages: {
                    "subject": {
                        required: "Subject is Required Field."
                    },
                    "date": {
                        required: "Please Select Date."
                    },
                    "leave_type": {
                        required: "Please Select Request Type."
                    },
                }
            });
        });

        function leaveValidation() {

            // For calculate leaves added by user

            var loggedin_user_id = $("#loggedin_user_id").val();
            var app_url = "{!! env('APP_URL') !!}";
            var token = $("input[name=_token]").val();
            var leave_type = $("#leave_type").val();
            var date = $("#date").val();

            var arr = date.split('-');
            var selected_month = arr[1];

            // Check monthwise leave

            var month_arr = ['01','02','03','04','05','06','07','08','09','10','11','12'];

            dt = new Date();
            var current_month = month_arr[dt.getMonth()];

            if(selected_month == current_month) {

                $.ajax({

                    type: 'GET',
                    url:app_url+'/late-in-early-go/count',
                    data: {'_token':token, loggedin_user_id:loggedin_user_id},
                    dataType:'json',
                    success: function(data) {

                        if (data >= '3') {
                                
                            alert('You Already Take 3 Early Go / Late In in this month.');
                            return false;
                        }
                        else {

                            return true;
                        }
                    }
                });
            }
            else {
                return true;
            }
        }
    </script>
@endsection