@if(isset($post) && sizeof($post) > 0)
    <div id="newsfeed-items-grid" style="margin-left:2%;">
        <div class="comment-page">
            @foreach($post as $p)
                <div class="m-post-row">
                    <?php
                        $post_doc = App\TicketsDiscussionPostDoc::getTicketPostDocsById($p->id);
                    ?>
                    @include('adminlte::ticketDiscussion.remarksitem', array('per_post' => $p,'post_doc' => $post_doc))
                </div>
            @endforeach
        </div>
    </div>
@endif