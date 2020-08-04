@extends('adminlte::page')

@section('title', 'Client Remarks')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="col-lg-12 col-md-12 margin-tb" style="margin-top: -3%;">
    <div class="pull-left">
        <h3>{{ $client['name'] }} - {{ $client['coordinator_name'] }} - {{ $client_location }}</h3>
    </div>

    {{--<div class="pull-right" style="margin-right: 50%;margin-top: 1%;">
        <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
    </div>--}}
</div>
<div class="col-md-7"> 
    <div>
        @include('adminlte::client.remarksnew',array('client_id' => $client_id,'user_id'=>$user_id,'super_admin_userid' => $super_admin_userid,'client_remarks'=>$client_remarks))       
    </div>

    <div>
        @include('adminlte::client.remarkslist',array('post' => $post,'super_admin_userid' => $super_admin_userid, 'client_remarks'=>$client_remarks, 'client_remarks_edit' => $client_remarks_edit))
    </div>
</div>

<div class="col-md-5">
    <div style="text-align: center;">
        <h3>Client History</h3>
    </div>
    <table id="timeline_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <tr>
            <th style="text-align: center;">Username</th>
            <th style="text-align: center;">From Date</th>
            <th style="text-align: center;">To Date</th>
            <th style="text-align: center;">Days</th>
        </tr>
        @if(isset($days_array) && sizeof($days_array) > 0)
            @foreach($days_array as $key => $value)
                <tr>
                    @if($value['user_id'] == 0)
                        <td style="text-align: center;">Yet to Assign</td>
                    @else
                        <td style="text-align: center;">{{ $value['user_name'] }}</td>
                    @endif

                    <td style="text-align: center;">{{ $value['from_date'] }}</td>

                    @if($value['to_date'] == '-')
                        <td style="text-align: center;">Present</td>
                    @else
                        <td style="text-align: center;">{{ $value['to_date'] }}</td>
                    @endif

                    @if($value['days'] == '-')
                        <?php
                            if($value['to_date'] == $value['from_date']){
                                $diff_in_days = '1';
                            }
                            else{
                                $today_date = date('d-m-Y');
                                $to = strtotime($today_date);
                                $from = strtotime($value['from_date']);
                                $diff_in_days = ($to - $from)/60/60/24;
                            }
                        ?>
                        <td style="text-align: center;">{{ $diff_in_days }}</td>
                    @else
                        <td style="text-align: center;">{{ $value['days'] }}
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </table>
</div>

