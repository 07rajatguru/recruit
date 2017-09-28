<a data-toggle="modal" href="#modal-delete-{!! $data['id'] !!}" class="">
    <i class="fa fa-fw fa-remove"></i>
    {{--<span class="glyphicon glyphicon-trash" style="color: #ffffff;"></span>--}}
</a>
<div id="modal-delete-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'DELETE', 'route' => ["$name.destroy", $data['id']]])!!}
            @if($name == 'clientattachments')
                {!! Form::hidden('clientid', $clientid, array('id'=>'clientid','placeholder' => 'Client Name','class' => 'form-control' )) !!}
            @else
                {!! Form::hidden('id', $id, array('id'=>'id','placeholder' => 'id','class' => 'form-control' )) !!}
            @endif
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Delete Data</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to delete this {!! $display_name !!}?
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
