<a data-toggle="modal" href="#modal-status-{!! $data['id'] !!}" class="fa fa-sun-o" title="Change Priority"></a>
<div id="modal-status-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Select Job Priority</h1>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ["$name.status", $data['id']]]) !!}
            <input type="hidden" id="job_id" name="job_id" value="{!! $data['id'] !!}">
            <input type="hidden" id="display_name" name="display_name" value="{!! $display_name !!}">
            <div class="modal-body">
                <strong>Select Job Priority :</strong> <br>
                {!! Form::select('job_priority', $job_priority,null, array('id'=>'job_priority','class' => 'form-control')) !!}
            </div>

            @if(isset($year) && $year != '')
                <input type="hidden" name="year" id="year" value="{{ $year }}"/>
            @endif
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
