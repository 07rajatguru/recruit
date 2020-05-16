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
            @if( $action == 'edit')
                <h2>Edit Support</h2>
            @else
                <h2>Add New Support</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

    @if($action == 'edit')
        {!! Form::model($customer_support,['method' => 'PATCH', 'files' => true, 'route' => ['customer.update', $customer_support['id']],'id'=>'customer_form', 'autocomplete' => 'off']) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'customer.store','id'=>'customer_form', 'autocomplete' => 'off']) !!}
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">

                    <div class="form-group {{ $errors->has('module') ? 'has-error' : '' }}">
                        <strong>Select Module: <span class = "required_fields">*</span> </strong>
                        {!! Form::select('module',$modules,$selected_modules, array('id'=>'module','class' => 'form-control','tabindex' => '1' )) !!}
                        @if ($errors->has('module'))
                            <span class="help-block">
                                <strong>{{ $errors->first('module') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <strong>Subject: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('subject', null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '2' )) !!}
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                        <strong>Message: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('message', null, array('id'=>'message','rows'=>'5','placeholder' => 'Message','class' => 'form-control', 'tabindex' => '3' )) !!}
                        @if ($errors->has('message'))
                            <span class="help-block">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                         @endif
                    </div>

                    @if($action == "add")
                        <div class="form-group">
                            <strong>Upload Documents:</strong>
                            <input type="file" name="upload_documents[]" multiple class="form-control" />
                        </div>
                    @endif

                    </div>
                </div>
            </div>
        </div>
     
       <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($customer_support) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

{!! Form::close() !!}

@section('customscripts')
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#module").select2();

        $("#customer_form").validate(
        {
            rules: {
                "module": {
                        required: true
                },
                "subject": {
                        required: true
                },
                "message": {
                        required: true
                }
            },
            messages: {
                "module": {
                        required: "Module is Required Field."
                },
                "subject": {
                        required: "Subject is Required Field."
                },
                "message": {
                        required: "Message is Required Field."
                }
            }
        });
    });
</script>
@endsection