@if(isset($post) && sizeof($post) > 0)
    <div id="newsfeed-items-grid">
        <div class="comment-page">
            @foreach($post as $per_post)
                <div class="m-post-row">
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
                            <div class="author-date">
                               <div class="date-time">
                                    <span>{{ $post_date }}</span>
                                </div>
                            </div>   
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="children">
                       @if($per_post->commentCount() > 0)
                            @foreach($per_post->comments->toTree() as $comment)
                                <?php
                                    $comment_time = explode(" ", $comment->updated_at);
                                    $time = App\Date::converttime($comment_time[1]);
                                    $comment_date = date('d-m-Y' ,strtotime($comment->updated_at)) . ' at '. date('h:i A' ,$time);
                                ?>

                                <div class="singal-row-wrapper">
                                    <div class="post__author author-date">
                                        <?php
                                            $data = App\UsersDoc::getUserDocInfoByIDType($comment->creator_id,'Photo'); 
                                        ?>

                                        @if(isset($data['file']) && $data['file'] != '')
                                            <img class="profile-avatar-pic" src="../../{{ $data['file'] }}" alt="author">
                                        @else
                                            <img class="profile-avatar-pic" src="/images/default.png" alt="author">
                                        @endif

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
                                        <div class="author-date">
                                           <div class="date-time">
                                                <span>{{ $comment_date }}</span>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif