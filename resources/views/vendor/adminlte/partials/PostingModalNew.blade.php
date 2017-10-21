<a data-toggle="modal" href="#modal-postingstatus-{!! $data['id'] !!}" class="row-edit">
    {!! Form::submit('Posting Status', ['class' => 'btn btn-danger']) !!}
    {{--<span class="glyphicon glyphicon-trash" style="color: #ffffff;"></span>--}}
</a>
<div id="modal-postingstatus-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Select Posting Status Options</h1>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ["$name.postingstatus", $data['id']]]) !!}

            <div class="modal-body">
                @foreach ( $posting_status as $i => $v )
                    {!! Form::checkbox( 'posting_status[]',$v,in_array($posting_status[$i],array()),['class' => 'md-check', 'id' => $v] ) !!}
                    {!! Form::label($v,  $v) !!}
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
