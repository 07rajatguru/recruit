@extends('adminlte::page')

@section('title', 'Add New User')

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

    {!! Form::open(array('route' => 'users.store','method'=>'POST','id' => 'users_form', 'autocomplete' => 'off')) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                            <strong>First Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('first_name', null, array('id'=>'first_name','placeholder' => 'First Name','class' => 'form-control', 'tabindex' => '1','onfocusout' => 'getFullName();')) !!}
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
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

                        <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                            <strong> Role : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('roles',$roles,null, array('id'=>'roles','class' => 'form-control', 'tabindex' => '10')) !!}
                            @if ($errors->has('roles'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('roles') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('working_hours') ? 'has-error' : '' }}">
                            <strong>Working Hours :</strong>
                            {!! Form::select('working_hours',$hours_array,$selected_working_hours, array('placeholder' => 'Select Hours','id' => 'working_hours', 'class' => 'form-control','tabindex' => '12')) !!}
                            @if ($errors->has('working_hours'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('working_hours') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('joining_date') ? 'has-error' : '' }}">
                            <strong>Joining Date : <span class = "required_fields">*</span>
                            </strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('joining_date', null, array('id'=>'joining_date','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '14', 'onchange' => 'getProEndDate();')) !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <strong> Status : </strong> <br/>
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

                        <div class="form-group">
                            <strong> Generate Report : </strong> <br/>
                            {!! Form::radio('daily_report','Yes', true, array('onclick' => 'reportSelection();')) !!}
                            {!! Form::label('Yes') !!} &nbsp;&nbsp;
                            {!! Form::radio('daily_report','No',false,array('onclick' => 'reportSelection();')) !!}
                            {!! Form::label('No') !!}
                        </div>

                        <div class="form-group {{ $errors->has('work_location') ? 'has-error' : '' }}">
                            <strong>Select Work Location : <span class = "required_fields">*</span></strong>
                            {!! Form::select('work_location',$work_type,$selected_work_type, array('id' => 'work_location', 'class' => 'form-control')) !!}
                            @if ($errors->has('work_location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('work_location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="box-body col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <strong>Last Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('last_name', null, array('id'=>'last_name','placeholder' => 'Last Name','class' => 'form-control', 'tabindex' => '2','onfocusout' => 'getFullName();')) !!}
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
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

                        <div class="form-group {{ $errors->has('company_id') ? 'has-error' : '' }}">
                            <strong>Select Company : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('company_id', $companies,$company_id, array('id'=>'company_id','class' => 'form-control','tabindex' => '8')) !!}
                            @if ($errors->has('company_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('company_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('reports_to') ? 'has-error' : '' }}">
                            <strong>Reports To :</strong>
                            {!! Form::select('reports_to', $reports_to,null, array('id'=>'reports_to','class' => 'form-control','tabindex' => '11')) !!}
                            @if ($errors->has('reports_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('reports_to') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('half_day_working_hours') ? 'has-error' : '' }}">
                            <strong>Half Day Working Hours :</strong>
                            {!! Form::select('half_day_working_hours',$hours_array,$selected_half_day_working_hours, array('placeholder' => 'Select Hours','id' => 'half_day_working_hours', 'class' => 'form-control','tabindex' => '13')) !!}
                            @if ($errors->has('half_day_working_hours'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('half_day_working_hours') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('probation_end_date') ? 'has-error' : '' }}">
                            <strong>Probation End Date : <span class = "required_fields">*</span></strong>
                            <div class="input-group probation_end_date">
                                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                {!! Form::text('probation_end_date', null, array('id'=>'probation_end_date','placeholder' => 'Probation End Date','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('account_manager') ? 'has-error' : '' }}">
                            <strong> Account Manager : </strong> <br/>
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

                        <div class="report_class" style="display:none;">
                            <div class="form-group">
                                <strong> Report Status : </strong> <br/>
                                {!! Form::checkbox('cv_report','Yes', true, array('id' => 'cv_report')) !!}
                                {!! Form::label('CVs Associated') !!} &nbsp;&nbsp;
                                {!! Form::checkbox('interview_report','Yes',true, array('id' => 'interview_report')) !!}
                                {!! Form::label('Interviews Scheduled') !!}&nbsp;&nbsp;
                                {!! Form::checkbox('lead_report','Yes',true, array('id' => 'lead_report')) !!}
                                {!! Form::label('Leads Added') !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Full Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '3', 'readonly' => 'true')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
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

                        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <strong>Department : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('type', $departments,null, array('id'=>'type','class' => 'form-control','tabindex' => '9','onchange' => 'getRoles()')) !!}
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('cluster_head') ? 'has-error' : '' }}">
                            <strong>Cluster Head :</strong>
                            {!! Form::select('cluster_head', $reports_to,null, array('id'=>'cluster_head','class' => 'form-control','tabindex' => '11')) !!}
                            @if ($errors->has('cluster_head'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cluster_head') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('employment_type') ? 'has-error' : '' }}">
                            <strong>Employment Type : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('employment_type', $employment_type,null, array('id'=>'employment_type','class' => 'form-control','tabindex' => '14','onchange' => 'getInternMonth()')) !!}
                            @if ($errors->has('employment_type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('employment_type') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('leave_applicable_date') ? 'has-error' : '' }}">
                            <strong>Leave Applicable Date : <span class = "required_fields">*</span></strong>
                            <div class="input-group leave_applicable_date">
                                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                {!! Form::text('leave_applicable_date', null, array('id'=>'leave_applicable_date','placeholder' => 'Leave Applicable Date','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group intern_months" style="display: none;">
                            <strong>Intern for How Many Month?: </strong> <br/>
                            {!! Form::radio('intern_month','3', false) !!}
                            {!! Form::label('3 Month') !!} &nbsp;&nbsp;
                            {!! Form::radio('intern_month','6',false) !!}
                            {!! Form::label('6 Month') !!}
                        </div>

                        <div class="form-group hr_adv" style="display: none;">
                            <strong> HR Advisory With or Without Recruitment : </strong> <br/>
                            {!! Form::radio('hr_adv_recruitemnt','Yes', false) !!}
                            {!! Form::label('With Recruitment') !!} &nbsp;&nbsp;
                            {!! Form::radio('hr_adv_recruitemnt','No',true) !!}
                            {!! Form::label('Without Recruitment') !!}
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            <input type="hidden" id="user_id" name="user_id" value="0">
            <input type="hidden" id="operations" name="operations" value="{{ $operations }}">
            <input type="hidden" id="bizpos_user_id" name="bizpos_user_id" value="{{ $bizpos_user_id }}">
        </div>
    {!! Form::close() !!}
@endsection

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            getRoles();
            getInternMonth();
            reportSelection();

            $("#type").select2();
            $("#roles").select2({placeholder: 'Select Role'});
            $("#reports_to").select2();
            $("#cluster_head").select2();
            $("#company_id").select2();
            $("#working_hours").select2();
            $("#half_day_working_hours").select2();
            $("#employment_type").select2();
            $("#work_location").select2();

            $("#joining_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#probation_end_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#leave_applicable_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

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
                    },
                    "joining_date": {
                        required: true
                    },
                    "probation_end_date": {
                        required: true
                    },
                    "leave_applicable_date": {
                        required: true
                    },
                    "employment_type": {
                        required: true
                    },
                    "work_location": {
                        required: true,
                    },
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
                    },
                    "joining_date": {
                        required: "Please Select Joining Date."
                    },
                    "probation_end_date": {
                        required: "Probation End Date required."
                    },
                    "leave_applicable_date": {
                        required: "Please Select Leave Applicable Date."
                    },
                    "employment_type": {
                        required: "Please Select Type of Employment."
                    },
                    "work_location": {
                        required: "Please Select Work Location."
                    },
                }
            });
        });

        function getProEndDate() {
            

        }

        function getRoles() {

            var department_id = $("#type").val();
            var user_id = $("#user_id").val();
            var app_url = "{!! env('APP_URL'); !!}";
            if(department_id == 2) {
                $(".hr_adv").show();
            }
            else {
                $(".hr_adv").hide();
            }

            if(department_id > 0) {
                $.ajax({
                    url: app_url+'/departments/getroles',
                    data:{department_id:department_id,user_id:user_id},
                    dataType:'json',
                    success: function(data) {
                        if(data.roles_res) {
                            $("#roles").empty();
                            $("#roles").append('<option value=""> Select Role </option>');
                            
                            $.each(data.roles_res,function(key, value) { 
                                if (data.pre_role_id == value.id) {
                                    $('select[id="roles"]').append('<option selected="selected" value="'+ value.id +'">' + value.name + '</option>');
                                }
                                else {
                                    $('select[id="roles"]').append('<option value="'+ value.id +'">' + value.name + '</option>');
                                }
                            }); 
                            $("#roles").select2();
                        }
                        else {
                            $("#roles").empty();
                        }

                        // Set By Default Bizpos user Id for Operations Department
                        var operations = $("#operations").val();
                        var bizpos_user_id = $("#bizpos_user_id").val();
                        
                        if(department_id == operations) {
                            $('#reports_to').val(bizpos_user_id);
                            $("#reports_to").select2();
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
            } else {
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
            } else {
                $("#cv_report").prop("checked", false);
                $("#interview_report").prop("checked", false);
                $("#lead_report").prop("checked", false);
                $(".report_class").hide();
            }
        }

        function getInternMonth() {
            var employment_type = $("#employment_type").val();
            if(employment_type == 'Intern') {
                $(".intern_months").show();
            } else {
                $(".intern_months").hide();
            }
        }
    </script>
@stop