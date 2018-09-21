<a data-toggle="modal" href="#modal-upload-{!! $data['id'] !!}" class="row-edit">
    Upload Attachments
</a>
<div id="modal-upload-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST','files' => true, 'route' => ["$name.upload", $data['id']]])!!}

            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {!! Form::file('file', null, array('id'=>'file','class' => 'form-control')) !!}
                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>

            </div>

            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
