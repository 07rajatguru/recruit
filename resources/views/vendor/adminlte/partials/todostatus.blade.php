

<a data-toggle="modal" href="#modal-status-{!! $data['id'] !!}" class="fa fa-sun-o" title="Status"></a>
<div id="modal-status-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Select Todo Status</h1>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ["$name.status", $data['id']]]) !!}
            <input type="hidden" id="id" name="id" value="{!! $data['id'] !!}">
            <div class="modal-body">
                <strong>Select Todo Status :</strong> <br>
                {!! Form::select('todostatus', $status,$data['status_ids'], array('id'=>'todostatus','class' => 'form-control')) !!}
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
