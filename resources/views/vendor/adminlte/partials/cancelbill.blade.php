
<a data-toggle="modal" href="#modal-close-{!! $data['id'] !!}" class="fa fa-close" title="Cancel {!! $display_name !!}"></a>
<div id="modal-close-{!! $data['id'] !!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'GET', 'route' => ["$name.cancel", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Cancel {!! $display_name !!}</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to Cancel this {!! $display_name !!}?
                </p>
            </div>

            @if(isset($year) && $year != '')
                <input type="hidden" name="year" id="year" value="{{ $year }}"/>
            @endif
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
