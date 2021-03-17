<a data-toggle="modal" href="#modal-status-{!! $data['id'] !!}" class="fa fa-sun-o" title="Update Status"></a>
<div id="modal-status-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Change Interview Status</h1>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ["$name.status", $data['id']]]) !!}

            <input type="hidden" id="interview_id" name="interview_id" value="{!! $data['id'] !!}">

            <div class="modal-body">
                <strong>Select Interview Status :</strong> <br>
                {!! Form::select('status', $interview_status,null, array('id'=>'status','class' => 'form-control')) !!}
            </div>

            @if(isset($year) && $year != '')
                <input type="hidden" name="year" id="year" value="{{ $year }}"/>
            @endif

            @if(isset($source) && $source != '')
                <input type="hidden" name="source" id="source" value="{{ $source }}"/>
            @endif
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->