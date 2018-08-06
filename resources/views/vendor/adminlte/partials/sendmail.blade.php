
<a data-toggle="modal" href="#modal-mail-{!! $data['id'] !!}" class="fa fa-send" title="Send Confirmation Mail"></a>
<div id="modal-mail-{!! $data['id'] !!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name.sendconfirmationmail", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Send Confirmation Mail</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to Send Confirmation Mail?
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
