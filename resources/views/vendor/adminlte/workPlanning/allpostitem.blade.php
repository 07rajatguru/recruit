<div class="singal-row-wrapper">
    <div class="post__author author-date">
        <?php
            $data = App\UsersDoc::getUserDocInfoByIDType($per_post->user_id,'Photo');
        ?>

        @if(isset($data['file']) && $data['file'] != '')
            <img class="profile-avatar-pic" src="{{$app_url}}/{{ $data['file'] }}" alt="Adler Talent Solutions Pvt. Ltd." style="vertical-align: top;width: 40px;height: 40px;border-radius: 100%;overflow: hidden;margin: 5px 10px 0 0;">
        @else
            <img class="profile-avatar-pic" src="{{$app_url}}/images/default.png" alt="Adler Talent Solutions Pvt. Ltd." style="vertical-align: top;width: 40px;height: 40px;border-radius: 100%;overflow: hidden;margin: 5px 10px 0 0;">
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
        </div>
    </div>
    <div class="right-detail"> 
        <div class="author-date">
           <div class="date-time">
                <span>{{ $per_post->user->name }} --- {{ $post_date }}</span>
            </div>
        </div>   
    </div>
</div>