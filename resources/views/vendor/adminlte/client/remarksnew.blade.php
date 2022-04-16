<div class="m-post-row comment-box">
    {!! Form::open(['route' => ['client.post.write', $client_id], 'name' => 'write_a_post', 'id' => 'write_a_post', 'files' => 'true']) !!}
        {!! Form::hidden('client_id', $client_id) !!}
        {!! Form::hidden('user_id', $user_id) !!}
        {!! Form::hidden('super_admin_userid',$super_admin_userid) !!}
        {!! Form::hidden('manager_user_id', $manager_user_id) !!}
        <!-- <div class="thumb">
            <img src="https://lh6.googleusercontent.com/-o-JGTaPiZfM/AAAAAAAAAAI/AAAAAAAAATA/hxzINDVAveQ/photo.jpg" alt="Avatar" class="avatar">
        </div>  -->
        <div class="comment-area">
           {{-- {!! Form::select('content', $client_remarks, null, ['id' => 'content','class' => 'form-control','required' => true, 'onchange' => 'AddnewRemarkspopup()']) !!} --}}

           {!! Form::text('content', null, ['id' => 'content','class' => 'form-control','required' => true,'style' => 'width:510px;','placeholder' => 'Add Remarks']) !!}
        </div>

        <div class="button-area">
            <button class="btn btn-primary btn-md-2">Post Remarks</button>
        </div>

    {!! Form::close() !!}
</div>