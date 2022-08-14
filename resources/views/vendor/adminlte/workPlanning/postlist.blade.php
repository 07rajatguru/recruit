@if(isset($post) && sizeof($post) > 0)
    <div id="newsfeed-items-grid">
        <div class="comment-page">
            @foreach($post as $p)
                <div class="m-post-row">
                    @include('adminlte::workPlanning.postitem', array( 'per_post' => $p,'edit_date_valid' => $edit_date_valid, 'superadmin_userid' => $superadmin_userid))
                </div>
            @endforeach
        </div>
    </div>
@endif