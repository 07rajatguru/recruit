<a data-toggle="modal" href="#modal-delete-{!! $data['id'] !!}" class="fa fa-times" title="Cancel" style="color: red;"></a>

<div id="modal-delete-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name.cancel", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Cancel Leave</h1>
            </div>
            <div class="modal-body">
                <p> Are you sure want to Cancel this {!! $display_name !!}? </p>

                <label>Remarks: <small>(If any)</small></label>
                <textarea id="leave_c_remarks" name="leave_c_remarks" class="form-control" placeholder="Remarks (If any)"></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
