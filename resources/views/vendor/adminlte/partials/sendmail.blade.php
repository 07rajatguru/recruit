
<a data-toggle="modal" href="#modal-mail-{!! $data['id'] !!}" class="{!! $class !!}" title="{!! $title !!}"></a>
<div id="modal-mail-{!! $data['id'] !!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">{!! $model_title !!}</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure {!! $model_body !!}
                </p>
            <input type="hidden" name="id" id="id" value="{{ $data['id'] }}"/>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
