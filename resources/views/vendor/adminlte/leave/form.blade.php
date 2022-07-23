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
                <h2>Edit Leave Application</h2>
            @else
                <h2>Add New Leave Application</h2>
            @endif

            @if(isset($leave_balance) && $leave_balance != '')
                <h4> (PL Balance : {{ $leave_balance->leave_remaining or 0 }}, SL Balance : {{ $leave_balance->seek_leave_remaining or 0 }})</h4>
            @else
                <h4> (PL Balance : 0, SL Balance : 0)</h4>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('leave.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($leave, ['method' => 'PATCH','files' => true,'route' => ['leave.update', $leave->id], 'id' => 'leave_form', 'autocomplete' => 'off','onsubmit' => "return sendEmail()"]) !!}
@else
    {!! Form::open(array('route' => 'leave.store','method'=>'POST','files' => true, 'id' => 'leave_form', 'autocomplete' => 'off','onsubmit' => "return checkLeaveBalance()")) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <strong>Subject: <span class = "required_fields">*</span></strong>
                            {!! Form::text('subject',null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1')) !!}
                            @if ($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('from_date') ? 'has-error' : '' }}">
                            <strong>From Date: <span class = "required_fields">*</span></strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>

                                {!! Form::text('from_date', isset($from_date) ? $from_date : null, array('id'=>'from_date','placeholder' => 'From Date','class' => 'form-control', 'tabindex' => '3')) !!}
                            </div>
                        </div>

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

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    
                    <div class="form-group {{ $errors->has('leave_category') ? 'has-error' : '' }}">
                        <strong>Leave Category: <span class = "required_fields">*</span></strong>
                        
                        {!! Form::select('leave_category', $leave_category, $selected_leave_category, array('id' => 'leave_category', 'class' => 'form-control','tabindex' => '2', 'onchange' => 'category();')) !!}

                        @if ($errors->has('leave_category'))
                            <span class="help-block">
                                <strong>{{ $errors->first('leave_category') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group to_date {{ $errors->has('to_date') ? 'has-error' : '' }}">
                        <strong>To Date: <span class = "required_fields">*</span></strong>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            {!! Form::text('to_date', isset($to_date) ? $to_date : null, array('id'=>'to_date','placeholder' => 'To Date','class' => 'form-control', 'tabindex' => '4')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('leave_type') ? 'has-error' : '' }}">
                        <strong>Leave Type: <span class = "required_fields">*</span></strong>
                        {!! Form::select('leave_type', $leave_type,$selected_leave_type, array('id' => 'leave_type','class' => 'form-control','tabindex' => '5', 'onchange' => 'displayHalfDayOptions();')) !!}
                        @if ($errors->has('leave_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('leave_type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group half_options {{ $errors->has('half_leave_type') ? 'has-error' : '' }}" style="display: none;">
                        <strong>Select Type: <span class = "required_fields">*</span></strong>
                        {!! Form::select('half_leave_type', $half_leave_type,$selected_half_leave_type, array('id' => 'half_leave_type','class' => 'form-control','tabindex' => '6')) !!}
                        @if ($errors->has('half_leave_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('half_leave_type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group document" style="display: none;">
                        <strong>Attachment: </strong>
                        <input type="file" name="leave_doc[]"  id="leave_doc" class="form-control" multiple>
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

    <input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{ $loggedin_user_id }}">

    @if(isset($leave_balance) && $leave_balance != '')
        <input type="hidden" name="pl_balance" id="pl_balance" value="{{ $leave_balance->leave_remaining }}">
        <input type="hidden" name="sl_balance" id="sl_balance" value="{{ $leave_balance->seek_leave_remaining }}">
    @else
        <input type="hidden" name="pl_balance" id="pl_balance" value="0">
        <input type="hidden" name="sl_balance" id="sl_balance" value="0">
    @endif
</div>

<div class="modal fade" id="leaveBalanceModal" tabindex="-1" role="dialog" aria-labelledby="leaveBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="leaveBalanceModalLabel">Leave Balance</h5></center>
            </div>
            <div class="modal-body">
                <div class="container" style="width:350px;">
                    <p class="display_content"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitForm();">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="email_value" id="email_value" value="">

{!! Form::close() !!}

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            displayHalfDayOptions();

            $("#from_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#to_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#message").wysihtml5();

            $("#leave_form").validate({
                rules: {
                    "subject": {
                        required: true
                    },
                    "from_date": {
                        required: true
                    },
                    "to_date": {
                        required: true
                    },
                    "leave_type": {
                        required: true
                    },
                    "leave_category": {
                        required: true
                    },
                    "half_leave_type": {
                        required: true
                    },
                },
                messages: {
                    "subject": {
                        required: "Subject is Required Field."
                    },
                    "from_date": {
                        required: "From Date is Required Field."
                    },
                    "to_date": {
                        required: "To Date is Required Field."
                    },
                    "leave_type": {
                        required: "Leave Type is Required Field."
                    },
                    "leave_category": {
                        required: "Leave Category is Required Field."
                    },
                    "half_leave_type": {
                        required: "Please Select Option."
                    },
                }
            });
        });

        function category() {

            var leave_cat = $("#leave_category").val();

            if (leave_cat == 'Sick Leave') {
                $(".document").show();
            }
            else {
                $(".document").hide();
            }
        }

        function checkLeaveBalance() {

            // Calculate Days From Selected Dates
            var leave_cat = $("#leave_category").val();
            var leave_type = $("#leave_type").val();

            var from_date = $("#from_date").val();
            var temp_from_date = from_date.split('-');
            var new_from_date = temp_from_date[2]+"-"+temp_from_date[1]+"-"+temp_from_date[0];

            var to_date = $("#to_date").val();
            var temp_to_date = to_date.split('-');
            var new_to_date = temp_to_date[2]+"-"+temp_to_date[1]+"-"+temp_to_date[0];

            const diffInMs = new Date(new_to_date) - new Date(new_from_date);
            const diffInDays = diffInMs / (1000 * 60 * 60 * 24);
            var days = diffInDays + 1;

            if(leave_type == 'Half Day') {
                var total_days = days/2;
            }
            else if(leave_type == 'Full Day') {
                var total_days = days;
            }

            var pl_balance = $("#pl_balance").val();
            var sl_balance = $("#sl_balance").val();

            if(leave_cat == 'Privilege Leave') {

                if(pl_balance != '' && pl_balance < total_days) {

                    var rest_balance = total_days - pl_balance;

                    var msg = 'You have '+pl_balance+' PL Balance, rest '+rest_balance+' leaves will fall into LWP, do you still want to apply?';

                    $(".display_content").empty();
                    $(".display_content").append(msg);
                    $("#leaveBalanceModal").modal('show');
                    return false;
                }
            }
            else if(leave_cat == 'Sick Leave') {

                if(sl_balance != '' && sl_balance < total_days) {

                    var rest_balance = total_days - sl_balance;
                    
                    var msg = 'You have '+sl_balance+' SL Balance, rest '+rest_balance+' leaves will fall into LWP, do you still want to apply?';

                    $(".display_content").empty();
                    $(".display_content").append(msg);
                    $("#leaveBalanceModal").modal('show');
                    return false;
                }
            }
            submitForm();
        }

        function displayHalfDayOptions() {

            var leave_type = $("#leave_type").val();

            if (leave_type == 'Half Day') {
                $(".half_options").show();
            }
            else {
                $(".half_options").hide();
            }
        }

        function sendEmail() {

            checkLeaveBalance();

            var msg = "Send an email with updated details?";
            var confirmvalue = confirm(msg);

            if(confirmvalue) {
                $("#email_value").val(confirmvalue);
            }
            return false;
        }

        function submitForm() {

            document.forms['leave_form'].submit();
            return true;
        }
    </script>
@endsection