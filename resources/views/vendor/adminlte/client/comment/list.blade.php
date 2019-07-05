@if($per_post->commentCount() > 0)
    @foreach($per_post->comments->toTree() as $comment)
        @include("adminlte::client.comment.item", array("per_post" => $per_post, "comment" => $comment))
    @endforeach

@endif