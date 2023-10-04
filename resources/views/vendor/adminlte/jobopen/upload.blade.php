<a data-toggle="modal" href="#modal-delete-{!! $data['id'] !!}" class="row-edit">
    Upload Attachments
</a>
<div id="modal-delete-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST','files' => true, 'route' => ["$name.upload", $data['id']]])!!}

            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group {{ $errors->has('upload_type') ? 'has-error' : '' }}">
                        <strong>Select type</strong>
                        {!! Form::select('upload_type', $upload_type,null, array('id'=>'upload_type','class' => 'form-control')) !!}
                        @if ($errors->has('upload_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('upload_type') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                    <label for="file">Choose File:</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".pdf, .jpg, .jpeg, .png, .gif, .xls, .xlsx, .doc, .docx, .ppt, .pptx, .txt"  multiple class="form-control" onchange="FormValidation.validateFile(this)"/>   
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
<script src="{{ asset('js/validation_file.js') }}"></script>

