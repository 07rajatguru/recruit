<a data-toggle="modal" href="#modal-secondline-am-{!! $data['id'] !!}" class="fa fa-star-o" title="Secondline Account Manager"></a>
<div id="modal-secondline-am-{!! $data['id'] !!}" class="modal text-left fade">
	<div class="modal-dialog">
        <div class="modal-content">

        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Second-line Account Manager</h1>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => ["$name.secondline_account_manager", $data['id']]]) !!}
            <input type="hidden" id="id" name="id" value="{!! $data['id'] !!}">
            <div class="modal-body">
                <strong>Select Account Manager :</strong> <br>
                {!! Form::select('secondline_account_manager', $account_manager, $data['second_line_am'], array('id'=>'secondline_account_manager','class' => 'form-control secondline_account_manager')) !!}
            </div>

            <input type="hidden" name="source" id="source" value="{{ $source }}">

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->