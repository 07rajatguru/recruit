<div class="m-post-row">
    {!! Form::open(['route' => ['client.post.write', $client_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
        {!! Form::hidden('client_id', $client_id) !!}
        {!! Form::hidden('user_id', $user_id) !!}
        <!-- <div class="thumb">
            <img src="img_avatar.png" alt="Avatar" class="avatar">
        </div> -->
        <div class="comment-area">
           {!! Form::textarea('content', null, ['class' => 'form-control' ,'placeholder' => 'Share what you are thinking here...', 'rows' => 3, "required" => true]) !!}
        </div>

         <div class="button-area">
            <button class="btn btn-primary btn-md-2">Post Comment</button>
        </div>

    {!! Form::close() !!}
</div>
