<a data-toggle="modal" href="#modal-status-{!! $data['id'] !!}" class="fa fa-sun-o" title="Account Manager"></a>
<div id="modal-status-{!! $data['id'] !!}" class="modal text-left fade">
	<div class="modal-dialog">
        <div class="modal-content">

        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Account Manager</h1>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => ["$name.account_manager", $data['id']]]) !!}
            <input type="hidden" id="id" name="id" value="{!! $data['id'] !!}">
            <div class="modal-body">
                <strong>Select Account Manager :</strong> <br>
                {!! Form::select('account_manager', $account_manager,$data['account_mangr_id'], array('id'=>'account_manager','class' => 'form-control account_manager')) !!}
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

