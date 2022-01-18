@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Request</h2>
            @else
                <h2>Add New Request</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('workfromhome.index') }}">Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($work_from_home_res, ['method' => 'PATCH','files' => true,'route' => ['workfromhome.update', $work_from_home_res->id], 'id' => 'workfromhome_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(array('route' => 'workfromhome.store','method'=>'POST','files' => true, 'id' => 'workfromhome_form', 'autocomplete' => 'off')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <strong>Subject : <span class = "required_fields">*</span></strong>
                            {!! Form::text('subject',null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1')) !!}
                            @if ($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('from_date') ? 'has-error' : '' }}">
                            <strong>From Date : <span class = "required_fields">*</span></strong>
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                {!! Form::text('from_date', isset($from_date) ? $from_date : null, array('id'=>'from_date','placeholder' => 'From Date','class' => 'form-control', 'tabindex' => '2')) !!}
                            </div>
                        </div>

                        <div class="form-group to_date {{ $errors->has('to_date') ? 'has-error' : '' }}">
                            <strong>To Date : <span class = "required_fields">*</span></strong>
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                {!! Form::text('to_date', isset($to_date) ? $to_date : null, array('id'=>'to_date','placeholder' => 'To Date','class' => 'form-control', 'tabindex' => '3')) !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('reason') ? 'has-error' : '' }}">
                            <strong>Reason : <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('reason', null, array('id'=>'reason','placeholder' => 'Reason','class' => 'form-control', 'tabindex' => '4')) !!}
                            @if ($errors->has('reason'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('reason') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($work_from_home_res) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("#from_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#to_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#reason").wysihtml5();

            $("#workfromhome_form").validate({
                rules: {
                    "subject": {
                        required: true
                    },
                    "from_date": {
                        required: true
                    },
                    "to_date": {
                        required: true
                    },
                },
                messages: {
                    "subject": {
                        required: "Subject is Required Field."
                    },
                    "from_date": {
                        required: "From Date is Required Field."
                    },
                    "to_date": {
                        required: "To Date is Required Field."
                    },
                }
            });
        });
    </script>
@endsection