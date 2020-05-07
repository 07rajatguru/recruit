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
            <a class="btn btn-primary" href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>

    {!! Form::open(array('route' => ['users.profilestore',$user_id],'method'=>'POST','id' => 'editprofile','files' => true,'autocomplete' => 'off')) !!}

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
                                {!! Form::text('name',isset($user['name']) ? $user['name'] : null,array('id'=>'name','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Official Email: <span class = "required_fields">*</span> </strong>
                                @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                    {!! Form::text('email',isset($user['email']) ? $user['email'] : null, array('id'=>'email','placeholder' => 'Official Email','class' => 'form-control','tabindex' => '1')) !!}
                                @else
                                    {!! Form::text('email',isset($user['email']) ? $user['email'] : null, array('id'=>'email','placeholder' => 'Official Email','class' => 'form-control','disabled','tabindex' => '1')) !!}
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Official Gmail: <span class = "required_fields">*</span> </strong>
                                @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                    {!! Form::text('semail',isset($user['semail']) ? $user['semail'] : null, array('id'=>'semail','placeholder' => 'Official Gmail','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @else
                                    {!! Form::text('semail',isset($user['semail']) ? $user['semail'] : null,array('id'=>'semail','placeholder' => 'Official Gmail','class' => 'form-control','disabled','tabindex' => '2' )) !!}
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Personal Email:  </strong>
                                {!! Form::text('personal_email',isset($user['personal_email']) ? $user['personal_email'] : null, array('id'=>'personal_email','placeholder' => 'Personal Email','class' => 'form-control','tabindex' => '3')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                                <strong>Birth Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_birth',isset($user['birth_date']) ? $user['birth_date'] : null, array('id'=>'date_of_birth','placeholder' => 'Birth Date','class' => 'form-control','tabindex' => '4')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('blood_group') ? 'has-error' : '' }}">
                                <strong>Blood Group: <span class = "required_fields">*</span></strong>
                                {!! Form::text('blood_group',isset($user['blood_group']) ? $user['blood_group'] : null, array('id'=>'blood_group','placeholder' => 'Blood Group','class' => 'form-control', 'tabindex' => '13' )) !!}
                            </div>

                            <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                                <strong>Personal Contact Number: <span class = "required_fields">*</span> </strong>
                                {!! Form::number('contact',isset($user['contact_number']) ? $user['contact_number'] : null, array('id'=>'contact','placeholder' => 'Personal Contact Number','class' => 'form-control','tabindex' => '7')) !!}
                                @if ($errors->has('contact'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Official Contact Number: </strong>
                                {!! Form::number('contact_no_official',isset($user['contact_no_official']) ? $user['contact_no_official'] : null, array('id'=>'contact_no_official','placeholder' => 'Official Contact Number','class' => 'form-control','tabindex' => '8')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Current Address: </strong>
                                {!! Form::textarea('current_address',isset($user['current_address']) ? $user['current_address'] : null, array('id'=>'current_address' ,'placeholder' => 'Current Address','class' => 'form-control','tabindex' => '9','rows' => '5')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Permanent Address: </strong>
                                {!! Form::textarea('permanent_address',isset($user['permanent_address']) ? $user['permanent_address'] : null, array('id'=>'permanent_address' ,'placeholder' => 'Permanent Address','class' => 'form-control','tabindex' => '13','rows' => '5')) !!}
                            </div>

                            <div class="form-group">
                                <strong>UAN No. : </strong>
                                {!! Form::text('uan_no',isset($user['uan_no']) ? $user['uan_no'] : null,array('id'=>'uan_no','placeholder' => 'UAN No.','class' => 'form-control','tabindex' => '13','rows' => '5')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group" id="default_image">
                                @if($user['type'] == "Photo")
                                    <img src= "../../{!!$user['photo']!!}" style="height: 150px;width: 150px;border-radius: 50%;" />
                                @else
                                    <img src= "../../uploads/User_Default.jpg" style="height: 150px;width: 150px;border-radius: 50%;" />
                                @endif
                            </div>

                            @if($user['edit_photo'] == '1' || $isSuperAdmin || $isAccountant || $isOfficeAdmin)
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
                                                <input type="file" name="image" id="upload_img" accept="image/x-png,image/gif,image/jpeg" />
                                                </span>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            @endif
                       
                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation: </strong>
                                @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                    {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','tabindex' => '10')) !!}
                                @else
                                    {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','disabled','tabindex' => '10')) !!}
                                @endif
                                @if ($errors->has('designation'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('designation') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                <strong>Joining Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_joining', isset($user['join_date']) ? $user['join_date'] : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '5')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                <strong>Confimation Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_joining', isset($user['join_date']) ? $user['join_date'] : null, array('id'=>'date_of_joining','placeholder' => 'Confimation Date','class' => 'form-control','tabindex' => '5')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('date_of_anni') ? 'has-error' : '' }}">
                                <strong>Anniversary Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_anni',isset($user['anni_date']) ? $user['anni_date'] : null, array('id'=>'date_of_anni','placeholder' => 'Anniversary Date','class' => 'form-control','tabindex' => '12')) !!}
                                </div>
                            </div>

                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                            <div class="form-group {{ $errors->has('date_of_exit') ? 'has-error' : '' }}">
                                <strong>Exit Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_exit',isset($user['exit_date']) ? $user['exit_date'] : null, array('id'=>'date_of_exit','placeholder' => 'Exit Date','class' => 'form-control','tabindex' => '12')) !!}
                                </div>
                            </div> 
                            @endif

                            <div class="form-group">
                                <strong>Marital Status:  </strong>
                                {!! Form::select('maritalStatus', $maritalStatus,null, array('id'=>'maritalStatus','class' => 'form-control', 'tabindex' => '4' )) !!}
                            </div>

                             <div class="form-group ">
                                <strong>Select Gender:</strong>
                                {!! Form::select('gender', $gender,null, array('id'=>'gender','class' => 'form-control', 'tabindex' => '6' )) !!}
                            </div>

                            <div class="form-group">
                                <strong>Hobbies: </strong>
                                {!! Form::number('hobbies',isset($user['hobbies']) ? $user['hobbies'] : null, array('id'=>'hobbies','placeholder' => 'Hobbies','class' => 'form-control','tabindex' => '8')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Interests: </strong>
                                {!! Form::number('interests',isset($user['interests']) ? $user['interests'] : null, array('id'=>'interests','placeholder' => 'Interests','class' => 'form-control','tabindex' => '8')) !!}
                            </div>

                            <div class="form-group">
                                <strong>ESIC No. : </strong>
                                {!! Form::text('esic_no',isset($user['esic_no']) ? $user['esic_no'] : null,array('id'=>'esic_no','placeholder' => 'ESIC No.','class' => 'form-control','tabindex' => '13','rows' => '5')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="">
                            <div class="form-group">
                                <strong>Signature : </strong>
                                {!! Form::textarea('signature',$user['signature'], array('id'=>'signature','placeholder' => 'Signature','class' => 'form-control','tabindex' => '14','rows' => '4')) !!}
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Family Details</h3>
                <span class = "required_fields"><b>(Minimum two details are required)</b></span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                {{--First family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_1') ? 'has-error' : '' }}">
                            <strong>Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('name_1', $user['name_1'],array('id'=>'name_1','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '15' )) !!}
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
                            <strong>Relationship : <span class = "required_fields">*</span></strong>
                            {!! Form::text('relationship_1', $user['relationship_1'], array('id'=>'relationship_1','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '16'  )) !!}
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
                            <strong>Occupation : <span class = "required_fields">*</span></strong>
                            {!! Form::text('occupation_1', $user['occupation_1'], array('id'=>'occupation_1','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '17'  )) !!}
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
                            <strong>Contact Number: <span class = "required_fields">*</span></strong>
                            {!! Form::number('contact_no_1', $user['contact_no_1'], array('id'=>'contact_no_1','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '18'  )) !!}
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
                            {!! Form::text('name_2', $user['name_2'],array('id'=>'name_2','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '19' )) !!}
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
                            {!! Form::text('relationship_2', $user['relationship_2'], array('id'=>'relationship_2','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '20'  )) !!}
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
                            {!! Form::text('occupation_2', $user['occupation_2'], array('id'=>'occupation_2','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '21'  )) !!}
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
                            {!! Form::number('contact_no_2', $user['contact_no_2'], array('id'=>'contact_no_2','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '22'  )) !!}
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
                            {!! Form::text('name_3', $user['name_3'],array('id'=>'name_3','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '23' )) !!}
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
                            {!! Form::text('relationship_3', $user['relationship_3'], array('id'=>'relationship_3','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '24'  )) !!}
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
                            {!! Form::text('occupation_3', $user['occupation_3'], array('id'=>'occupation_3','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '25'  )) !!}
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
                            {!! Form::number('contact_no_3', $user['contact_no_3'], array('id'=>'contact_no_3','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '26'  )) !!}
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
                            {!! Form::text('name_4', $user['name_4'],array('id'=>'name_4','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '27' )) !!}
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
                            {!! Form::text('relationship_4', $user['relationship_4'], array('id'=>'relationship_4','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '28'  )) !!}
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
                            {!! Form::text('occupation_4', $user['occupation_4'], array('id'=>'occupation_4','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '29'  )) !!}
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
                            {!! Form::number('contact_no_4', $user['contact_no_4'], array('id'=>'contact_no_4','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '30'  )) !!}
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
                            {!! Form::text('name_5', $user['name_5'],array('id'=>'name_5','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '31' )) !!}
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
                            {!! Form::text('relationship_5', $user['relationship_5'], array('id'=>'relationship_5','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '32'  )) !!}
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
                            {!! Form::text('occupation_5', $user['occupation_5'], array('id'=>'occupation_5','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '33'  )) !!}
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
                            {!! Form::number('contact_no_5', $user['contact_no_5'], array('id'=>'contact_no_5','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '34')) !!}
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
                        <div class="form-group">
                            <strong>Bank Name: <span class = "required_fields">*</span> </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('bank_name',$user['bank_name'],array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '35' )) !!}
                            @else
                                {!! Form::text('bank_name',$user['bank_name'],array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control','tabindex' => '35','disabled')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Account Number: <span class = "required_fields">*</span> </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('account_no',$user['acc_no'], array('id'=>'account_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '36')) !!}
                            @else
                                {!! Form::text('account_no',$user['acc_no'], array('id'=>'account_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '36','disabled')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Full Name: </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('user_full_name',$user['user_full_name'], array('id'=>'user_full_name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '37')) !!}
                            @else
                                {!! Form::text('user_full_name',$user['user_full_name'], array('id'=>'user_full_name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '37','disabled')) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>Branch Name: </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('branch_name',$user['branch_name'], array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '38')) !!}
                            @else
                                {!! Form::text('branch_name',$user['branch_name'], array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '38','disabled')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>IFSC Code: </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('ifsc',$user['ifsc_code'], array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '39')) !!}
                            @else
                                {!! Form::text('ifsc',$user['ifsc_code'], array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '39','disabled')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Payment Mode: <span class = "required_fields">*</span> </strong>
                            @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
                                {!! Form::text('payment_mode',null, array('id'=>'payment_mode','placeholder' => 'Payment Mode','class' => 'form-control', 'tabindex' => '40')) !!}
                            @else
                                {!! Form::text('payment_mode',null, array('id'=>'payment_mode','placeholder' => 'Payment Mode','class' => 'form-control', 'tabindex' => '40','disabled')) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isSuperAdmin || $isAccountant || $isOfficeAdmin)
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Salary Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Fixed Salary : (Monthly)</strong>
                            {!! Form::number('fixed_salary',$user['salary'],array('id'=>'fixed_salary','placeholder' => 'Fixed Salary','class' => 'form-control', 'tabindex' => '41','onfocusout' => 'countTotalSalary();')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Performance Bonus : </strong>
                            {!! Form::number('performance_bonus',$user['performance_bonus'],array('id'=>'performance_bonus','placeholder' => 'Performance Bonus','class' => 'form-control', 'tabindex' => '42','onfocusout' => 'countTotalSalary();')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Total Salary : </strong>
                            {!! Form::number('total_salary',$user['total_salary'],array('id'=>'total_salary','placeholder' => 'Total Salary','class' => 'form-control', 'tabindex' => '43')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- <div class="col-xs-12 col-sm-12 col-md-12">
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
    </div> -->

 
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Attachment Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Select type</strong>
                        {!! Form::select('users_upload_type', $users_upload_type,null, array('id'=>'users_upload_type','class' => 'form-control','onchange' => "displayDoc();")) !!}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 singledoc" style="display:none;">
                    <div class="form-group">
                        <strong>Upload :</strong>
                        <input type="file" name="upload_documents[]" class="form-control" />
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 multidoc" style="display:none;">
                    <div class="form-group">
                        <strong>Upload Documents:</strong>
                        <input type="file" name="upload_documents[]" multiple class="form-control" />
                    </div>
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
  <script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">

        jQuery(document).ready(function () {

            //$("#signature").wysihtml5();

            displayDoc();

            $("#users_upload_type").select2();

            CKEDITOR.replace( 'signature', 
            {
                filebrowserUploadUrl: '{{ route('upload.signature',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js'
            });

            CKEDITOR.on('dialogDefinition', function( ev )
            {
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

            $("#editprofile").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "email": {
                        required: true,
                    },
                    "semail": {
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
                    "payment_mode": {
                        required: true,
                    },
                    "blood_group": {
                        required: true,
                    },
                    "name_1": {
                        required: true,
                    },
                    "relationship_1": {
                        required: true,
                    },
                    "occupation_1": {
                        required: true,
                    },
                    "contact_no_1": {
                        required: true,
                    },
                    "name_2": {
                        required: true,
                    },
                    "relationship_2": {
                        required: true,
                    },
                    "occupation_2": {
                        required: true,
                    },
                    "contact_no_2": {
                        required: true,
                    },
                },
                messages: {
                    "name": {
                        required: "Name is Required."
                    },
                    "email": {
                        required: "Official email is Required.",
                    },
                    "semail": {
                        required: "Official Gmail is Required.",
                    },
                    "contact": {
                        required: "Personal Contact Number is Required.",
                    },
                    "bank_name": {
                        required: "Bank Name is Required.",
                    },
                    "account_no": {
                        required: "Account Number is Required.",
                    },
                    "payment_mode": {
                        required: "Payment Mode is Required.",
                    },
                    "blood_group": {
                        required: "Blood Group is Required.",
                    },
                    "name_1": {
                        required: "Name is Required.",
                    },
                    "relationship_1": {
                        required: "Relationship is Required.",
                    },
                    "occupation_1": {
                        required: "Occupation is Required.",
                    },
                    "contact_no_1": {
                        required: "Contact Number is Required.",
                    },
                    "name_2": {
                        required: "Name is Required.",
                    },
                    "relationship_2": {
                        required: "Relationship is Required.",
                    },
                    "occupation_2": {
                        required: "Occupation is Required.",
                    },
                    "contact_no_2": {
                        required: "Contact Number is Required.",
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


            $("#upload_img").change(function(e){
                if( !e ){
                    e = window.event;
                }
                $('#upload_images_div').html("");
                var total_file=document.getElementById("upload_img").files.length;
                for(var i=0;i<total_file;i++){
                    $('#default_image').hide();
                    $('#upload_images_div').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' style='height: 150px;width: 150px;border-radius: 50%;'>");
                    $('#upload_images_div').append("<br/><br/>");
                }
            });
        });
    
    function displayDoc() {

        var doc_type = $("#users_upload_type").val();

        if(doc_type == 'Others')
        {
            $(".multidoc").show();
            $(".singledoc").hide();
        }
        else
        {
            $(".multidoc").hide();
            $(".singledoc").show();
        }
    }

    function countTotalSalary() {

        var fixed_salary = $("#fixed_salary").val();
        var performance_bonus = $("#performance_bonus").val();
        var total_salary = parseFloat(fixed_salary) + parseFloat(performance_bonus);
        $("#total_salary").val(total_salary);
    }
    </script>
@endsection