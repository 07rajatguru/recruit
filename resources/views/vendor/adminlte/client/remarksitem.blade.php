<div class="post__author ">
    <!-- <img class="profile-avatar-pic" src="https://lh6.googleusercontent.com/-o-JGTaPiZfM/AAAAAAAAAAI/AAAAAAAAATA/hxzINDVAveQ/photo.jpg" alt="author"> -->
    <div class="author-date">
        <a class="h6 post__author-name fn" href="#">{{ $per_post->user->name }}</a>
        <time class="published" datetime="2018-04-05 10:48:23" title="05-04-2018 10:48 AM">{{$per_post->created_at->diffForHumans()}} </time>
    </div>
    <div class="more">
        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        <ul class="more-dropdown">
            @if(isset(Auth::user()->id) && ($per_post->user->id == \Auth::user()->id) )
                <div class="auth-links">
                    <li>
                        <a href="#" data-toggle="modal" data-target="#update-review-{{$per_post->id }}">Edit Post</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" onclick="deleteReview({{$per_post->id }})">Delete Post</a>
                    </li>
                </div>
            @endif
        </ul>
    </div>
    <p>{{$per_post->content}} </p>

    <!-- Window-popup Update Review -->

    <div class="modal fade" id="update-review-{{$per_post->id }}">
        <div class="modal-dialog ui-block window-popup edit-widget update-review">
            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">X</a>

            <div class="ui-block-title">
                <h6 class="title">Update Review</h6>
            </div>

            <div class="ui-block-content">
                {!! Form::open(['route' => ['state.reviews.update', $client_id,$per_post->id], 'name' => 'update_a_review', 'id' => 'update_a_review', 'files' => 'true']) !!}
                {!! Form::hidden('client_id', $client_id) !!}
                {!! Form::hidden('user_id', auth()->id()) !!}
                {!! Form::textarea('content',  $per_post->content, ['id'=>'update-review-textarea-'.$per_post->id,'class' => 'form-control update-review-textarea', 'placeholder' => '', 'rows' => 1, "required" => true ]) !!}

                <div class="form-group is-empty update-preview-image-container"></div>
                <div class="add-options-message">
                    <div class="input">
                        <img class="camera-img" src="/images/camera-icon.png"/>
                        <input name="file[]" id="files" type="file" multiple="true">
                    </div>
                </div>

                <input type="hidden" id="review_id" name="review_id" value="{{$per_post->id }}">
                {{-- <a href="javascript:void(0);" class="btn btn-primary btn-lg full-width" onclick="updateReview({{$per_post->id }})">Update</a>--}}
                <div class="update-button"><button class="btn btn-primary btn-md-2">Update</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

   <!-- Check readfile  -->
</div>