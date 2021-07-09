<div class="m-post-row comment-box">
    {!! Form::open(['route' => ['ticket.post.write', $tickets_discussion_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
        {!! Form::hidden('tickets_discussion_id', $tickets_discussion_id) !!}
        {!! Form::hidden('user_id', $user_id) !!}

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="comment-area">
                <div class="form-group">
                    {!! Form::text('content', null, ['id' => 'content','class' => 'form-control','required' => true,'placeholder' => 'Add Comment','style' => 'width:635px']) !!}
                </div>
                <div class="form-group">
                    <input type="file" name="upload_documents[]" multiple tabindex="2" />
                </div>
            </div>
        </div>

        <div class="button-area">
            <button class="btn btn-primary btn-md-2">Post Comment</button>
        </div>

    {!! Form::close() !!}
</div>