<a data-toggle="modal" href="#modal-complete-{!! $data['id'] !!}" class="fa fa-circle" title="Complete the task">
    {{--{!! Form::submit('', ['class' => '']) !!}--}}
    {{--<span class="glyphicon glyphicon-trash" style="color: #ffffff;"></span>--}}
</a>
<div id="modal-complete-{!! $data['id']!!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name.complete", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Complete Todo</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to Complete this {!! $display_name !!}?
                    {!! Form::hidden('complete',null, array('id'=>'complete','class' => 'form-control')) !!}

                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
