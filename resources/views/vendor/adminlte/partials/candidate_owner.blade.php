<a data-toggle="modal" href="#modal-candidate-owner-{!! $data['id'] !!}" class="fa fa-sun-o" title="Candidate Owner"></a>
<div id="modal-candidate-owner-{!! $data['id'] !!}" class="modal text-left fade">
	<div class="modal-dialog">
        <div class="modal-content">

        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Candidate Owner</h2>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => ["$name.candidate_owner", $data['id']]]) !!}
                <input type="hidden" id="id" name="id" value="{!! $data['id'] !!}">
                <div class="modal-body">
                    <strong>Select Candidate Owner :</strong> <br><br>
                    {!! Form::select('candidate_owner',$all_users,$data['owner'], array('id'=>'candidate_owner','class' => 'form-control')) !!}
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

