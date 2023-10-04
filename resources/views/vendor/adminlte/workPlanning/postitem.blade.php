<div class="singal-row-wrapper">
    <div class="post__author author-date">
        <?php
            $data = App\UsersDoc::getUserDocInfoByIDType($per_post->user_id,'Photo');
        ?>

        @if(isset($data['file']) && $data['file'] != '')
            <img class="profile-avatar-pic" src="../../{{ $data['file'] }}" alt="author">
        @else
            <img class="profile-avatar-pic" src="/images/default.png" alt="author">
        @endif

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

                    @if(\Auth::user()->id == $superadmin_userid)
                        @if((isset(Auth::user()->id) && $per_post->user->id == \Auth::user()->id))
                            <li>
                                <a href="#" title="Edit Comment" data-toggle="modal" data-target="#update-review-{{$per_post->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            </li>
                        @endpermission

                        @permission(('display-work-planning'))
                            <li>
                                <a href="javascript:void(0);" title="Delete Comment" onclick="deletePost({{$per_post->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </li>
                        @endpermission
                    @else
                        @if(date('Y-m-d') <= $edit_date_valid)
                            @if((isset(Auth::user()->id) && $per_post->user->id == \Auth::user()->id))
                                <li>
                                    <a href="#" title="Edit Comment" data-toggle="modal" data-target="#update-review-{{$per_post->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </li>
                            @endpermission

                            @permission(('display-work-planning'))
                                <li>
                                    <a href="javascript:void(0);" title="Delete Comment" onclick="deletePost({{$per_post->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </li>
                            @endpermission
                        @endif
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

<div class="modal fade" id="update-review-{{$per_post->id }}">
    <div class="modal-dialog ui-block window-popup edit-widget update-review">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="ui-block-title"><h4>Update Comment</h4></div>

        <div class="ui-block-content">
            {!! Form::open(['route' => ['workplanning.post.update', $wp_id,$per_post->id], 'name' => 'update_a_review', 'id' => 'update_a_review', 'files' => 'true']) !!}
            {!! Form::hidden('wp_id', \Crypt::encrypt($wp_id)) !!}
            {!! Form::hidden('user_id', auth()->id()) !!}

            {!!Form::textarea('content',$per_post->content, array('placeholder' =>'Add Comment','id' => 'update-review-textarea-'.$per_post->id,'class' => 'form-control update-review-textarea','rows' => 5, 'required' => true)) !!}

            <div class="form-group is-empty update-preview-image-container"></div>
            
            <input type="hidden" id="review_id" name="review_id" value="{{$per_post->id }}">

            <div class="update-button">
                <button class="btn btn-primary btn-md-2">Update</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>