<div class="modal fade" id="new-comment">
    <div class="modal-dialog ui-block window-popup edit-widget add-comment">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Add Remarks</h2>
        </div>

        <div class="modal-body">
            {!! Form::open(['route' => ['client.post.write', $client_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
            {!! Form::hidden('client_id', $client_id) !!}
            {!! Form::hidden('user_id', auth()->id()) !!}
            {!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
            {!! Form::hidden('manager_user_id', $manager_user_id) !!}
            {!! Form::text('content', null, ['id' => 'new_content','class' => 'form-control','required' => true, 'placeholder' => 'Write Remark']) !!}
        </div>

        <div class="modal-footer">
            <div class="add-button"><button class="btn btn-primary btn-md-2">Add</button></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<input type="hidden" name="super_admin_userid" id="super_admin_userid" value="{{ $super_admin_userid }}">
<input type="hidden" name="hidden_clientid" id="hidden_clientid" value="{{ $client_id }}">

@stop

@section('customscripts')
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function(){

        $("#content").select2({'placeholder': 'Select Remark'});
        $(".update-review-textarea").select2({'placeholder': 'Select Remark','width': '100%'});
    });

    function emptyTextValidation(id) {

        var content = $("#comment_"+id).val();

        if(content == '') {
            alert("Please Select One Remark");
            return false;
        }
        return true;
    }

    function AddnewRemarkspopup() {

        var content = $("#content").val();

        if (content == 'other') {
            $("#new-comment").modal('show');
        }
    }

    function AddnewRemarkCommentpopup(id) {

        var content = $("#comment_"+id).val();

        if (content == 'other') {
            $("#new-remark-comment").modal('show');
        }
    }

    function initSearchRemarks() {

        $("#content").autocomplete({

            minLength: 1,
            source: '/search-remarks',
            
            select: function( event, ui )  {
                if(ui.item.label !== '') {
                    $("#content").val(ui.item.label);
                }
                else {
                    $("#content").val('');
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item ) {

            if(item.label !== '') {
                ul.addClass('srch-remarks');
                return $( "<li>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
        };
    }

    function initSearchComment(id) {

        $("#comment_"+id).autocomplete({

            minLength: 1,
            source: '/search-remarks',
            
            select: function( event, ui ) {
                if(ui.item.label !== '') {
                    $("#comment_"+id).val(ui.item.label);
                }
                else {
                    $("#comment_"+id).val('');
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item ) {
            if(item.label !== '') {
                ul.addClass('srch-remarks');
                return $( "<li>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
        };
    }

    function initUpdateSearchComment(id) {

        $("#update-comment-textarea-"+id).autocomplete({
            minLength: 1,
            source: '/search-remarks',
            appendTo : '#update-comment-'+id+'',
            
            select: function( event, ui ) {
                if(ui.item.label == "No Remarks Found") {
                    $("#update-comment-textarea-"+id).val(ui.item.label);
                }
                if(ui.item.label !== '') {
                   $("#update-comment-textarea-"+id).val(ui.item.label);
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item ) {
            if(item.label !== '') {
                ul.addClass('srch-remarks');
                return $( "<li style='background-color:white;'>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
        };
    }

    function showcommentbox(post_id) {

        $("#comment_"+post_id).select2({'placeholder' : 'Select Remark','width':'100%'});

        if($(".comment-"+post_id).is(':hidden')) {
            $(".comment-"+post_id).show();
        }
        else {
            $(".comment-"+post_id).hide();
        }
    }

    function deletePost(id) {

        msg = "Are you sure ?";
        var confirmvalue = confirm(msg);
        var csrf_token = $("#csrf_token").val();
        var hidden_clientid = $("#hidden_clientid").val();
        
        if(confirmvalue) {

            jQuery.ajax({

                url:'/client/post/delete/'+id,
                type:"POST",
                dataType:'json',
                data : {client_id:hidden_clientid,_token:csrf_token},

                success: function(response) {

                    if (response.returnvalue == 'valid') {
                        alert("Remarks Deleted Succesfully.");
                    }
                    else {
                        alert("Error while Deleting Remarks.");
                    }
                    window.location.reload();
                }
            });
        }
    }

    function updateCommentReply(id) {

        var csrf_token = $("#csrf_token").val();
        var super_admin_userid = $("#super_admin_userid").val();
        var hidden_clientid = $("#hidden_clientid").val();

        if(id > 0) {

            var content = $("#update-comment-textarea-"+id).val();
            jQuery.ajax({

                url:'/client/comment/update',
                type:"POST",
                dataType:'json',
                data : {content:content,id:id,_token:csrf_token,super_admin_userid:super_admin_userid,client_id:hidden_clientid},

                success: function(response) {

                    if (response.returnvalue == 'valid') {
                        alert("Data updated Succesfully.");
                    }
                    else {
                        alert("Error while updating comment");
                    }
                    window.location.reload();
                }
            });
        }
    }

    function deleteComment(id) {

        msg = "Are you sure ?";
        var confirmvalue = confirm(msg);
        var csrf_token = $("#csrf_token").val();
        var hidden_clientid = $("#hidden_clientid").val();

        if(confirmvalue) {

            jQuery.ajax({
                url:'/client/comment/delete/'+id,
                type:"POST",
                dataType:'json',
                data : {client_id:hidden_clientid,_token:csrf_token},

                success: function(response) {

                    if (response.returnvalue == 'valid') {
                        alert("Comment Deleted Succesfully.");
                    }
                    else {
                        alert("Error while Deleting Comment.");
                    }
                    window.location.reload();
                }
            });
        }
    }
</script>
@stop