<a data-toggle="modal" href="#modal-relive-hold-{!! $data['id'] !!}" class="fa fa-registered" title="Relive Hold {!! $display_name !!}"></a>
<div id="modal-relive-hold-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'GET', 'route' => ["$name.relivehold", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Hold {!! $display_name !!}</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to Relive this Hold {!! $display_name !!}?
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