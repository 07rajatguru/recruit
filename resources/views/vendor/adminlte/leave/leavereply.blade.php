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
            <button type="submit" class="btn btn-primary" onclick="permission('Approved')">Approved</button> &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary" onclick="permission('Notapproved')">Not approved</button>
        </div>
    @endif
@endif

@if($leave_details['status'] == 1)
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="button" class="btn btn-primary" disabled="disabled">Approved by {{ $leave_details['approved_by'] }}</button>
    </div>
@elseif($leave_details['status'] == 2)
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="button" class="btn btn-primary" disabled="disabled">Not Approved by {{ $leave_details['approved_by'] }}</button>
    </div>
@endif

<input type="hidden" name="leave_id" id="leave_id" value="{{$leave_id}}">
<input type="hidden" name="msg" id="msg" value="{{$leave_details['message']}}">
<input type="hidden" name="user_name" id="user_name" value="{{$leave_details['uname']}}">
<input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{$loggedin_user_id}}">
<input type="hidden" name="user_id" id="user_id" value="{{$leave_details['user_id']}}">
<input type="hidden" name="subject" id="subject" value="{{$leave_details['subject']}}">
<input type="hidden" name="approved_by" id="approved_by" value="{{$leave_details['approved_by']}}">
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
        //alert(loggedin_user_id);

        $.ajax({
            type: 'POST',
            url:app_url+'/leave/reply/'+leave_id,
            data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject,approved_by:approved_by},
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