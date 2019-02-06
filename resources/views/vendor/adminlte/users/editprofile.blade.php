@extends('adminlte::page')

@section('title', 'Profile')

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
            <h2>Edit User Profile</h2>  
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

    {!! Form::open(array('route' => ['users.profilestore',$user_id],'method'=>'POST','id' => 'editprofile','files' => true)) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('name',$user['name'], array('id'=>'name','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: <span class = "required_fields">*</span> </strong>
                                @if($isSuperAdmin || $isAccountant)
                                    {!! Form::text('email',$user['email'], array('id'=>'email','placeholder' => 'Email','class' => 'form-control')) !!}
                                @else
                                    {!! Form::text('email',$user['email'], array('id'=>'email','placeholder' => 'Email','class' => 'form-control','disabled')) !!}
                                @endif
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                                <strong>Birth Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_birth', isset($user['birth_date']) ? $user['birth_date'] : null, array('id'=>'date_of_birth','placeholder' => 'Birth Date','class' => 'form-control','tabindex' => '2')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                <strong>Joining Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_joining', isset($user['join_date']) ? $user['join_date'] : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '3')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('date_of_anni') ? 'has-error' : '' }}">
                                <strong>Anniversary Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_anni',isset($user['anni_date']) ? $user['anni_date'] : null, array('id'=>'date_of_anni','placeholder' => 'Anniversary Date','class' => 'form-control','tabindex' => '6')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                                <strong>Contact Number: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('contact',isset($user['contact_number']) ? $user['contact_number'] : null, array('id'=>'contact','placeholder' => 'Contact Number','class' => 'form-control','tabindex' => '6')) !!}
                                @if ($errors->has('contact'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                           <!--  <div class="form-group">
                                <strong>Profile Photo: </strong><br/>

                                @if($user['type'] == "Photo")
                                <img src= "../{!!$user['photo']!!}" height="172px" width="170px" />
                                @else
                                <img src= "../../uploads/User_Default.jpg" height="100px" width="100px" />
                                @endif
                            </div> -->

                            <div class="form-group" id="default_image">
                                @if($user['type'] == "Photo")
                                    <img src= "../../{!!$user['photo']!!}" height="150px" width="150px" />
                                @else
                                    <img src= "../../uploads/User_Default.jpg" height="150px" width="150px" />
                                @endif
                            </div>

                            <div class="form-group" id="image_div">
                            
                                <div class="form-group file_input_redesign upload_img1">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div style="width: 22px; height: 70px;display:none;" class="fileinput-new thumbnail" data-trigger="fileinput">
                                            <span>Select Image</span>
                                        </div>
                                        <div id = "upload_images_div">
                                        </div>
                                        <div>
                                            <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select Profile Photo</span>
                                           <!--  <span class="fileinput-exists" style="">Change</span> -->
                                            <input type="file" name="image" id="upload_img" accept="image/x-png,image/gif,image/jpeg" />
                                            </span>
                                            <!-- <a href="#" class="btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> -->
                                        </div>
                                    </div>  
                                </div>
                            </div>
                       
                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation: </strong>
                                @if($isSuperAdmin || $isAccountant)
                                    {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control')) !!}
                                @else
                                    {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','disabled')) !!}
                                @endif
                                @if ($errors->has('designation'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('designation') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Secondary Gmail: </strong>
                                {!! Form::text('semail',$user['s_email'], array('id'=>'semail','placeholder' => 'Secondary Gmail','class' => 'form-control', 'tabindex' => '5' )) !!}
                                @if ($errors->has('semail'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('semail') }}</strong>
                                </span>
                                @endif
                            </div>

                            <?php if($isSuperAdmin || $isAccountant) { ?>
                            <div class="form-group {{ $errors->has('date_of_exit') ? 'has-error' : '' }}">
                                <strong>Exit Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_exit',isset($user['exit_date']) ? $user['exit_date'] : null, array('id'=>'date_of_exit','placeholder' => 'Exit Date','class' => 'form-control','tabindex' => '7')) !!}
                                </div>
                            </div> 
                            <?php }?>  
                            
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Family Details</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                {{--First family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_1') ? 'has-error' : '' }}">
                            <strong>Name: </strong>
                            {!! Form::text('name_1', $user['name_1'],array('id'=>'name_1','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '8' )) !!}
                            @if ($errors->has('name_1'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name_1') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('relationship_1') ? 'has-error' : '' }}">
                            <strong>Relationship : </strong>
                            {!! Form::text('relationship_1', $user['relationship_1'], array('id'=>'relationship_1','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '9'  )) !!}
                            @if ($errors->has('relationship_1'))
                            <span class="help-block">
                                <strong>{{ $errors->first('relationship_1') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('occupation_1') ? 'has-error' : '' }}">
                            <strong>Occupation : </strong>
                            {!! Form::text('occupation_1', $user['occupation_1'], array('id'=>'occupation_1','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '10'  )) !!}
                            @if ($errors->has('occupation_1'))
                            <span class="help-block">
                                <strong>{{ $errors->first('occupation_1') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('contact_no_1') ? 'has-error' : '' }}">
                            <strong>Contact Number: </strong>
                            {!! Form::text('contact_no_1', $user['contact_no_1'], array('id'=>'contact_no_1','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '11'  )) !!}
                            @if ($errors->has('contact_no_1'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_no_1') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{--Second family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_2') ? 'has-error' : '' }}">
                            {!! Form::text('name_2', $user['name_2'],array('id'=>'name_2','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '12' )) !!}
                            @if ($errors->has('name_2'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name_2') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('relationship_2') ? 'has-error' : '' }}">
                            {!! Form::text('relationship_2', $user['relationship_2'], array('id'=>'relationship_2','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '13'  )) !!}
                            @if ($errors->has('relationship_2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('relationship_2') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('occupation_2') ? 'has-error' : '' }}">
                            {!! Form::text('occupation_2', $user['occupation_2'], array('id'=>'occupation_2','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '14'  )) !!}
                            @if ($errors->has('occupation_2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('occupation_2') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('contact_no_2') ? 'has-error' : '' }}">
                            {!! Form::text('contact_no_2', $user['contact_no_2'], array('id'=>'contact_no_2','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '15'  )) !!}
                            @if ($errors->has('contact_no_2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_no_2') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{--Third family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_3') ? 'has-error' : '' }}">
                            {!! Form::text('name_3', $user['name_3'],array('id'=>'name_3','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '16' )) !!}
                            @if ($errors->has('name_3'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name_3') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('relationship_3') ? 'has-error' : '' }}">
                            {!! Form::text('relationship_3', $user['relationship_3'], array('id'=>'relationship_3','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '17'  )) !!}
                            @if ($errors->has('relationship_3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('relationship_3') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('occupation_3') ? 'has-error' : '' }}">
                            {!! Form::text('occupation_3', $user['occupation_3'], array('id'=>'occupation_3','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '18'  )) !!}
                            @if ($errors->has('occupation_3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('occupation_3') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('contact_no_3') ? 'has-error' : '' }}">
                            {!! Form::text('contact_no_3', $user['contact_no_3'], array('id'=>'contact_no_3','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '19'  )) !!}
                            @if ($errors->has('contact_no_3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_no_3') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{--Fourth family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_4') ? 'has-error' : '' }}">
                            {!! Form::text('name_4', $user['name_4'],array('id'=>'name_4','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '20' )) !!}
                            @if ($errors->has('name_4'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name_4') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('relationship_4') ? 'has-error' : '' }}">
                            {!! Form::text('relationship_4', $user['relationship_4'], array('id'=>'relationship_4','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '21'  )) !!}
                            @if ($errors->has('relationship_4'))
                            <span class="help-block">
                                <strong>{{ $errors->first('relationship_4') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('occupation_4') ? 'has-error' : '' }}">
                            {!! Form::text('occupation_4', $user['occupation_4'], array('id'=>'occupation_4','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '22'  )) !!}
                            @if ($errors->has('occupation_4'))
                            <span class="help-block">
                                <strong>{{ $errors->first('occupation_4') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('contact_no_4') ? 'has-error' : '' }}">
                            {!! Form::text('contact_no_4', $user['contact_no_4'], array('id'=>'contact_no_4','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '23'  )) !!}
                            @if ($errors->has('contact_no_4'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_no_4') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{--Fifth family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_5') ? 'has-error' : '' }}">
                            {!! Form::text('name_5', $user['name_5'],array('id'=>'name_5','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '24' )) !!}
                            @if ($errors->has('name_5'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name_5') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('relationship_5') ? 'has-error' : '' }}">
                            {!! Form::text('relationship_5', $user['relationship_5'], array('id'=>'relationship_5','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '25'  )) !!}
                            @if ($errors->has('relationship_5'))
                            <span class="help-block">
                                <strong>{{ $errors->first('relationship_5') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('occupation_5') ? 'has-error' : '' }}">
                            {!! Form::text('occupation_5', $user['occupation_5'], array('id'=>'occupation_5','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '26'  )) !!}
                            @if ($errors->has('occupation_5'))
                            <span class="help-block">
                                <strong>{{ $errors->first('occupation_5') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('contact_no_5') ? 'has-error' : '' }}">
                            {!! Form::text('contact_no_5', $user['contact_no_5'], array('id'=>'contact_no_5','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '27'  )) !!}
                            @if ($errors->has('contact_no_5'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_no_5') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Bank Details</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
                            <strong>Bank Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('bank_name',$user['bank_name'],array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '28' )) !!}
                            @if ($errors->has('bank_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('account_no') ? 'has-error' : '' }}">
                            <strong>Account Number: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('account_no',$user['acc_no'], array('id'=>'account_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '29'  )) !!}
                            @if ($errors->has('account_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('account_no') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('user_full_name') ? 'has-error' : '' }}">
                            <strong>Full Name: </strong>
                            {!! Form::text('user_full_name',$user['user_full_name'], array('id'=>'user_full_name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '30'  )) !!}
                            @if ($errors->has('user_full_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_full_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                            <div class="form-group {{ $errors->has('branch_name') ? 'has-error' : '' }}">
                               <strong>Branch Name: </strong>
                                {!! Form::text('branch_name',$user['branch_name'], array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '31'  )) !!}
                                @if ($errors->has('branch_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('branch_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ifsc') ? 'has-error' : '' }}">
                                <strong>IFSC Code: </strong>
                                {!! Form::text('ifsc',$user['ifsc_code'], array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '32' )) !!}
                                @if ($errors->has('ifsc'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ifsc') }}</strong>
                                </span>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@if($isSuperAdmin || $isAccountant)
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Salary Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('fixed_salary') ? 'has-error' : '' }}">
                            <strong>Fixed Salary: </strong>
                            {!! Form::text('fixed_salary',$user['salary'],array('id'=>'fixed_salary','placeholder' => 'Fixed Saraly','class' => 'form-control', 'tabindex' => '33' )) !!}
                            @if ($errors->has('fixed_salary'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fixed_salary') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Attachment Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Upload Documents:</strong>
                    <input type="file" name="upload_documents[]" multiple class="form-control" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("#editprofile").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "email": {
                        required: true,
                    },
                    "contact": {
                        required: true,
                    },
                    "bank_name": {
                        required: true,
                    },
                    "account_no": {
                        required: true,
                    },
                },
                messages: {
                    "name": {
                        required: "Name is required."
                    },
                    "email": {
                        required: "Email is required.",
                    },
                    "contact": {
                        required: "Contact Number is required.",
                    },
                    "bank_name": {
                        required: "Bank Name is required.",
                    },
                    "account_no": {
                        required: "Account Number is required.",
                    },
                }
            });

            $("#date_of_joining").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
            });

            $("#date_of_birth").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
            });

            $("#date_of_anni").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
            });

            $("#date_of_exit").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
            });


            $("#upload_img").change(function(){
                $('#upload_images_div').html("");
                var total_file=document.getElementById("upload_img").files.length;
                for(var i=0;i<total_file;i++){
                    $('#default_image').hide();
                    $('#upload_images_div').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' height='150px' width='150px'>");
                    $('#upload_images_div').append("<br/><br/>");
                }
            });
        });
    
    </script>
@endsection