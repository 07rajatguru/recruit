@extends('adminlte::page')

@section('title', 'Leave Reply')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Leave Application</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            	<br/>
            	<div class="col-xs-12 col-sm-12 col-md-12">
            		<b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Hello, </p></b>
            		<p><b> Subject : </b> &nbsp;&nbsp;{!! $leave_details['subject'] !!}</p>
            		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! $leave_details['message'] !!}</p>
            		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanks & Regards,</p>
            		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $leave_details['uname'] }}</p>
            	</div>
            </div>
        </div>
    </div>

@if($leave_details['category'] == 'Medical')
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
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
                                        <a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>
                                    </td>
                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
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

<div class="col-xs-12 col-sm-12 col-md-12 text-center">
    <button type="submit" class="btn btn-primary" onclick="permission('Approved')">Approved</button> &nbsp;&nbsp;&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary" onclick="permission('Unapproved')">Unapproved</button>
</div>

<input type="hidden" name="leave_id" id="leave_id" value="{{$leave_id}}">
<input type="hidden" name="msg" id="msg" value="{{$leave_details['message']}}">
<input type="hidden" name="user_name" id="user_name" value="{{$leave_details['uname']}}">
<input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{$loggedin_user_id}}">
<input type="hidden" name="user_id" id="user_id" value="{{$leave_details['user_id']}}">
<input type="hidden" name="subject" id="subject" value="{{$leave_details['subject']}}">
@stop

@section('customscripts')
<script type="text/javascript">
    function permission(check){
        var leave_id = $("#leave_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();
        var msg = $("#msg").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var subject = $("#subject").val();
        //alert(loggedin_user_id);
        $.ajax({
            type: 'POST',
            url:app_url+'/leave/reply/'+leave_id,
            data: {leave_id: leave_id, 'check':check, '_token':token, msg:msg, user_name:user_name, loggedin_user_id:loggedin_user_id,user_id:user_id,subject:subject},
            dataType:'json',
            success: function(data){
                if (data == 'success') { 
                    window.location.reload();
                }
            }
        });
    }
</script>
@endsection