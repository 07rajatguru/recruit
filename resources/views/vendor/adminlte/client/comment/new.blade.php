{!! Form::open(['route' => ['post.comments.write', $per_post->id], 'name' => 'write_a_review', 'id' => 'write_a_review']) !!}
{!! Form::hidden('post_id', $per_post->id) !!}
{!! Form::hidden('client_id', $client_id) !!}
{!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Write Comment', 'rows' => 3, "required" => true]) !!}
<button class="btn btn-primary btn-md-2">Post Comment</button>
{!! Form::close() !!}