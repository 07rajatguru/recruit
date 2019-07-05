<div class="post__author " style="padding: 0 0 0 20px;">
    <img class="profile-avatar-pic" src="https://lh6.googleusercontent.com/-o-JGTaPiZfM/AAAAAAAAAAI/AAAAAAAAATA/hxzINDVAveQ/photo.jpg" alt="author"> 
    <div class="author-date">
        <a class="h6 post__author-name fn" href="#">{{ $per_post->user->name }}</a>
        <time class="published" datetime="2018-04-05 10:48:23" title="05-04-2018 10:48 AM">{{$per_post->created_at->diffForHumans()}} </time>
    </div>
    <div class="more">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        <ul class="more-dropdown">
            <div class="auth-links">
            <li><a href="#" data-toggle="modal" onclick="showcommentbox({{$per_post->id }})">Add Comment</a></li>
            @if((isset(Auth::user()->id) && $per_post->user->id == \Auth::user()->id) || $isSuperAdmin )
                <li>
                    <a href="#" data-toggle="modal" data-target="#update-review-{{$per_post->id }}">Edit Post</a>
                </li>
            @endif
             @if($isSuperAdmin)
                <li>
                    <a href="javascript:void(0);" onclick="deletePost({{$per_post->id }})">Delete Post</a>
                </li>
             @endif
            </div>
        </ul>
    </div>
    <p>{{$per_post->content}} </p>

    {{--<div class="post-additional-info inline-items">
        <div class="comments-shared">
            <a href="javascript:void(0);" title="Write a comment" data-form-id="write_a_review_11_comment" onclick="showcommentbox({{$per_post->id}})" class="reply post-add-icon inline-items">
                    <i class="fa fa-commenting-o" aria-hidden="true" ></i>
                </a>
        </div>
    </div>--}}

    <!-- Window-popup Update Review -->

    <div class="modal fade" id="update-review-{{$per_post->id }}">
        <div class="modal-dialog ui-block window-popup edit-widget update-review">
            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">X</a>

            <div class="ui-block-title">
                <h6 class="title">Update Remarks</h6>
            </div>

            <div class="ui-block-content">
                {!! Form::open(['route' => ['client.post.update', $client_id,$per_post->id], 'name' => 'update_a_review', 'id' => 'update_a_review', 'files' => 'true']) !!}
                {!! Form::hidden('client_id', $client_id) !!}
                {!! Form::hidden('user_id', auth()->id()) !!}
                {!! Form::textarea('content',  $per_post->content, ['id'=>'update-review-textarea-'.$per_post->id,'class' => 'form-control update-review-textarea', 'placeholder' => '', 'rows' => 1, "required" => true ]) !!}

                <div class="form-group is-empty update-preview-image-container"></div>
            

                <input type="hidden" id="review_id" name="review_id" value="{{$per_post->id }}">
                {{-- <a href="javascript:void(0);" class="btn btn-primary btn-lg full-width" onclick="updateReview({{$per_post->id }})">Update</a>--}}
                <div class="update-button"><button class="btn btn-primary btn-md-2">Update</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="reply comment-{{$per_post->id}}" style="display: none;">
        @include('adminlte::client.comment.new', array('per_post' => $per_post,'client_id'=>$client_id))
    </div>

    <ul class="children">
        @include('adminlte::client.comment.list', array('per_post' => $per_post))
    </ul>

</div>