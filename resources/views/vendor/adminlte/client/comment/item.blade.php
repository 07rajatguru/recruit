
<?php
                $comment_time = explode(" ", $comment->updated_at);
                $time = App\Date::converttime($comment_time[1]);
                $comment_date = date('d-m-Y' ,strtotime($comment->updated_at)) . ' at '. date('h:i A' ,$time);
            ?>

<div class="singal-row-wrapper">
    <div class="post__author author-date">
        <img class="profile-avatar-pic" src="/images/default.png" alt="author"> 
        <div class="comment-detail">
            <div class="comment-desc">
                <p>{{ $comment->body }}</p>
            </div>
            <div class="user-name">
                <a class="h6 post__author-name fn" href="#">{{ $comment->creator()->name }}</a>
            </div>
        </div>
     </div>
    <div class="right-detail"> 
        <div class="user-option">
            <ul>
                <div class="auth-links">
                    {{--<li><a href="#" title="Add Comment" data-toggle="modal" onclick="showcommentbox({{$comment->id }})"><i class="fa fa-plus" aria-hidden="true"></i></a></li>--}}
                    @if((isset(Auth::user()->id) && $comment->creator()->id == \Auth::user()->id ) || $isSuperAdmin)
                        <li>
                            <a href="#" title="Edit Post" data-toggle="modal" data-target="#update-comment-{{$comment->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        </li>
                    @endif
                    @if($isSuperAdmin)
                        <li>
                            <a href="javascript:void(0);" title="Dlete Post" onclick="deleteComment({{$comment->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </li>
                    @endif
                </div>
            </ul>
        </div>
        <div class="author-date">
           <div class="date-time">
                <span>{{ $comment_date }}</span>
            </div>
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
                {!! Form::select('content',$client_remarks_edit, $comment->body, ['id'=>'update-comment-textarea-'.$comment->id, 'class' => 'form-control update-review-textarea', "required" => true]) !!}
                <input type="hidden" id="review_id" name="review_id" value="{{$comment->id }}"><br/>
                <div class="update-button" style="margin: 1% 0 0 0;"><a href="javascript:void(0);" class="btn btn-primary btn-lg full-width" onclick="updateCommentReply({{$comment->id }})">Update</a></div>
            </div>
        </div>
    </div>
    <!-- ... end Window-popup Update Review -->
</div>