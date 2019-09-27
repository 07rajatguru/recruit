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
<div class="col-md-9"> 
    <div>
     	@include('adminlte::client.remarksnew',array('client_id' => $client_id,'user_id'=>$user_id))    	 
    </div>

    <div>
    	@include('adminlte::client.remarkslist',array('post' => $post))
    </div>
</div>
@stop

@section('customscripts')
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function(){

        initSearchRemarks();
        initSearchComment();
    });

    function initSearchRemarks()
    {
        $("#content").autocomplete(
        {
            minLength: 1,
            source: '/search-remarks',
            
            select: function( event, ui ) 
            {
                if(ui.item.label == "No Remarks Found")
                {
                    $("#content").val(ui.item.label);
                }
                else if(ui.item.label !== '')
                {
                    $("#content").val(ui.item.label);
                }
                else
                {
                    $("#content").val('');
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item )
        {
            if(item.label !== '')
            {
                ul.addClass('srch-remarks');
                return $( "<li>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
            else{
                $("#content").val('');
            }
        };
    }

    function initUpdateSearchRemarks(id)
    {
       $("#update-review-textarea-"+id).autocomplete(
        {
            minLength: 1,
            source: '/search-remarks',
            appendTo : '#update-review-'+id+'',
            
            select: function( event, ui ) 
            {
                if(ui.item.label == "No Remarks Found")
                {
                    $("#update-review-textarea-"+id).val(ui.item.label);
                }
                if(ui.item.label !== '')
                {
                    $("#update-review-textarea-"+id).val(ui.item.label);
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item )
        {
            if(item.label !== '')
            {
                ul.addClass('srch-remarks');
                return $( "<li>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
        };
    }

    function initSearchComment(id)
    {
        $("#comment_"+id).autocomplete(
        {
            minLength: 1,
            source: '/search-remarks',
            
            select: function( event, ui ) 
            {
                if(ui.item.label == "No Remarks Found")
                {
                    $("#comment_"+id).val(ui.item.label);
                }
                else if(ui.item.label !== '')
                {
                    $("#comment_"+id).val(ui.item.label);
                }
                else
                {
                    $("#comment_"+id).val('');
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item )
        {
            if(item.label !== '')
            {
                ul.addClass('srch-remarks');
                return $( "<li>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            } 
            else{
                $("#comment_"+id).val('');
            }
        };
    }

    function initUpdateSearchComment(id)
    {
        $("#update-comment-textarea-"+id).autocomplete(
        {
            minLength: 1,
            source: '/search-remarks',
            appendTo : '#update-comment-'+id+'',
            
            select: function( event, ui ) 
            {
                if(ui.item.label == "No Remarks Found")
                {
                    $("#update-comment-textarea-"+id).val(ui.item.label);
                }
                if(ui.item.label !== '')
                {
                   $("#update-comment-textarea-"+id).val(ui.item.label);
                }
                return false;
            },
        }).autocomplete( "instance" )._renderItem = function( ul,item )
        {
            if(item.label !== '')
            {
                ul.addClass('srch-remarks');
                return $( "<li style='background-color:white;'>" )
                .append( "<div><span>" + item.label + "</span></div>")
                .appendTo( ul )
            }
        };
    }

	function showcommentbox(post_id)
    {
        if($(".comment-"+post_id).is(':hidden')){
            $(".comment-"+post_id).show();
        }
        else{
            $(".comment-"+post_id).hide();
        }
    }

	function deletePost(id)
    {
        msg = "Are you sure ?";
        var confirmvalue = confirm(msg);

        if(confirmvalue){
            jQuery.ajax(
            {
                url:'/client/post/delete/'+id,
                dataType:'json',
                success: function(response){
                    if (response.returnvalue == 'valid') {
                        alert("Remarks Deleted Succesfully.");
                    }
                    else{
                        alert("Error while Deleting Remarks.");
                    }
                    window.location.reload();
                }
            });
        }
    }

    function updateCommentReply(id)
    {
        var csrf_token = $("#csrf_token").val();
        if(id>0){
            var content = $("#update-comment-textarea-"+id).val();
            jQuery.ajax(
            {
                url:'/client/comment/update',
                type:"POST",
                dataType:'json',
                data : "content="+content+"&id="+id+"&_token="+csrf_token,
                success: function(response){
                    if (response.returnvalue == 'valid') {
                        alert("Data updated Succesfully.");
                    }
                    else{
                        alert("Error while updating comment");
                    }
                    window.location.reload();
                }
            });
        }
    }

    function deleteComment(id)
    {
        msg = "Are you sure ?";
        var confirmvalue = confirm(msg);

        if(confirmvalue){
            jQuery.ajax(
            {
                url:'/client/comment/delete/'+id,
                dataType:'json',
                success: function(response){
                    if (response.returnvalue == 'valid') {
                        alert("Comment deleted Succesfully.");
                    }
                    else{
                        alert("Error while deleting comment");
                    }
                    window.location.reload();
                }
            });
        }
    }
</script>
@stop

