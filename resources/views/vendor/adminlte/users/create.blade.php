@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1>Create New User</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>

            </div>

        </div>

    </div>

    {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">

                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="">

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('email', null, array('id'=>'email','placeholder' => 'Email','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Secondary Gmail: </strong>
                                {!! Form::text('semail', null, array('id'=>'semail','placeholder' => 'Secondary Email','class' => 'form-control', 'tabindex' => '3' )) !!}
                                @if ($errors->has('semail'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('semail') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <strong>Password: <span class = "required_fields">*</span> </strong>
                                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control','tabindex' => '4' )) !!}
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('confirm-password') ? 'has-error' : '' }}">
                                <strong>Confirm Password: </strong>
                                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control','tabindex' => '5' )) !!}
                                @if ($errors->has('confirm-password'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('confirm-password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('reports_to') ? 'has-error' : '' }}">
                                <strong>Reports To :</strong>
                                {!! Form::select('reports_to', $reports_to,null, array('id'=>'reports_to','class' => 'form-control','tabindex' => '6' )) !!}
                                @if ($errors->has('reports_to'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('reports_to') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('floor_incharge') ? 'has-error' : '' }}">
                                <strong>Floor Incharge :</strong>
                                {!! Form::select('floor_incharge', $floor_incharge,null, array('id'=>'floor_incharge','class' => 'form-control','tabindex' => '7' )) !!}
                                @if ($errors->has('floor_incharge'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('floor_incharge') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('company_id') ? 'has-error' : '' }}">
                                <strong>Select Compnay :</strong>
                                {!! Form::select('company_id', $companies,null, array('id'=>'company_id','class' => 'form-control','tabindex' => '8' )) !!}
                                @if ($errors->has('company_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('company_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                <strong> Role : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','tabindex' => '9' )) !!}
                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('roles') }}</strong>
                                </span>
                                @endif
                            </div>

                             <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong> Type : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('type', $type,null, array('class' => 'form-control','tabindex' => '10' )) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
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

    </div>

    {!! Form::close() !!}
@endsection

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {
            reportSelection();
        });

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
                $("#cv_report").prop("checked", true);
                $("#interview_report").prop("checked", true);
                $("#lead_report").prop("checked", true);
                $(".report_class").show();
            }
            else
            {
                $("#cv_report").prop("checked", false);
                $("#interview_report").prop("checked", false);
                $("#lead_report").prop("checked", false);
                $(".report_class").hide();
            }
        }
    </script>
@stop
