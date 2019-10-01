@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
</style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Email Template</h2>
            @else
                <h2>Add Email Template</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('emailtemplate.index') }}"> Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($email_template,['method' => 'PATCH', 'files' => true, 'route' => ['emailtemplate.update', $email_template['id']],'id'=>'email_template_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'emailtemplate.store', 'id'=>'email_template_form', 'autocomplete' => 'off']) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <strong>Template Name: <span class = "required_fields">*</span> </strong>
                    {!! Form::text('name', null, array('id'=>'name','class' => 'form-control','placeholder' => 'Template Name','tabindex' => '1' )) !!}
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                    <strong>Subject: <span class = "required_fields">*</span> </strong>
                    {!! Form::text('subject', null, array('id'=>'subject','class' => 'form-control','placeholder' => 'Subject','tabindex' => '2' )) !!}
                    @if ($errors->has('subject'))
                        <span class="help-block">
                            <strong>{{ $errors->first('subject') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <strong>Email Body : </strong>
                    {!! Form::textarea('email_body',null, array('id'=>'email_body','placeholder' => 'Email Body','class' => 'form-control','tabindex' => '3','rows' => '4')) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="action" id="action" value="{{ $action }}">

{!! Form::close() !!}

@section('customscripts')
<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script>
        $(document).ready(function()
        {
            var action = $("#action").val();

            if(action == 'add'){    
                document.getElementById("email_body").defaultValue = "Dear Clientname, ";
            }

            CKEDITOR.replace( 'email_body', {
                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js',

            });

            CKEDITOR.on('dialogDefinition', function( ev ){
               var dialogName = ev.data.name;  
               var dialogDefinition = ev.data.definition;
                     
               switch (dialogName) {  
               case 'image': //Image Properties dialog      
               dialogDefinition.removeContents('Link');
               dialogDefinition.removeContents('advanced');
               break;      
               case 'link': //image Properties dialog          
               dialogDefinition.removeContents('advanced');   
               break;
               }
            });

            $("#email_template_form").validate(
            {
                rules: {
                    "name": {
                        required: true
                    },
                    "subject": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Template Name is Required Field."
                    },
                    "subject": {
                        required: "Subject is Required Field."
                    }
                }
            });
        });
    </script>
@endsection