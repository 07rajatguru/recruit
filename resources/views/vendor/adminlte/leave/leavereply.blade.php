@extends('adminlte::page')

@section('title', 'Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{!! $leave_details['subject'] !!}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('leave.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            	<br/>
            	<div class="col-xs-12 col-sm-12 col-md-12">
            		<table class="table table-bordered">
                        <tr>
                            <th>User Name</th>
                            @if(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '0')
                                <td style="background-color:#FFCC00;">{{ $leave_details['uname'] }}</td>
                            @elseif(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '1')
                                <td style="background-color:#e87992;">{{ $leave_details['uname'] }}</td>
                            @elseif(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '2')
                                <td style="background-color:#f17a40;">{{ $leave_details['uname'] }}</td>
                            @else
                                <td>{{ $leave_details['uname'] }}</td>
                            @endif
                            <th>From Date</th>
                            <td>{{ $leave_details['from_date'] }}</td>
                            <th>To Date</th>
                            <td>{{ $leave_details['to_date'] }}</td>
                        </tr>

                        <tr>
                            <th>Leave Type</th>

                            @if(isset($leave_details['half_leave_type']) && $leave_details['half_leave_type'] != '')
                                <td>{{ $leave_details['type_of_leave'] }} - {{ $leave_details['half_leave_type'] }}</td>
                            @else
                                <td>{{ $leave_details['type_of_leave'] }}</td>
                            @endif
                            
                            <th>Leave Category</th>
                            <td>{{ $leave_details['category'] }}</td>
                            <th>Status</th>

                            @if($leave_details['status'] == 0)
                                <td style="background-color:#8FB1D5;">Pending</td>
                            @elseif($leave_details['status'] == 1)
                                <td style="background-color:#32CD32;">Approved</td>
                            @elseif($leave_details['status'] == 2)
                                <td style="background-color:#F08080;">Rejected</td>
                            @endif
                        </tr>
                        
                        <tr>
                            <th>Leave Message</th>
                            <td colspan="6">{!! $leave_details['message'] !!}</td>
                        </tr>

                        @if(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1')
                            <tr>
                                <th>Leave Cancel Date</th>
                                <td>{{ $leave_details['leave_cancel_date'] }}</td>
                                <th>Leave Cancel Remarks</th>
                                <td colspan="4">{{ $leave_details['leave_cancel_remarks'] }}</td>
                            </tr>
                        @endif

                        @if(isset($leave_details['remarks']) && $leave_details['remarks'] != '')
                            <tr>
                                <th>Remarks</th>
                                <td colspan="6">{!! $leave_details['remarks'] !!}</td>
                            </tr>
                        @endif

                        @if(isset($leave_details['category']) && $leave_details['category'] == 'Privilege Leave')
                            <tr>
                                <th>Note : </th>
                                <td colspan="6">As per leave policy, the employee is expected to apply for Privileged leaves with 3 days prior intimation.
                                The approver is expected to understand the reason of leave application before approving or rejecting.</td>
                            </tr>
                        @elseif(isset($leave_details['category']) && $leave_details['category'] == 'Sick Leave')
                            <tr>
                                <th>Note : </th>
                                <td colspan="6">To avail the benefit of sick leave as per leave policy, the employee is expected to apply for it immediately after returning from the leave in case if not apply before. For more than two working days, the employee is expected to submit a medical certificate to the Reporting Manager to approve the same.</td>
                            </tr>
                        @endif
                    </table>
            	</div>
            </div>
        </div>
    </div>

    @if($leave_details['category'] == 'Sick Leave')
        @if(isset($leave_doc) && sizeof($leave_doc) > 0)
            <div class="row">    
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                        <div class="box-header with-border col-md-6 ">
                            <h3 class="box-title">Medical Documents</h3>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th></th>
                                    <th>File Name</th>
                                    <th>Size</th>      
                                </tr>
                                @foreach($leave_doc as $key => $value)
                                    <tr>
                                        <td>
                                            <a download href="{{ $value['url'] }}">
                                            <i class="fa fa-fw fa-download"></i></a>
                                        </td>
                                        <td><a target="_blank" href="{{ $value['url'] }}">
                                        {{ $value['name'] }}</a></td>
                                        <td>{{ $value['size'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if($loggedin_user_id == $leave_details['user_id'])

        @if($leave_details['status'] == 1)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-success" disabled="disabled">Approved by {{ $leave_details['approved_by'] }}</button>
            </div>
        @elseif($leave_details['status'] == 2)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-danger" disabled="disabled">Rejected by {{ $leave_details['approved_by'] }}</button>
            </div>
        @endif

        @if(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '1')
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-success" disabled="disabled">Cancelled Approved by {{ $leave_details['leave_cancel_approved_by'] }}</button>
            </div>
        @elseif(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '2')
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-danger" disabled="disabled">Cancelled Rejected by {{ $leave_details['leave_cancel_approved_by'] }}</button>
            </div>
        @endif
    @else
        @if(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '0')
            @if($leave_details['status'] == 0)
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success" onclick="permission('Approved')" title="Approve">Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" onclick="permission('Rejected')" title="Reject">Reject</button>
                </div>
            @elseif($leave_details['status'] == 1)
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="button" class="btn btn-success" disabled="disabled">Approved by {{ $leave_details['approved_by'] }}</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" onclick="permission('Rejected')" title="Reject">Reject</button>
                </div>
            @elseif($leave_details['status'] == 2)
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success" onclick="permission('Approved')" title="Approve">Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger" disabled="disabled">Rejected by {{ $leave_details['approved_by'] }}</button>
                </div>
            @endif
        @endif
        
        @if(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '0')
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-success" onclick="cancelPermission('Approved')" title="Approve">Cancel Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" onclick="cancelPermission('Rejected')" title="Reject">Cancel Reject</button>
            </div>
        @elseif(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '1')
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-success" disabled="disabled">Cancelled Approved by {{ $leave_details['leave_cancel_approved_by'] }}</button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" onclick="cancelPermission('Rejected')" title="Reject">Cancel Reject</button>
            </div>
        @elseif(isset($leave_details['is_leave_cancel']) && $leave_details['is_leave_cancel'] == '1' && $leave_details['leave_cancel_status'] == '2')
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-success" onclick="cancelPermission('Approved')" title="Approve">Cancel Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger" disabled="disabled">Cancelled Rejected by {{ $leave_details['leave_cancel_approved_by'] }}</button>
            </div>
        @endif
    @endif

    <input type="hidden" name="leave_id" id="leave_id" value="{{ $leave_id }}">
    <input type="hidden" name="msg" id="msg" value="{{ $leave_details['message'] }}">
    <input type="hidden" name="user_name" id="user_name" value="{{ $leave_details['uname'] }}">
    <input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{ $loggedin_user_id }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ $leave_details['user_id'] }}">
    <input type="hidden" name="subject" id="subject" value="{{ $leave_details['subject'] }}">
    <input type="hidden" name="approved_by" id="approved_by" value="{{ $leave_details['approved_by'] }}">
    <input type="hidden" name="type_of_leave" id="type_of_leave" value="{{ $leave_details['type_of_leave'] }}">

    <input type="hidden" name="created_at" id="created_at" value="{{ $leave_details['created_at'] }}">
    <input type="hidden" name="from_date" id="from_date" value="{{ $leave_details['from_date'] }}">
    <input type="hidden" name="from_tommorrow_date_1" id="from_tommorrow_date_1" value="{{ $leave_details['from_tommorrow_date_1'] }}">
    <input type="hidden" name="from_tommorrow_date_2" id="from_tommorrow_date_2" value="{{ $leave_details['from_tommorrow_date_2'] }}">

    <input type="hidden" name="check" id="check" value="">

    <!-- Remarsk Modal Popup -->
    <div id="remarksmodal" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add Remarks</h4>
                </div>

                <div class="modal-body">
                    <div class="ac_mngr_cls">
                        <div class="form-group">
                            <strong>Remarks: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('remarks', null, array('id'=>'remarks','placeholder' => 'Remarks','class' => 'form-control','rows' => '5')) !!}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="hideModal();">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('customscripts')
<script type="text/javascript">

    function permission(check) {
        
        var leave_id = $("#leave_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();
        var msg = $("#msg").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var subject = $("#subject").val();
        var approved_by = $("#approved_by").val();
        var type_of_leave = $("#type_of_leave").val();

        var from_date = $("#from_date").val();
        var created_at = $("#created_at").val();
        var from_tommorrow_date_1 = $("#from_tommorrow_date_1").val();
        var from_tommorrow_date_2 = $("#from_tommorrow_date_2").val();

        $("#check").val(check);

        if(from_date == created_at || from_date == from_tommorrow_date_1 || from_date == from_tommorrow_date_2 || check == 'Rejected') {

            $("#remarksmodal").modal('show');
        }
        else {

            var remarks = '';

            $.ajax({
                type: 'POST',
                url:app_url+'/leave/reply/'+leave_id,
                data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by,'remarks':remarks},
                dataType:'json',
                success: function(data) {
                    if (data == 'success') { 
                        window.location.reload();
                        alert('Reply Sent Successfully.');
                    }
                }
            });
        }
    }

    function cancelPermission(check) {
        var leave_id = $("#leave_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();
        var msg = $("#msg").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var subject = $("#subject").val();
        var approved_by = $("#approved_by").val();
        var type_of_leave = $("#type_of_leave").val();

        $("#check").val(check);
        var remarks = '';
        $.ajax({
            type: 'POST',
            url:app_url+'/leave/cancel_reply/'+leave_id,
            data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by,'remarks':remarks},
            dataType:'json',
            success: function(data) {
                if (data == 'success') { 
                    window.location.reload();
                    alert('Reply Sent Successfully.');
                }
            }
        });
    }

    function hideModal() {

        var remarks = $("#remarks").val();

        if(remarks == '') {

            alert("Please Add Remarks");
            return false;
        }

        //alert("Remarks Added Successfully.");

        $("#remarksmodal").modal('hide');
        
        var app_url = "{!! env('APP_URL') !!}";
        var leave_id = $("#leave_id").val();
        var check = $("#check").val();
        var token = $("input[name=_token]").val();
        var msg = $("#msg").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var subject = $("#subject").val();
        var approved_by = $("#approved_by").val();

        $.ajax({

            type: 'POST',
            url:app_url+'/leave/reply/'+leave_id,
            data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by,'remarks':remarks},
            dataType:'json',
            success: function(data){
                if (data == 'success') { 
                    window.location.reload();
                    alert('Reply Sent Successfully.');
                }
            }
        });
    }
</script>
@endsection