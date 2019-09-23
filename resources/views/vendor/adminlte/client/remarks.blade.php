@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
<div class="col-lg-12 col-md-12 margin-tb">
    <div class="pull-left">
        <h2>{{ $client['name'] }}</h2>
    </div>

   <div class="pull-right" style="margin-right: 25%;">
        <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
    </div>
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

<!-- /.modal start -->
<div class="modal text-left fade" id="remarksModal">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <h4 class="modal-title">No Remarks Found</h4>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@section('customscripts')
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
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
                if(ui.item.label !== '')
                {
                    $("#content").val(ui.item.label);
                }
                else
                {
                    $("#content").val('');
                    $("#remarksModal").modal('show');
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
            else
            {
                $("#remarksModal").modal('show');
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
                if(ui.item.label !== '')
                {
                    $("#update-review-textarea-"+id).val(ui.item.label);
                }
                else
                {
                    $("#update-review-textarea-"+id).val('');
                    $("#update-review-"+id).modal('hide');
                    $("#remarksModal").modal('show');
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
            else
            {
                $("#update-review-"+id).modal('hide');
                $("#remarksModal").modal('show');
            }
        };
    }

    function initSearchComment()
    {
        $("#comment").autocomplete(
        {
            minLength: 1,
            source: '/search-remarks',
            
            select: function( event, ui ) 
            {
                if(ui.item.label == 'No Remarks Found')
                {
                    $("#comment").val('');
                    return false;
                }
            }
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

