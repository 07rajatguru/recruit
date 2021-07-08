@if(isset($post) && sizeof($post) > 0)
    <div id="newsfeed-items-grid" style="margin-left:2%;">
        <div class="comment-page">
            @foreach($post as $p)
                <div class="m-post-row">
                    @include('adminlte::ticketDiscussion.remarksitem', array('per_post' => $p))
                </div>
            @endforeach
        </div>
    </div>
@endif