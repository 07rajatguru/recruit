@extends('adminlte::page')

@section('title', 'Ticket Discussion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    
    <div class="col-lg-12 col-md-12 margin-tb">
        <div class="pull-left" style="margin-left:15px;">
            <h3>{{ $ticket_discussion['question_type'] }}</h3>
        </div>
    </div>

    <div class="col-md-7"> 
        <div>
            @include('adminlte::ticketDiscussion.remarksnew',array('tickets_discussion_id' => $tickets_discussion_id,'user_id'=>$user_id))       
        </div>

        <div>
            @include('adminlte::ticketDiscussion.remarkslist',array('post' => $post))
        </div>
    </div>

    <div class="modal fade" id="new-comment">
        <div class="modal-dialog ui-block window-popup edit-widget add-comment">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Add Remarks</h2>
            </div>

            <div class="modal-body">
                {!! Form::open(['route' => ['ticket.post.write', $tickets_discussion_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
                {!! Form::hidden('tickets_discussion_id', $tickets_discussion_id) !!}
                {!! Form::hidden('user_id', auth()->id()) !!}

                {!! Form::text('content', null, ['id' => 'new_content','class' => 'form-control','required' => true, 'placeholder' => 'Write Remark']) !!}
            </div>

            <div class="modal-footer">
                <div class="add-button"><button class="btn btn-primary btn-md-2">Add</button></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <input type="hidden" name="tickets_discussion_id" id="tickets_discussion_id" value="{{ $tickets_discussion_id }}">

@stop

@section('customscripts')
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

    function deletePost(id) {

        msg = "Are you sure ?";
        var confirmvalue = confirm(msg);
        var csrf_token = $("#csrf_token").val();
        var tickets_discussion_id = $("#tickets_discussion_id").val();
        
        if(confirmvalue) {

            jQuery.ajax({

                url:'/ticket/post/delete/'+id,
                type:"POST",
                dataType:'json',
                data : {tickets_discussion_id:tickets_discussion_id,_token:csrf_token},

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
</script>
@stop