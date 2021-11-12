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
                            <td>{{ $leave_details['uname'] }}</td>
                            <th>From Date</th>
                            <td>{{ $leave_details['from_date'] }}</td>
                            <th>To Date</th>
                            <td>{{ $leave_details['to_date'] }}</td>
                        </tr>

                        <tr>
                            <th>Leave Type</th>
                            <td>{{ $leave_details['type_of_leave'] }}</td>
                            <th>Leave Category</th>
                            <td>{{ $leave_details['category'] }}</td>
                            <th>Status</th>

                            @if($leave_details['status'] == 0)
                                <td style="background-color:#8FB1D5;">Pending</td>
                            @elseif($leave_details['status'] == 1)
                                <td style="background-color:#32CD32;">Approved</td>
                            @elseif($leave_details['status'] == 2)
                                <td style="background-color:#F08080;">Not Approved</td>
                            @endif
                        </tr>
                        
                        <tr>
                            <th>Leave Message</th>
                            <td colspan="6">{!! $leave_details['message'] !!}</td>
                        </tr>

                        <tr>
                            <th>Remarks</th>
                            <td colspan="6">{!! $leave_details['remarks'] !!}</td>
                        </tr>
                    </table>
            	</div>
            </div>
        </div>
    </div>

@if($leave_details['category'] == 'Seek Leave')
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
                        @if(isset($leave_doc) && sizeof($leave_doc) > 0)
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
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if($loggedin_user_id == $leave_details['user_id'])
    
@else
    @if($leave_details['status'] == 0)
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-success" onclick="permission('Approved')" title="Approved">Approved</button> 
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-danger" onclick="permission('Notapproved')" title="Not Approved">Not Approved</button>
        </div>
    @endif
@endif

@if($leave_details['status'] == 1)
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="button" class="btn btn-success" disabled="disabled">Approved by {{ $leave_details['approved_by'] }}</button>
    </div>
@elseif($leave_details['status'] == 2)
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="button" class="btn btn-danger" disabled="disabled">Not Approved by {{ $leave_details['approved_by'] }}</button>
    </div>
@endif

<input type="hidden" name="leave_id" id="leave_id" value="{{ $leave_id }}">
<input type="hidden" name="msg" id="msg" value="{{ $leave_details['message'] }}">
<input type="hidden" name="user_name" id="user_name" value="{{ $leave_details['uname'] }}">
<input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{ $loggedin_user_id }}">
<input type="hidden" name="user_id" id="user_id" value="{{ $leave_details['user_id'] }}">
<input type="hidden" name="subject" id="subject" value="{{ $leave_details['subject'] }}">
<input type="hidden" name="approved_by" id="approved_by" value="{{ $leave_details['approved_by'] }}">

<input type="hidden" name="created_at" id="created_at" value="{{ $leave_details['created_at'] }}">

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
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                </button>
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
        var created_at = $("#created_at").val();

        $("#check").val(check);

        var today = new Date();

        var dd = today.getDate();
        var mm = today.getMonth()+1; 
        var yyyy = today.getFullYear();

        if(dd < 10) {
            dd='0'+dd;
        } 
        if(mm < 10) {
            mm='0'+mm;
        }

        today = dd+'-'+mm+'-'+yyyy;

        var dd_tomorrow = dd + 1;
        var tomorrow = dd_tomorrow+'-'+mm+'-'+yyyy;

        var dd_tomorrow_1 = dd_tomorrow + 1;
        var tomorrow_1 = dd_tomorrow_1+'-'+mm+'-'+yyyy;

        if(created_at == today || created_at == tomorrow || created_at == tomorrow_1) {

            $("#remarksmodal").modal('show');
        }
        else {

            var remarks = '';

            $.ajax({
                type: 'POST',
                url:app_url+'/leave/reply/'+leave_id,
                data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by,'remarks':remarks},
                dataType:'json',
                success: function(data){
                    if (data == 'success') { 
                        window.location.reload();
                        alert('Reply Send Successfully.');
                    }
                }
            });
        }
    }

    function hideModal() {

        alert("Remarks Added Successfully.");

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
        var remarks = $("#remarks").val();

        $.ajax({

                type: 'POST',
                url:app_url+'/leave/reply/'+leave_id,
                data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by,'remarks':remarks},
                dataType:'json',
                success: function(data){
                    if (data == 'success') { 
                        window.location.reload();
                        alert('Reply Send Successfully.');
                    }
                }
        });
    }
</script>
@endsection