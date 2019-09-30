<div class="m-post-row comment-box">
    {!! Form::open(['route' => ['client.post.write', $client_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
        {!! Form::hidden('client_id', $client_id) !!}
        {!! Form::hidden('user_id', $user_id) !!}
        {!! Form::hidden('super_admin_userid',$super_admin_userid) !!}
        <!-- <div class="thumb">
            <img src="https://lh6.googleusercontent.com/-o-JGTaPiZfM/AAAAAAAAAAI/AAAAAAAAATA/hxzINDVAveQ/photo.jpg" alt="Avatar" class="avatar">
        </div>  -->
        <div class="comment-area">
           {!! Form::textarea('content', null, ['id' => 'content','class' => 'form-control' ,'placeholder' => 'Write your remarks', 'rows' => 2, 'cols' => 70,"required" => true,'onclick' => 'initSearchRemarks();']) !!}
        </div>

         <div class="button-area" style="padding: 4px;">
            <button class="btn btn-primary btn-md-2">Post Remarks</button>
        </div>

    {!! Form::close() !!}
</div>