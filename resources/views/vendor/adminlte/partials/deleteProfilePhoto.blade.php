<a data-toggle="modal" href="#modal-delete-{!! $data['id'] !!}" class="btn btn-danger" title="Delete">

    Remove Profile Photo
</a>
<div id="modal-delete-{!! $data['id'] !!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'DELETE', 'route' => ["$name.destroy", $data['id']]])!!}

            <form method="DELETE" action="">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Delete Data</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are You Sure Want to delete This {!! $display_name !!}?
                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
