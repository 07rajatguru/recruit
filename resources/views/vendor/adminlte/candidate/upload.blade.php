<a data-toggle="modal" href="#modal-upload-{!! $data['id'] !!}" class="row-edit">
    Upload Attachments
</a>
<div id="modal-upload-{!! $data['id'] !!}" class="modal text-left fade">
    <div class="modal-dialog">
        <div class="modal-content">

            @if(isset($form_name) && $form_name != '')
                {!! Form::open(['method' => 'POST','files' => true, 'route' => ["$name.upload", $data['candidate_id']]])!!}
                <input type="hidden" name="form_name" id="form_name" value="{{ $form_name }}">
            @else
                {!! Form::open(['method' => 'POST','files' => true, 'route' => ["$name.upload", $data['id']]])!!}
            @endif

            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group {{ $errors->has('candidate_upload_type') ? 'has-error' : '' }}">
                        <strong>Select type</strong>
                        {!! Form::select('candidate_upload_type', $candidate_upload_type,null, array('id'=>'candidate_upload_type','class' => 'form-control')) !!}
                        @if ($errors->has('candidate_upload_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('candidate_upload_type') }}</strong>
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
