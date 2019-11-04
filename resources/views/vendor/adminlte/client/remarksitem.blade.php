<div class="singal-row-wrapper">
    <div class="post__author author-date">
        <img class="profile-avatar-pic" src="/images/default.png" alt="author"> 

        <?php
            $post_time = explode(" ", $per_post->updated_at);
            $time = App\Date::converttime($post_time[1]);
            $post_date = date('d-m-Y' ,strtotime($per_post->updated_at)) . ' at '. date('h:i A' ,$time);
        ?>

        <div class="comment-detail">
            <div class="comment-desc">
                <p>{{$per_post->content}}</p>
            </div>
            <div class="user-name">
                <a class="h6 post__author-name fn" href="#">{{ $per_post->user->name }}</a>
            </div>
        </div>
    </div>
    <div class="right-detail"> 
        <div class="user-option">
            <ul>
                <div class="auth-links">
                    <li><a href="#" title="Add Comment" data-toggle="modal" onclick="showcommentbox({{$per_post->id }})"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                    @if((isset(Auth::user()->id) && $per_post->user->id == \Auth::user()->id) || $isSuperAdmin )
                        <li>
                            <a href="#" title="Edit Post" data-toggle="modal" data-target="#update-review-{{$per_post->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        </li>
                    @endif
                    @if($isSuperAdmin)
                        <li>
                            <a href="javascript:void(0);" title="Dlete Post" onclick="deletePost({{$per_post->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </li>
                    @endif
                </div>
            </ul>
        </div>
        <div class="author-date">
           <div class="date-time">
                <span>{{ $post_date }}</span>
            </div>
        </div>   
    </div>
</div>
<div class="clearfix"></div>
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
                {!! Form::hidden('super_admin_userid', $super_admin_userid) !!}
                {!! Form::select('content', $client_remarks_edit, $per_post->content, ['id'=>'update-review-textarea-'.$per_post->id, 'class' => 'form-control update-review-textarea', 'placeholder' => 'Select Remark', 'required' => true]) !!}

                <div class="form-group is-empty update-preview-image-container"></div>
            

                <input type="hidden" id="review_id" name="review_id" value="{{$per_post->id }}">
                {{-- <a href="javascript:void(0);" class="btn btn-primary btn-lg full-width" onclick="updateReview({{$per_post->id }})">Update</a>--}}
                <div class="update-button"><button class="btn btn-primary btn-md-2">Update</button></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="reply comment-{{$per_post->id}}" style="display: none;">
        @include('adminlte::client.comment.new', array('per_post' => $per_post,'client_id'=>$client_id,'super_admin_userid' => $super_admin_userid, 'client_remarks'=>$client_remarks))
    </div>

    <div class="children">
        @include('adminlte::client.comment.list', array('per_post' => $per_post,'super_admin_userid' => $super_admin_userid, 'client_remarks_edit' => $client_remarks_edit))
    </div>