<div class="post__author author vcard inline-items">
    <img class="profile-avatar-pic" src="https://lh6.googleusercontent.com/-o-JGTaPiZfM/AAAAAAAAAAI/AAAAAAAAATA/hxzINDVAveQ/photo.jpg" alt="author">
    <div class="author-date">
        <a class="h6 post__author-name fn" href="javascript:void(0);">{{ $comment->creator()->name }}</a>
        <div class="post__date">
            <time class="published" datetime="2018-04-05 10:48:23" title="05-04-2018 10:48 AM">
                {{$comment->created_at->diffForHumans()}} </time>
        </div>
    </div>
    <div class="more">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        <ul class="more-dropdown">
            @if(isset(Auth::user()->id) && $comment->creator()->id == \Auth::user()->id)
                <div class="auth-links">
                    <li><a href="#" data-toggle="modal" data-target="#update-comment-{{$comment->id }}">Edit Comment</a></li>
                    <li> <a href="javascript:void(0);" onclick="deleteComment({{$comment->id }})">Delete Comment</a></li>
                </div>
            @else
                <div class="auth-links">
                    <li><a href="javascript:void(0);" onclick="$('#login-dialog').modal('show');">Login</a></li>
                </div>
            @endif
        </ul>
    </div>
</div>

<!-- Window-popup Update Review -->

<div class="modal fade" id="update-comment-{{$comment->id }}">
    <div class="modal-dialog ui-block window-popup edit-widget update-review">
        <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">X</a>

        <div class="ui-block-title">
            <h6 class="title">Update Comment</h6>
        </div>

        <div class="ui-block-content">
            {!! Form::textarea('content',  $comment->body, ['id'=>'update-comment-textarea-'.$comment->id,'class' => 'form-control update-review-textarea', 'placeholder' => '', 'rows' => 1, "required" => true ]) !!}
            <input type="hidden" id="review_id" name="review_id" value="{{$comment->id }}">
            <div class="update-button"><a href="javascript:void(0);" class="btn btn-primary btn-lg full-width" onclick="updateCommentReply({{$comment->id }})">Update</a></div>
        </div>
    </div>
</div>

<!-- ... end Window-popup Update Review -->

<p>
    {{ $comment->body }}
</p>