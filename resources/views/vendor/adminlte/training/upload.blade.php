<a data-toggle="modal" href="#modal-upload-{!! $data['id'] !!}" class="row-edit">
    Upload Attachments
</a>
<div id="modal-upload-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST','files' => true, 'route' => ["$name.upload",$data['id']]])!!}

            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Document</strong>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {!! Form::file('file', null, array('id'=>'file','class' => 'form-control' , 'multiple' => true)) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->