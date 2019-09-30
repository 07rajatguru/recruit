{!! Form::open(['route' => ['post.comments.write', $per_post->id], 'name' => 'write_a_review', 'id' => 'write_a_review']) !!}
{!! Form::hidden('post_id', $per_post->id) !!}
{!! Form::hidden('client_id', $client_id) !!}
{!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
{!! Form::textarea('content', null, ['id' => 'comment_'.$per_post->id,'class' => 'form-control', 'placeholder' => 'Write Comment', 'rows' => '1', "required" => true,'onclick' => 'initSearchComment('.$per_post->id.');']) !!}
<button class="btn btn-primary btn-md-2">Post Comment</button>
{!! Form::close() !!}