@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
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
                <h2>Add New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}">Back</a>
            </div>
        </div>
    </div>

    {!! Form::open(array('route' => 'users.store','method'=>'POST','id' => 'users_form')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>
                
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

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>Full Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '3', 'readonly' => 'true')) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('email', null, array('id'=>'email','placeholder' => 'Email','class' => 'form-control', 'tabindex' => '4')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Secondary Gmail: </strong>
                                {!! Form::text('semail', null, array('id'=>'semail','placeholder' => 'Secondary Email','class' => 'form-control', 'tabindex' => '5' )) !!}
                                @if ($errors->has('semail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('semail') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <strong>Password: <span class = "required_fields">*</span> </strong>
                                {!! Form::password('password', array('id'=>'password','placeholder' => 'Password','class' => 'form-control','tabindex' => '6')) !!}
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('confirm-password') ? 'has-error' : '' }}">
                                <strong>Confirm Password: </strong>
                                {!! Form::password('confirm-password', array('id'=>'confirm-password', 'placeholder' => 'Confirm Password','class' => 'form-control','tabindex' => '7')) !!}
                                @if ($errors->has('confirm-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm-password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('reports_to') ? 'has-error' : '' }}">
                                <strong>Reports To :</strong>
                                {!! Form::select('reports_to', $reports_to,null, array('id'=>'reports_to','class' => 'form-control','tabindex' => '8')) !!}
                                @if ($errors->has('reports_to'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reports_to') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('floor_incharge') ? 'has-error' : '' }}">
                                <strong>Floor Incharge :</strong>
                                {!! Form::select('floor_incharge', $floor_incharge,null, array('id'=>'floor_incharge','class' => 'form-control','tabindex' => '9' )) !!}
                                @if ($errors->has('floor_incharge'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('floor_incharge') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('company_id') ? 'has-error' : '' }}">
                                <strong>Select Compnay : <span class = "required_fields">*</span> 
                                </strong>
                                {!! Form::select('company_id', $companies,null, array('id'=>'company_id','class' => 'form-control','tabindex' => '10')) !!}
                                @if ($errors->has('company_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong>Department : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('type', $departments,null, array('id'=>'type','class' => 'form-control','tabindex' => '11','onchange' => 'getRoles()')) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group hr_adv" style="display: none;">
                                <strong> HR Advisory With or Without Recruitemnt : </strong> &nbsp;&nbsp;
                                {!! Form::radio('hr_adv_recruitemnt','Yes', false) !!}
                                {!! Form::label('With Recruitemnt') !!} &nbsp;&nbsp;
                                {!! Form::radio('hr_adv_recruitemnt','No',true) !!}
                                {!! Form::label('Without Recruitemnt') !!}
                            </div>

                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                <strong> Role : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('roles',$roles,null, array('id'=>'roles','class' => 'form-control', 'tabindex' => '12')) !!}
                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <!-- <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong> Type : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('type', $type,null, array('class' => 'form-control','tabindex' => '12' )) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div> -->

                            <div class="form-group">
                                <strong> Floor Incharge : </strong> &nbsp;&nbsp;
                                {!! Form::radio('check_floor_incharge','Yes', false) !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('check_floor_incharge','No',true) !!}
                                {!! Form::label('No') !!}
                            </div>

                            <div class="form-group">
                                <strong> Generate Report : </strong> &nbsp;&nbsp;
                                {!! Form::radio('daily_report','Yes', true, array('onclick' => 'reportSelection();')) !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('daily_report','No',false,array('onclick' => 'reportSelection();')) !!}
                                {!! Form::label('No') !!}
                            </div>

                            <div class="report_class" style="display:none;">
                                <div class="form-group">
                                    <strong> Report Status : </strong> &nbsp;&nbsp;
                                    {!! Form::checkbox('cv_report','Yes', true, array('id' => 'cv_report')) !!}
                                    {!! Form::label('CVs Associated') !!} &nbsp;&nbsp;
                                    {!! Form::checkbox('interview_report','Yes',true, array('id' => 'interview_report')) !!}
                                    {!! Form::label('Interviews Scheduled') !!}&nbsp;&nbsp;
                                    {!! Form::checkbox('lead_report','Yes',true, array('id' => 'lead_report')) !!}
                                    {!! Form::label('Leads Added') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <strong> Eligibility Report : </strong> &nbsp;&nbsp;
                                {!! Form::radio('eligibility_report','Yes', true) !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('eligibility_report','No',false) !!}
                                {!! Form::label('No') !!}
                            </div>
                          
                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                <strong> Status : </strong> &nbsp;&nbsp;
                                {!! Form::radio('status','Active', true) !!}
                                {!! Form::label('Active') !!} &nbsp;&nbsp;
                                {!! Form::radio('status','Inactive') !!}
                                {!! Form::label('Inactive') !!}
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                                <strong> Account Manager : </strong> &nbsp;&nbsp;
                                {!! Form::radio('account_manager','Yes') !!}
                                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                                {!! Form::radio('account_manager','No', true) !!}
                                {!! Form::label('No') !!}
                                @if ($errors->has('account_manager'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('account_manager') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

        <input type="hidden" id="user_id" name="user_id" value="0">
    </div>

    {!! Form::close() !!}
@endsection

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            getRoles();

            $("#type").select2();
            $("#roles").select2({placeholder: 'Select Role'});

            // automaticaly open the select2 when it gets focus
            jQuery(document).on('focus', '.select2', function() {
                jQuery(this).siblings('select').select2('open');
            });
            
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
                    "roles": {
                        required: true
                    }
                },
                messages: {
                    "first_name": {
                        required: "First Name is Required Field."
                    },
                    "last_name": {
                        required: "Last Name is Required Field."
                    },
                    "email": {
                        required: "Email is Required Field."
                    },
                    "password": {
                        required: "Password is Required Field."
                    },
                    "company_id": {
                        required: "Please Select Company."
                    },
                    "type": {
                        required: "Please Select Department."
                    },
                    "roles": {
                        required: "Please Select Role."
                    }
                }
            });

            reportSelection();
        });

        function getRoles() {

            var department_id = $("#type").val();
            var user_id = $("#user_id").val();

            if(department_id == 2) {

                $(".hr_adv").show();
            }
            else {

                $(".hr_adv").hide();
            }

            if(department_id > 0) {

                $.ajax({

                    url:'/departments/getroles',
                    data:{department_id:department_id,user_id:user_id},
                    dataType:'json',

                    success: function(data) {

                        if(data.roles_res) {

                            $("#roles").empty();

                            $("#roles").append('<option value=""> Select Role </option>');
                            
                            $.each(data.roles_res,function(key, value) { 

                                if (data.pre_department_id == key) {

                                    $('select[id="roles"]').append('<option selected="selected" value="'+ key +'">' + value + '</option>');        
                                }
                                else {

                                    $('select[id="roles"]').append('<option value="'+ key +'">' + value + '</option>');
                                }
                            }); 
                            $("#roles").select2();
                        }
                        else{
                            $("#roles").empty();
                        }
                    }
                });
            }
        }

        function getFullName() {

            // Get Display Name

            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var display_name = '';

            if(first_name != '' && last_name != '') {
                display_name = first_name + " " + last_name.charAt(0) + ".";
            }
            else {
                display_name = '';
            }

            $("#name").val(display_name);
        }

        function reportSelection() {

            var report_value = document.getElementsByName('daily_report');
            var report_item_value="";

            for(var i=0; i<report_value.length; i++) {

                if(report_value[i].type=='radio' && report_value[i].checked==true) {
                    report_item_value += report_value[i].value;
                }
            }

            if(report_item_value == 'Yes') {

                $("#cv_report").prop("checked", true);
                $("#interview_report").prop("checked", true);
                $("#lead_report").prop("checked", true);
                $(".report_class").show();
            }
            else {

                $("#cv_report").prop("checked", false);
                $("#interview_report").prop("checked", false);
                $("#lead_report").prop("checked", false);
                $(".report_class").hide();
            }
        }
    </script>
@stop