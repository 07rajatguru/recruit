<div class="modal fade" id="new-remark-comment">
    <div class="modal-dialog ui-block window-popup edit-widget add-comment">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Add Remarks</h2>
        </div>

        <div class="modal-body">
            {!! Form::open(['route' => ['post.comments.write', $per_post->id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
            {!! Form::hidden('client_id', $client_id) !!}
            {!! Form::hidden('user_id', auth()->id()) !!}
            {!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
            {!! Form::text('content', null, ['id' => 'new_content','class' => 'form-control','required' => true, 'placeholder' => 'Write Remark']) !!}
        </div>

        <div class="modal-footer">
            <div class="add-button"><button class="btn btn-primary btn-md-2">Add</button></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>


{!! Form::open(['route' => ['post.comments.write', $per_post->id], 'name' => 'write_a_review', 'id' => 'write_a_review']) !!}
{!! Form::hidden('post_id', $per_post->id) !!}
{!! Form::hidden('client_id', $client_id) !!}
{!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
{!! Form::select('content', $client_remarks, null, ['id' => 'comment_'.$per_post->id,'class' => 'form-control', 'placeholder' => 'Select Comment', 'required' => true, 'onchange' => 'AddnewRemarkCommentpopup('.$per_post->id.')']) !!}
<button class="btn btn-primary btn-md-2" style="margin: 1% 0 0 0;">Post Comment</button>
{!! Form::close() !!}