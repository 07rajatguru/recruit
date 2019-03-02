@extends('adminlte::page')

@section('title', 'User Leave Application')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add Leave Application</h2>  
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

    {!! Form::open(array('route' => 'leave.store','method'=>'POST','id' => 'userleave','files' => true, 'id' => 'leave_form')) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <strong>Subject: <span class = "required_fields">*</span></strong>
                            {!! Form::text('subject',null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1' )) !!}
                            @if ($errors->has('subject'))
                                <span class="help-block">
                            <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('from_date') ? 'has-error' : '' }}">
                            <strong>From Date: <span class = "required_fields">*</span></strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            {!! Form::text('from_date',null, array('id'=>'from_date','placeholder' => 'From Date','class' => 'form-control','tabindex' => '3')) !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('leave_msg') ? 'has-error' : '' }}">
                            <strong>Message: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('leave_msg', null, array('id'=>'leave_msg','placeholder' => 'Message','class' => 'form-control', 'tabindex' => '6'  )) !!}
                            @if ($errors->has('leave_msg'))
                                <span class="help-block">
                            <strong>{{ $errors->first('leave_msg') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('leave_type') ? 'has-error' : '' }}">
                        <strong>Leave Type: <span class = "required_fields">*</span></strong>
                        {!! Form::select('leave_type', $leave_type,null, array('id' => 'leave_type','class' => 'form-control','tabindex' => '2', 'onchange' => 'checktype();' )) !!}
                        @if ($errors->has('leave_type'))
                            <span class="help-block">
                        <strong>{{ $errors->first('leave_type') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group to_date {{ $errors->has('to_date') ? 'has-error' : '' }}" style="display: none;">
                        <strong>To Date: </strong>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        {!! Form::text('to_date',null, array('id'=>'to_date','placeholder' => 'To Date','class' => 'form-control','tabindex' => '4')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('leave_category') ? 'has-error' : '' }}">
                        <strong>Leave Category: <span class = "required_fields">*</span></strong>
                        {!! Form::select('leave_category', $leave_category,null, array('id' => 'leave_category', 'class' => 'form-control','tabindex' => '5', 'onchange' => 'category();' )) !!}
                        @if ($errors->has('leave_category'))
                            <span class="help-block">
                        <strong>{{ $errors->first('leave_category') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group document {{ $errors->has('doc') ? 'has-error' : '' }}" style="display: none;">
                        <strong>Attachment: <span class = "required_fields">*</span> </strong>
                        <input type="file" name="doc[]" multiple class="form-control" />
                        @if ($errors->has('doc'))
                            <span class="help-block">
                        <strong>{{ $errors->first('doc') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary" onclick="validation();">Submit</button>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection

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

            $("#leave_msg").wysihtml5();
        });

        function category(){
            leave_cat = $("#leave_category").val();

            if (leave_cat == 'Medical') {
                $(".document").show();
            }
            else {
                $(".document").hide();
            }
        }

        function checktype(){
            var leave_type = $("#leave_type").val();

            if (leave_type == 'Full') {
                $(".to_date").show();
            }
            else{
                $(".to_date").hide();
            }
        }

        function validation(){

            leave_cat = $("#leave_category").val();
            if (leave_cat == 'Medical') {
                $("#leave_form").validate({
                    rules: {
                        "subject": {
                            required: true
                        },
                        "from_date": {
                            required: true
                        },
                        "leave_type": {
                            required: true
                        },
                        "leave_category": {
                            required: true
                        },
                        "leave_msg": {
                            required: true
                        },
                        "doc[]": {
                            required: true
                        }
                    },
                    messages: {
                        "subject": {
                            required: "Subject is required field."
                        },
                        "from_date": {
                            required: "From date is required field."
                        },
                        "leave_type": {
                            required: "Leave type is required field."
                        },
                        "leave_category": {
                            required: "Leave Category is required field."
                        },
                        "leave_msg": {
                            required: "Message is required field."
                        },
                        "doc[]": {
                            required: "Document file is required field."
                        }
                    }
                });
            }
            else {
                $("#leave_form").validate({
                    rules: {
                        "subject": {
                            required: true
                        },
                        "from_date": {
                            required: true
                        },
                        "leave_type": {
                            required: true
                        },
                        "leave_category": {
                            required: true
                        },
                        "leave_msg": {
                            required: true
                        }
                    },
                    messages: {
                        "subject": {
                            required: "Subject is required field."
                        },
                        "from_date": {
                            required: "From date is required field."
                        },
                        "leave_type": {
                            required: "Leave type is required field."
                        },
                        "leave_category": {
                            required: "Leave Category is required field."
                        },
                        "leave_msg": {
                            required: "Message is required field."
                        }
                    }
                });
            }
        }
    
    </script>
@endsection
