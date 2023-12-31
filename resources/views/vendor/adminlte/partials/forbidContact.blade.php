<a data-toggle="modal" href="#modal-forbid-{!! $data['id'] !!}" class="fa fa-foursquare" title="Forbid {!! $display_name !!}"></a>
<div id="modal-forbid-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'GET', 'route' => ["$name.generateforbid", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Forbid {!! $display_name !!}</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to Forbid this {!! $display_name !!}?
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