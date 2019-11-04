@if(isset($post) && sizeof($post) > 0)
    <div id="newsfeed-items-grid">
        <div class="comment-page">
            @foreach($post as $p)
                <div class="m-post-row">
                    <?php // print_r($p->created_at->diffForHumans());exit; ?>
                    @include('adminlte::client.remarksitem', array( 'per_post' => $p,'super_admin_userid' => $super_admin_userid, 'client_remarks'=>$client_remarks))

                </div>
            @endforeach
        </div>
    </div>

 <!-- Load more -->
   <!--  <a id="load-more-button" href="#" class="btn btn-control btn-more"
       data-load-link="items-to-load.html" data-container="newsfeed-items-grid">
        <svg class="olymp-three-dots-icon">
            <use xlink:href="/theme/olympus/icons/icons.svg#olymp-three-dots-icon"></use>
        </svg>
    </a> -->
@endif