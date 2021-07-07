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
                <h2>Edit Ticket Details</h2>
            @else
                <h2>Add New Ticket</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('ticket.index') }}">Back</a>
        </div>
    </div>
</div>

    @if($action == 'edit')
        {!! Form::model($ticket_res,['method' => 'PATCH', 'files' => true, 'route' => ['ticket.update', $ticket_res['id']],'id'=>'ticket_form', 'autocomplete' => 'off']) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'ticket.store','id'=>'ticket_form', 'autocomplete' => 'off']) !!}
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">

                    <div class="">
                        <div class="form-group {{ $errors->has('question_type') ? 'has-error' : '' }}">
                            <strong>Select Question Type : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('question_type',$question_type,$selected_question_type,array('id'=>'question_type','class' => 'form-control','tabindex' => '1' )) !!}
                            @if ($errors->has('question_type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('question_type') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <strong>Description : <span class = "required_fields">*</span></strong>
                                {!! Form::textarea('description',null, array('id'=>'description','rows'=>'5','placeholder' => 'Description','class' => 'form-control', 'tabindex' => '2' )) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>

                        @if($action == "add")
                            <div class="form-group">
                                <strong>Upload Documents :</strong>
                                <input type="file" name="upload_documents[]" multiple class="form-control" tabindex="3" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
     
       <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($ticket_res) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

{!! Form::close() !!}

@section('customscripts')
<script type="text/javascript">
    $(document).ready(function() {

        $("#question_type").select2();

        $("#ticket_form").validate({

            rules: {
                "question_type": {
                    required: true
                },
                "description": {
                    required: true
                }
            },
            messages: {
                "question_type": {
                    required: "Question Type is Required Field."
                },
                "description": {
                    required: "Description is Required Field."
                }
            }
        });
    });
</script>
@endsection