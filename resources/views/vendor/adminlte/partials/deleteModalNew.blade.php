<a data-toggle="modal" href="#modal-delete-{!! $data['id'] !!}" class="fa fa-trash" title="Delete">
    {{--{!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
    {{--<span class="glyphicon glyphicon-trash" style="color: #ffffff;"></span>--}}
</a>
<div id="modal-delete-{!! $data['id'] !!}" class="modal text-left fade">


    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'DELETE', 'route' => ["$name.destroy", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-title">Delete Data</h1>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure want to delete this {!! $display_name !!}?
                </p>
            </div>

            @if(isset($year) && $year != '')
                <input type="hidden" name="year" id="year" value="{{ $year }}"/>
            @endif

            @if(isset($Lead_Type) && $Lead_Type != '')
                <input type="hidden" name="Lead_Type" id="Lead_Type" value="{{ $Lead_Type }}"/>
            @endif

            @if(isset($Contact_Type) && $Contact_Type != '')
                <input type="hidden" name="Contact_Type" id="Contact_Type" value="{{ $Contact_Type }}"/>
            @endif
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
