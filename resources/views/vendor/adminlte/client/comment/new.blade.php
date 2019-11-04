{!! Form::open(['route' => ['post.comments.write', $per_post->id], 'name' => 'write_a_review', 'id' => 'write_a_review']) !!}
{!! Form::hidden('post_id', $per_post->id) !!}
{!! Form::hidden('client_id', $client_id) !!}
{!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
{!! Form::select('content', $client_remarks, null, ['id' => 'comment_'.$per_post->id,'class' => 'form-control', 'placeholder' => 'Select Comment', 'required' => true, 'onchange' => 'AddnewRemarkCommentpopup('.$per_post->id.')']) !!}
<button class="btn btn-primary btn-md-2" style="margin: 1% 0 0 0;">Post Comment</button>
{!! Form::close() !!}