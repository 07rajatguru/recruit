@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Edit user details</h1>
@stop

@section('content')
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
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::model($user, ['method' => 'PATCH','id' => 'users_form','route' => ['users.update', $user->id]]) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                <strong>First Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('first_name', null, array('id'=>'first_name','placeholder' => 'First Name','class' => 'form-control', 'tabindex' => '1','onfocusout' => 'getFullName();')) !!}
                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <strong>Last Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('last_name', null, array('id'=>'last_name','placeholder' => 'Last Name','class' => 'form-control', 'tabindex' => '2','onfocusout' => 'getFullName();')) !!}
                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Full Name:</strong>
                                {!! Form::text('name', null, array('placeholder' => 'Full Name','class' => 'form-control','tabindex' => '3', 'readonly' => 'true','id'=>'name')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Email:</strong>
                                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control','tabindex' => '4','id'=>'email')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Secondary Gmail:</strong>
                                {!! Form::text('semail', $semail, array('placeholder' => 'Secondary Email','class' => 'form-control','tabindex' => '5','id'=>'semail')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Password:</strong>
                                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control','tabindex' => '6','id'=>'password')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control','tabindex' => '7','id'=>'confirm-password')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Reports To :</strong>
                                {!! Form::select('reports_to', $reports_to,isset($userReportsTo) ? $userReportsTo : null, array('id'=>'reports_to','class' => 'form-control','tabindex' => '8')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Floor Incharge :</strong>
                                {!! Form::select('floor_incharge', $floor_incharge,isset($userFloorIncharge) ? $userFloorIncharge : 0, array('id'=>'floor_incharge','class' => 'form-control','tabindex' => '9')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Select Company :</strong>
                                {!! Form::select('company_id', $companies,isset($user->compnay_id) ? $user->compnay_id : null, array('id'=>'company_id','class' => 'form-control','tabindex' => '10')) !!}
                            </div>
      
                            <div class="form-group">
                                <strong>Role:</strong>
                                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','tabindex' => '11')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Type:</strong>
                                {!! Form::select('type', $type, null, array('class' => 'form-control','tabindex' => '12')) !!}
                            </div>

                            <div class="form-group">
                                <strong> Floor Incharge : </strong> &nbsp;&nbsp;
                                {!! Form::radio('check_floor_incharge','Yes', true) !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('check_floor_incharge','No',false) !!}
                                {!! Form::label('No') !!}
                            </div>

                            <div class="form-group">
                                <strong> Generate Report : </strong> &nbsp;&nbsp;
                                {!! Form::radio('daily_report','Yes', true, array('onclick' => 'reportSelection();')) !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('daily_report','No','',array('onclick' => 'reportSelection();')) !!}
                                {!! Form::label('No') !!}
                            </div>
       
                            <div class="report_class" style="display:none;">
                                <div class="form-group">
                                    <strong> Report Status : </strong> &nbsp;&nbsp;
                                    {!! Form::checkbox('cv_report','Yes') !!}
                                    {!! Form::label('CVs Associated') !!} &nbsp;&nbsp;
                                   
                                    {!! Form::checkbox('interview_report','Yes') !!}
                                    {!! Form::label('Interviews Scheduled') !!}&nbsp;&nbsp;
                                   
                                    {!! Form::checkbox('lead_report','Yes') !!}
                                    {!! Form::label('Leads Added') !!}
                                </div>
                            </div>
       
                            <div class="form-group">
                                <strong>Status:</strong>&nbsp;&nbsp;
                                {!! Form::radio('status','Active') !!}
                                {!! Form::label('Active') !!} &nbsp;&nbsp;
                                {!! Form::radio('status','Inactive') !!}
                                {!! Form::label('Inactive') !!}
                            </div>

                            <div class="form-group">
                                <strong>Account Manager:</strong>&nbsp;&nbsp;
                                {!! Form::radio('account_manager','Yes') !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('account_manager','No') !!}
                                {!! Form::label('No') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            $("#users_form").validate({
                rules: {
                    "first_name": {
                        required: true
                    },
                    "last_name": {
                        required: true
                    },
                    "email": {
                        required: true
                    },
                    "password": {
                        required: true
                    },
                    "company_id": {
                        required: true
                    },
                    "type": {
                        required: true
                    },
                },
                messages: {
                    "first_name": {
                        required: "First Name is required field."
                    },
                    "last_name": {
                        required: "Last Name is required field."
                    },
                    "email": {
                        required: "Email is required field."
                    },
                    "password": {
                        required: "Password is required field."
                    },
                    "company_id": {
                        required: "Please Select Company"
                    },
                    "type": {
                        required: "Please Select Type"
                    },
                }
            });

            reportSelection();
        });

        function getFullName()
        {
            // Get Display Name

            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var display_name = '';

            if(first_name != '' && last_name != '')
            {
                display_name = first_name + " " + last_name.charAt(0) + ".";
            }
            else
            {
                display_name = '';
            }

            $("#name").val(display_name);
        }

        function reportSelection()
        {
            var report_value = document.getElementsByName('daily_report');
            var report_item_value="";
            for(var i=0; i<report_value.length; i++)
            {
                if(report_value[i].type=='radio' && report_value[i].checked==true)
                {
                    report_item_value += report_value[i].value;
                }
            }

            if(report_item_value == 'Yes')
            {
                $(".report_class").show();
            }
            else
            {
                $(".report_class").hide();
            }
        }
    </script>
@stop