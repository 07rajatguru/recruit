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

                    @permission(('display-ticket'))
                        <li>
                            <a href="#" title="Edit Post" data-toggle="modal" data-target="#update-review-{{$per_post->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        </li>

                        <li>
                            <a href="javascript:void(0);" title="Dlete Post" onclick="deletePost({{$per_post->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </li>
                    @else
                        @if((isset(Auth::user()->id) && $per_post->user->id == \Auth::user()->id))
                            <li>
                                <a href="#" title="Edit Post" data-toggle="modal" data-target="#update-review-{{$per_post->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            </li>

                            <li>
                                <a href="javascript:void(0);" title="Dlete Post" onclick="deletePost({{$per_post->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </li>
                        @endif
                    @endpermission
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

@if(isset($post_doc) && sizeof($post_doc)>0)
    <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
        <div class="box-header  col-md-6 ">
            <h3 class="box-title">Attachments</h3>
        </div>

        <div class="box-header col-md-8"></div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>Download</th>
                    <th>File Name</th>
                </tr>
                                    
                @foreach($post_doc as $key => $value)
                    <tr>
                        <td>
                            <a download href="{{ $value['url'] }}" ><i class="fa fa-fw fa-download"></i></a>&nbsp;
                        </td>
                        <td>
                            <a target="_blank" href="{{ $value['url'] }}"> {{ $value['name'] }}</a>
                        </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif                 

<div class="clearfix"></div>

<div class="modal fade" id="update-review-{{$per_post->id }}">
    <div class="modal-dialog ui-block window-popup edit-widget update-review">
        <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">X</a>
        <div class="ui-block-title"><h6 class="title">Update Remarks</h6></div>

        <div class="ui-block-content">
            {!! Form::open(['route' => ['ticket.post.update', $tickets_discussion_id,$per_post->id], 'name' => 'update_a_review', 'id' => 'update_a_review', 'files' => 'true']) !!}

            {!! Form::hidden('tickets_discussion_id', $tickets_discussion_id) !!}
            {!! Form::hidden('user_id', auth()->id()) !!}

            {!! Form::text('content', $per_post->content, ['id'=>'update-review-textarea-'.$per_post->id, 'class' => 'form-control update-review-textarea', 'placeholder' => 'Add Comment', 'required' => true]) !!}

            <div class="form-group is-empty update-preview-image-container"></div>
            
            <input type="hidden" id="review_id" name="review_id" value="{{$per_post->id }}">
            <div class="update-button"><button class="btn btn-primary btn-md-2">Update</button></div>
            
            {!! Form::close() !!}
        </div>
    </div>
</div>