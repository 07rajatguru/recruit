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

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

        });

		function showcommentbox(post_id) {
            if($(".comment-"+post_id).is(':hidden')){
                $(".comment-"+post_id).show();
            }
            else{
                $(".comment-"+post_id).hide();
            }
        }

		function deletePost(id) {
            msg = "Are you sure ?";
            var confirmvalue = confirm(msg);

            if(confirmvalue){
                jQuery.ajax({
                    url:'/client/post/delete/'+id,
                    dataType:'json',
                    success: function(response){
                        if (response.returnvalue == 'valid') {
                            alert("Remarks deleted succesfully");
                        }
                        else{
                            alert("Error while deleting reviews");
                        }
                        window.location.reload();
                    }
                });
            }
        }

        function updateCommentReply(id) {
            var csrf_token = $("#csrf_token").val();
            if(id>0){
                var content = $("#update-comment-textarea-"+id).val();
                jQuery.ajax({
                    url:'/client/comment/update',
                    type:"POST",
                    dataType:'json',
                    data : "content="+content+"&id="+id+"&_token="+csrf_token,
                    success: function(response){
                        if (response.returnvalue == 'valid') {
                            alert("Data updated succesfully");
                        }
                        else{
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

            if(confirmvalue){
                jQuery.ajax({
                    url:'/client/comment/delete/'+id,
                    dataType:'json',
                    success: function(response){
                        if (response.returnvalue == 'valid') {
                            alert("Comment deleted succesfully");
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

