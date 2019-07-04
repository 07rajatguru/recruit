@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
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

    </script>
@stop

