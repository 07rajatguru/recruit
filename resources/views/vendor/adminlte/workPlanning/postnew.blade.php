<div class="m-post-row comment-box">
    {!! Form::open(['route' => ['workplanning.post.write', $wp_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
        {!! Form::hidden('wp_id', $wp_id) !!}
        {!! Form::hidden('user_id', $user_id) !!}

        <div class="comment-area">
           {!!Form::textarea('content',null, array('placeholder' =>'Add Remarks','id' => 'content','class' => 'form-control','rows' => 10,'required' => true,'style' => 'width:505px;border:1px solid black;')) !!}
        </div>

        <div class="button-area">
            <button class="btn btn-primary btn-md-2">Add Remarks</button>
        </div>

    {!! Form::close() !!}
</div>