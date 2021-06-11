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

                            <div class="form-group">
                                <strong>Employee ID : <span class = "required_fields">*</span> </strong>
                                {!! Form::text('employee_id',isset($user['employee_id']) ? $user['employee_id'] : null,array('id'=>'employee_id','placeholder' => 'Employee ID','class' => 'form-control', 'tabindex' => '1','readonly' => 'true')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>Name : <span class = "required_fields">*</span> </strong>
                                {!! Form::text('name',isset($user['name']) ? $user['name'] : null,array('id'=>'name','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1','disabled')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Official Email : <span class = "required_fields">*</span> </strong>
                                @permission(('edit-user-profile'))
                                    {!! Form::text('email',isset($user['email']) ? $user['email'] : null, array('id'=>'email','placeholder' => 'Official Email','class' => 'form-control','tabindex' => '2')) !!}
                                @else
                                    {!! Form::text('email',isset($user['email']) ? $user['email'] : null, array('id'=>'email','placeholder' => 'Official Email','class' => 'form-control','tabindex' => '2','readonly' => 'true')) !!}
                                @endpermission
                            </div>

                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Official Gmail : <span class = "required_fields">*</span> </strong>
                                @permission(('edit-user-profile'))
                                    {!! Form::text('semail',isset($user['semail']) ? $user['semail'] : null, array('id'=>'semail','placeholder' => 'Official Gmail','class' => 'form-control', 'tabindex' => '3' )) !!}
                                @else
                                    {!! Form::text('semail',isset($user['semail']) ? $user['semail'] : null,array('id'=>'semail','placeholder' => 'Official Gmail','class' => 'form-control','tabindex' => '3','readonly' => 'true')) !!}
                                @endpermission
                            </div>

                            <div class="form-group">
                                <strong>Personal Email : </strong>
                                {!! Form::text('personal_email',isset($user['personal_email']) ? $user['personal_email'] : null, array('id'=>'personal_email','placeholder' => 'Personal Email','class' => 'form-control','tabindex' => '5')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                                <strong>Birth Date : <span class = "required_fields">*</span></strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_birth',isset($user['date_of_birth']) ? $user['date_of_birth'] : null, array('id'=>'date_of_birth','placeholder' => 'Birth Date','class' => 'form-control','tabindex' => '7')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('blood_group') ? 'has-error' : '' }}">
                                <strong>Blood Group : <span class = "required_fields">*</span></strong>
                                {!! Form::text('blood_group',isset($user['blood_group']) ? $user['blood_group'] : null, array('id'=>'blood_group','placeholder' => 'Blood Group','class' => 'form-control', 'tabindex' => '9' )) !!}
                            </div>

                            <div class="form-group {{ $errors->has('contact_number') ? 'has-error' : '' }}">
                                <strong>Personal Contact Number : <span class = "required_fields">*</span> </strong>
                                {!! Form::number('contact_number',isset($user['contact_number']) ? $user['contact_number'] : null, array('id'=>'contact_number','placeholder' => 'Personal Contact Number','class' => 'form-control','tabindex' => '11')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Official Contact Number : </strong>
                                {!! Form::number('contact_no_official', isset($user['contact_no_official']) ? $user['contact_no_official'] : null, array('id'=>'contact_no_official' ,'placeholder' => 'Official Contact Number','class' => 'form-control','tabindex' => '13')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Current Address : </strong>
                                {!! Form::textarea('current_address',isset($user['current_address']) ? $user['current_address'] : null, array('id'=>'current_address' ,'placeholder' => 'Current Address','class' => 'form-control','tabindex' => '15','rows' => '5')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Permanent Address : </strong>
                                {!! Form::textarea('permanent_address',isset($user['permanent_address']) ? $user['permanent_address'] : null, array('id'=>'permanent_address' ,'placeholder' => 'Permanent Address','class' => 'form-control','tabindex' => '17','rows' => '5')) !!}
                            </div>

                            @permission(('edit-user-profile'))
                                <div class="form-group">
                                    <strong>UAN No : </strong>
                                    {!! Form::text('uan_no',isset($user['uan_no']) ? $user['uan_no'] : null,array('id'=>'uan_no','placeholder' => 'UAN No.','class' => 'form-control','tabindex' => '19','rows' => '5')) !!}
                                </div>
                            @endpermission
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

                            @if($user['edit_photo'] == '1')
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
                                                <input type="file" name="image" id="upload_img" accept="image/x-png,image/gif,image/jpeg" tabindex="4" />
                                                </span>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            @else
                                @permission(('edit-user-profile'))
                                    <div class="form-group" id="image_div">
                                        <div class="form-group file_input_redesign upload_img1">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div style="width: 22px; height: 70px;display:none;" class="fileinput-new thumbnail" data-trigger="fileinput">
                                                    <span>Select Image</span>
                                                </div>
                                                <div id = "upload_images_div"></div>
                                                <div>
                                                    <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">Select Profile Photo</span>
                                                        <input type="file" name="image" id="upload_img" accept="image/x-png,image/gif,image/jpeg" tabindex="4" />
                                                    </span>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                @endpermission
                            @endif

                            @permission(('edit-user-profile'))
                                <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                    <strong>Designation : <span class = "required_fields">*</span> </strong>
                                    {!! Form::text('designation',isset($user['designation']) ? $user['designation'] : null, array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','tabindex' => '6','disabled')) !!}
                                </div>

                                <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                    <strong>Joining Date : <span class = "required_fields">*</span> 
                                    </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_joining', isset($user['date_of_joining']) ? $user['date_of_joining'] : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '8')) !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('date_of_confirmation') ? 'has-error' : '' }}">
                                    <strong>Confimation Date : <span class = "required_fields">*</span>
                                    </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_confirmation', isset($user['date_of_confirmation']) ? $user['date_of_confirmation'] : null, array('id'=>'date_of_confirmation','placeholder' => 'Confimation Date','class' => 'form-control','tabindex' => '10')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <strong>Marriage Anniversary Date : </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_anniversary',isset($user['date_of_anniversary']) ? $user['date_of_anniversary'] : null, array('id'=> 'date_of_anniversary','placeholder' => 'Marriage Anniversary Date','class' => 'form-control','tabindex' => '12')) !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('date_of_exit') ? 'has-error' : '' }}">
                                    <strong>Exit Date : </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_exit',isset($user['date_of_exit']) ? $user['date_of_exit'] : null, array('id'=>'date_of_exit','placeholder' => 'Exit Date','class' => 'form-control','tabindex' => '14')) !!}
                                    </div>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                    <strong>Designation : <span class = "required_fields">*</span>
                                    </strong>
                                    {!! Form::text('designation',isset($user['designation']) ? $user['designation'] : null, array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','tabindex' => '6','disabled')) !!}
                                </div>

                                <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                    <strong>Joining Date : <span class = "required_fields">*</span>
                                    </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_joining', isset($user['date_of_joining']) ? $user['date_of_joining'] : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '8','disabled')) !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('date_of_confirmation') ? 'has-error' : '' }}">
                                    <strong>Confimation Date : <span class = "required_fields">*</span>
                                    </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_confirmation', isset($user['date_of_confirmation']) ? $user['date_of_confirmation'] : null, array('id'=>'date_of_confirmation','placeholder' => 'Confimation Date','class' => 'form-control','tabindex' => '10','disabled')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <strong>Marriage Anniversary Date : </strong>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('date_of_anniversary',isset($user['date_of_anniversary']) ? $user['date_of_anniversary'] : null, array('id'=> 'date_of_anniversary','placeholder' => 'Marriage Anniversary Date','class' => 'form-control','tabindex' => '12','disabled')) !!}
                                    </div>
                                </div>
                            @endpermission

                            <div class="form-group">
                                <strong>Marital Status : </strong>
                                {!! Form::select('marital_status', $maritalStatus,isset($user['marital_status']) ? $user['marital_status'] : null,array('id'=>'marital_status','class' => 'form-control', 'tabindex' => '16' )) !!}
                            </div>

                             <div class="form-group ">
                                <strong>Select Gender : </strong>
                                {!! Form::select('gender', $gender,isset($user['gender']) ? $user['gender'] : null,array('id'=>'gender','class' => 'form-control', 'tabindex' => '16' )) !!}
                            </div>

                            <div class="form-group">
                                <strong>Hobbies : </strong>
                                {!! Form::text('hobbies',isset($user['hobbies']) ? $user['hobbies'] : null, array('id'=>'hobbies','placeholder' => 'Hobbies','class' => 'form-control','tabindex' => '18')) !!}
                            </div>

                            <div class="form-group">
                                <strong>Interests : </strong>
                                {!! Form::text('interests',isset($user['interests']) ? $user['interests'] : null, array('id'=>'interests','placeholder' => 'Interests','class' => 'form-control','tabindex' => '18')) !!}
                            </div>

                            @permission(('edit-user-profile'))
                                <div class="form-group">
                                    <strong>ESIC No : </strong>
                                    {!! Form::text('esic_no',isset($user['esic_no']) ? $user['esic_no'] : null,array('id'=>'esic_no','placeholder' => 'ESIC No.','class' => 'form-control','tabindex' => '20','rows' => '5')) !!}
                                </div>
                            @endpermission
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="">
                            <div class="form-group">
                                <strong>Signature : </strong>
                                {!! Form::textarea('signature',isset($user['signature']) ? $user['signature'] : null,array('id'=>'signature','placeholder' => 'Signature','class' => 'form-control','tabindex' => '21','rows' => '4')) !!}
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
                <span class = "required_fields"><b>(Minimum two Details are Required.)</b></span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                {{--First family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('name_1') ? 'has-error' : '' }}">
                            <strong>Name : <span class = "required_fields">*</span></strong>
                            {!! Form::text('name_1',isset($user['name_1']) ? $user['name_1'] : null,array('id'=>'name_1','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '22')) !!}
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
                            {!! Form::text('relationship_1',isset($user['relationship_1']) ? $user['relationship_1'] : null, array('id'=>'relationship_1','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '23')) !!}
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
                            {!! Form::text('occupation_1',isset($user['occupation_1']) ? $user['occupation_1'] : null,array('id'=>'occupation_1','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '24')) !!}
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
                            <strong>Contact Number : <span class = "required_fields">*</span></strong>
                            {!! Form::number('contact_no_1',isset($user['contact_no_1']) ? $user['contact_no_1'] : null,array('id'=>'contact_no_1','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '25')) !!}
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
                            {!! Form::text('name_2',isset($user['name_2']) ? $user['name_2'] : null,array('id'=>'name_2','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '26')) !!}
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
                            {!! Form::text('relationship_2',isset($user['relationship_2']) ? $user['relationship_2'] : null, array('id'=>'relationship_2','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '27')) !!}
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
                            {!! Form::text('occupation_2',isset($user['occupation_2']) ? $user['occupation_2'] : null, array('id'=>'occupation_2','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '28')) !!}
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
                            {!! Form::number('contact_no_2',isset($user['contact_no_2']) ? $user['contact_no_2'] : null, array('id'=>'contact_no_2','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '29')) !!}
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
                        <div class="form-group">
                            {!! Form::text('name_3',isset($user['name_3']) ? $user['name_3'] : null,array('id'=>'name_3','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '30')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('relationship_3',isset($user['relationship_3']) ? $user['relationship_3'] : null,array('id'=>'relationship_3','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '31')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group}">
                            {!! Form::text('occupation_3',isset($user['occupation_3']) ? $user['occupation_3'] : null, array('id'=>'occupation_3','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '32')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::number('contact_no_3',isset($user['contact_no_3']) ? $user['contact_no_3'] : null,array('id'=>'contact_no_3','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '33')) !!}
                        </div>
                    </div>
                </div>
                
                {{--Fourth family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('name_4',isset($user['name_4']) ? $user['name_4'] : null,array('id'=>'name_4','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '34')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('relationship_4',isset($user['relationship_4']) ? $user['relationship_4'] : null,array('id'=>'relationship_4','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '35')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('occupation_4',isset($user['occupation_4']) ? $user['occupation_4'] : null,array('id'=>'occupation_4','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '36')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::number('contact_no_4',isset($user['contact_no_4']) ? $user['contact_no_4'] : null,array('id'=>'contact_no_4','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '37')) !!}
                        </div>
                    </div>
                </div>
                
                {{--Fifth family member details--}}
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('name_5',isset($user['name_5']) ? $user['name_5'] : null,array('id'=>'name_5','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '38')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('relationship_5',isset($user['relationship_5']) ? $user['relationship_5'] : null,array('id'=>'relationship_5','placeholder' => 'Relationship','class' => 'form-control', 'tabindex' => '39')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::text('occupation_5',isset($user['occupation_5']) ? $user['occupation_5'] : null,array('id'=>'occupation_5','placeholder' => 'Occupation','class' => 'form-control', 'tabindex' => '40')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group">
                            {!! Form::number('contact_no_5',isset($user['contact_no_5']) ? $user['contact_no_5'] : null,array('id'=>'contact_no_5','placeholder' => 'Contact No','class' => 'form-control', 'tabindex' => '41')) !!}
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
                            <strong>Bank Name : <span class = "required_fields">*</span> </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('bank_name',isset($user['bank_name']) ? $user['bank_name'] : null,array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '42' )) !!}
                            @else
                                {!! Form::text('bank_name',isset($user['bank_name']) ? $user['bank_name'] : null,array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control','tabindex' => '42','disabled')) !!}
                            @endpermission
                        </div>

                        <div class="form-group">
                            <strong>Account Number : <span class = "required_fields">*</span> </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('acc_no',isset($user['acc_no']) ? $user['acc_no'] : null, array('id'=>'acc_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '44')) !!}
                            @else
                                {!! Form::text('acc_no',isset($user['acc_no']) ? $user['acc_no'] : null, array('id'=>'acc_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '44','disabled')) !!}
                            @endpermission
                        </div>

                        <div class="form-group">
                            <strong>Full Name : </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('user_full_name',isset($user['user_full_name']) ? $user['user_full_name'] : null,array('id'=>'user_full_name' ,'placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '46')) !!}
                            @else
                                {!! Form::text('user_full_name',isset($user['user_full_name']) ? $user['user_full_name'] : null, array('id'=>'user_full_name' ,'placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '46','disabled')) !!}
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>Branch Name : </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('branch_name',isset($user['branch_name']) ? $user['branch_name'] : null,array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '43')) !!}
                            @else
                                {!! Form::text('branch_name',isset($user['branch_name']) ? $user['branch_name'] : null,array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '43','disabled')) !!}
                            @endpermission
                        </div>

                        <div class="form-group">
                            <strong>IFSC Code : </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('ifsc_code',isset($user['ifsc_code']) ? $user['ifsc_code'] : null,array('id'=>'ifsc_code','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '45')) !!}
                            @else
                                {!! Form::text('ifsc_code',isset($user['ifsc_code']) ? $user['ifsc_code'] : null,array('id'=>'ifsc_code','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '45','disabled')) !!}
                            @endpermission
                        </div>

                        <div class="form-group">
                            <strong>Payment Mode : <span class = "required_fields">*</span> </strong>
                            @permission(('edit-user-profile'))
                                {!! Form::text('payment_mode',isset($user['payment_mode']) ? $user['payment_mode'] : null,array('id'=>'payment_mode','placeholder' => 'Payment Mode','class' => 'form-control', 'tabindex' => '47')) !!}
                            @else
                                {!! Form::text('payment_mode',isset($user['payment_mode']) ? $user['payment_mode'] : null,array('id'=>'payment_mode','placeholder' => 'Payment Mode','class' => 'form-control', 'tabindex' => '47','disabled')) !!}
                            @endpermission
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission(('display-salary'))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title"><a href="#" data-toggle="modal" data-target="#salaryModal">ADD Salary Details</a></h3>
                </div>
            </div>
        </div>
    @endpermission

    @permission(('display-salary'))
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
                            {!! Form::number('fixed_salary',isset($user['fixed_salary']) ? $user['fixed_salary'] : null,array('id'=>'fixed_salary','placeholder' => 'Fixed Salary','class' => 'form-control', 'tabindex' => '48','onfocusout' => 'countTotalSalary();')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Performance Bonus : </strong>
                            {!! Form::number('performance_bonus',isset($user['performance_bonus']) ? $user['performance_bonus'] : null,array('id'=>'performance_bonus', 'placeholder' => 'Performance Bonus','class' => 'form-control', 'tabindex' => '49','onfocusout' => 'countTotalSalary();')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Total Salary : </strong>
                            {!! Form::number('total_salary',isset($user['total_salary']) ? $user['total_salary'] : null,array('id'=>'total_salary','placeholder' => 'Total Salary','class' => 'form-control', 'tabindex' => '50')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endpermission

    <input type="hidden" name="doc_size" id="doc_size" value="{{ sizeof($user['doc']) }}">

    @if(isset($user['doc']) && sizeof($user['doc'])>0)
        @foreach($user['doc'] as $key=>$value)
            <?php

                if($value['type'] == 'SSC Marksheet') {
                    $ssc_m['url'] = $value['url'];
                    $ssc_m['name'] = $value['name'];
                }
                else if($value['type'] == 'HSC Marksheet') {
                    $hsc_m['url'] = $value['url'];
                    $hsc_m['name'] = $value['name'];
                }
                else if($value['type'] == 'University Certificate') {
                    $uni_c['url'] = $value['url'];
                    $uni_c['name'] = $value['name'];
                }
                else if($value['type'] == 'Offer Letter') {
                    $off_l['url'] = $value['url'];
                    $off_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Appraisal Letter') {
                    $a_l['url'] = $value['url'];
                    $a_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Relieving Letter') {
                    $rel_l['url'] = $value['url'];
                    $rel_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Resignation Letter') {
                    $reg_l['url'] = $value['url'];
                    $reg_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Appointment Letter') {
                    $app_l['url'] = $value['url'];
                    $app_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Experience Letter') {
                    $exp_l['url'] = $value['url'];
                    $exp_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Pay Slips') {
                    $pay_l['url'] = $value['url'];
                    $pay_l['name'] = $value['name'];
                }
                else if($value['type'] == 'Form - 26') {
                    $form_l['url'] = $value['url'];
                    $form_l['name'] = $value['name'];
                }
                else if($value['type'] == 'ID Proof') {
                    $id_p['url'] = $value['url'];
                    $id_p['name'] = $value['name'];
                }
                else if($value['type'] == 'Passport') {
                    $pass_port['url'] = $value['url'];
                    $pass_port['name'] = $value['name'];
                }
                else if($value['type'] == 'PAN Card') {
                    $pan_c['url'] = $value['url'];
                    $pan_c['name'] = $value['name'];
                }
                else if($value['type'] == 'Cancelled Cheque') {
                    $can_ch['url'] = $value['url'];
                    $can_ch['name'] = $value['name'];
                }
                else if($value['type'] == 'Address Proof') {
                    $add_p['url'] = $value['url'];
                    $add_p['name'] = $value['name'];
                }
                else if($value['type'] == 'Aadhar Card') {
                    $adr_c['url'] = $value['url'];
                    $adr_c['name'] = $value['name'];
                }
                else if($value['type'] == 'Resume') {
                    $resume['url'] = $value['url'];
                    $resume['name'] = $value['name'];
                }
                else if($value['type'] == 'Passport Photo') {
                    $pass_photo['url'] = $value['url'];
                    $pass_photo['name'] = $value['name'];
                }
            ?>
        @endforeach
    @endif

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="border: 1px dotted black;">
                    <h4>Educational Credentials : </h4><br/>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Xth (SSC) : </strong>
                            @if(isset($ssc_m['url']) && $ssc_m['url'] != '')
                                <a target="_blank" href="../{{ $ssc_m['url'] }}">{{ $ssc_m['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" name="ssc_marksheet" id="ssc_marksheet" tabindex="51" />
                                            </span>
                                            <div class="ssc_marksheet_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="ssc_marksheet" name="ssc_marksheet" class="form-control" tabindex="51" />
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>XIIth (HSC) : </strong>
                            @if(isset($hsc_m['url']) && $hsc_m['url'] != '')
                                <a target="_blank" href="../{{ $hsc_m['url'] }}">{{ $hsc_m['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="hsc_marksheet" name="hsc_marksheet" class="form-control" tabindex="52"/>
                                            </span>
                                            <div class="hsc_marksheet_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="hsc_marksheet" name="hsc_marksheet" class="form-control" tabindex="52"/>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>University Marks Sheets and Convocation certificate : 
                            <span class = "required_fields">*</span></strong>
                            @if(isset($uni_c['url']) && $uni_c['url'] != '')
                                <a target="_blank" href="../{{ $uni_c['url'] }}">{{ $uni_c['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" name="university_certificate" id="university_certificate" tabindex="53" />
                                            </span>
                                            <div class="university_certificate_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="university_certificate" name="university_certificate" class="form-control" tabindex="53"/>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="border: 1px dotted black;margin-top: 15px;">
                    <h4>Company Credentials of Previous Employment : </h4><br/>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Offer Letter : </strong>
                            @if(isset($off_l['url']) && $off_l['url'] != '')
                                <a target="_blank" href="../{{ $off_l['url'] }}">{{ $off_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="offer_letter" name="offer_letter" class="form-control" tabindex="54"/>
                                            </span>
                                            <div class="offer_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="offer_letter" name="offer_letter" class="form-control" tabindex="54"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Appraisal Letter (If Any) : </strong>
                            @if(isset($a_l['url']) && $a_l['url'] != '')
                                <a target="_blank" href="../{{ $a_l['url'] }}">{{ $a_l['name'] }}</a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="appraisal_letter" name="appraisal_letter" class="form-control" tabindex="56"/>
                                            </span>
                                            <div class="appraisal_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="appraisal_letter" name="appraisal_letter" class="form-control" tabindex="56"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Relieving Letter : </strong>
                            @if(isset($rel_l['url']) && $rel_l['url'] != '')
                                <a target="_blank" href="../{{ $rel_l['url'] }}">{{ $rel_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="relieving_letter" name="relieving_letter" class="form-control" tabindex="58"/>
                                            </span>
                                            <div class="relieving_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="relieving_letter" name="relieving_letter" class="form-control" tabindex="58"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Resignation Letter (Email with Acceptance) : </strong>
                            @if(isset($reg_l['url']) && $reg_l['url'] != '')
                                <a target="_blank" href="../{{ $reg_l['url'] }}">{{ $reg_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="resignation_letter" name="resignation_letter" class="form-control" tabindex="60"/>
                                            </span>
                                            <div class="resignation_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="resignation_letter" name="resignation_letter" class="form-control" tabindex="60"/>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Appointment Letter : </strong>
                            @if(isset($app_l['url']) && $app_l['url'] != '')
                                <a target="_blank" href="../{{ $app_l['url'] }}">{{ $app_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="appointment_letter" name="appointment_letter" class="form-control" tabindex="55"/>
                                            </span>
                                            <div class="appointment_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="appointment_letter" name="appointment_letter" class="form-control" tabindex="55"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Experience Letter : </strong>
                            @if(isset($exp_l['url']) && $exp_l['url'] != '')
                                <a target="_blank" href="../{{ $exp_l['url'] }}">{{ $exp_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="experience_letter" name="experience_letter" class="form-control" tabindex="57"/>
                                            </span>
                                            <div class="experience_letter_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="experience_letter" name="experience_letter" class="form-control" tabindex="57"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Pay Slips - Current Financial Year : </strong>
                            @if(isset($pay_l['url']) && $pay_l['url'] != '')
                                <a target="_blank" href="../{{ $pay_l['url'] }}">{{ $pay_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="pay_slips" name="pay_slips" class="form-control" tabindex="59"/>
                                            </span>
                                            <div class="pay_slips_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="pay_slips" name="pay_slips" class="form-control" tabindex="59"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Form - 26 AS (From Current Company) : </strong>
                            @if(isset($form_l['url']) && $form_l['url'] != '')
                                <a target="_blank" href="../{{ $form_l['url'] }}">{{ $form_l['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="form_26" name="form_26" class="form-control" tabindex="61"/>
                                            </span>
                                            <div class="form_26_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="form_26" name="form_26" class="form-control" tabindex="61"/>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="border: 1px dotted black;margin-top: 15px;">
                    <h4>Personal Credentials : </h4><br/>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>ID Proof : </strong>
                            @if(isset($id_p['url']) && $id_p['url'] != '')
                                <a target="_blank" href="../{{ $id_p['url'] }}">{{ $id_p['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="id_proof" name="id_proof" class="form-control" tabindex="62"/>
                                            </span>
                                            <div class="id_proof_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="id_proof" name="id_proof" class="form-control" tabindex="62"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Passport : </strong>
                            @if(isset($pass_port['url']) && $pass_port['url'] != '')
                                <a target="_blank" href="../{{ $pass_port['url'] }}">{{ $pass_port['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="passport" name="passport" class="form-control" tabindex="64"/>
                                            </span>
                                            <div class="passport_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="passport" name="passport" class="form-control" tabindex="64"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>PAN Card : <span class = "required_fields">*</span> </strong>
                            @if(isset($pan_c['url']) && $pan_c['url'] != '')
                                <a target="_blank" href="../{{ $pan_c['url'] }}">{{ $pan_c['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="pan_card" name="pan_card" class="form-control" tabindex="66"/>
                                            </span>
                                            <div class="pan_card_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="pan_card" name="pan_card" class="form-control" tabindex="66"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Cancelled Cheque : <span class = "required_fields">*</span> </strong>
                            @if(isset($can_ch['url']) && $can_ch['url'] != '')
                                <a target="_blank" href="../{{ $can_ch['url'] }}">{{ $can_ch['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="cancelled_cheque" name="cancelled_cheque" class="form-control" tabindex="68"/>
                                            </span>
                                            <div class="cancelled_cheque_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="cancelled_cheque" name="cancelled_cheque" class="form-control" tabindex="68"/>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Address Proof : </strong>
                            @if(isset($add_p['url']) && $add_p['url'] != '')
                                <a target="_blank" href="../{{ $add_p['url'] }}">{{ $add_p['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="address_proof" name="address_proof" class="form-control" tabindex="63"/>
                                            </span>
                                            <div class="address_proof_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="address_proof" name="address_proof" class="form-control" tabindex="63"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Aadhar Card : <span class = "required_fields">*</span> </strong>
                            @if(isset($adr_c['url']) && $adr_c['url'] != '')
                                <a target="_blank" href="../{{ $adr_c['url'] }}">{{ $adr_c['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="aadhar_card" name="aadhar_card" class="form-control" tabindex="65"/>
                                            </span>
                                            <div class="aadhar_card_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="aadhar_card" name="aadhar_card" class="form-control" tabindex="65"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Resume : </strong>
                            @if(isset($resume['url']) && $resume['url'] != '')
                                <a target="_blank" href="../{{ $resume['url'] }}">{{ $resume['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="resume" name="resume" class="form-control" tabindex="67"/>
                                            </span>
                                            <div class="resume_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="resume" name="resume" class="form-control" tabindex="67"/>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Passport Size Photo : <span class = "required_fields">*</span> 
                            </strong>
                            @if(isset($pass_photo['url']) && $pass_photo['url'] != '')
                                <a target="_blank" href="../{{ $pass_photo['url'] }}">{{ $pass_photo['name'] }}
                                </a>

                                <div class="form-group file_input_redesign">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Change Document</span>
                                                <input type="file" id="passport_photo" name="passport_photo" class="form-control" tabindex="69"/>
                                            </span>
                                            <div class="passport_photo_div"></div>
                                        </div>
                                    </div>  
                                </div>
                            @else
                                <input type="file" id="passport_photo" name="passport_photo" class="form-control" tabindex="69"/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <input type="hidden" name="employee_id_increment" id="employee_id_increment" value="{!! $employee_id_increment !!}">

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">

        jQuery(document).ready(function () {

            $('#ssc_marksheet').change(function(e){
                var fileName = e.target.files[0].name;
                $(".ssc_marksheet_div").html(fileName);
            });

            $('#hsc_marksheet').change(function(e){
                var fileName = e.target.files[0].name;
                $(".hsc_marksheet_div").html(fileName);
            });

            $('#university_certificate').change(function(e){
                var fileName = e.target.files[0].name;
                $(".university_certificate_div").html(fileName);
            });

            $('#offer_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".offer_letter_div").html(fileName);
            });

            $('#appraisal_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".appraisal_letter_div").html(fileName);
            });

            $('#relieving_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".relieving_letter_div").html(fileName);
            });

            $('#resignation_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".resignation_letter_div").html(fileName);
            });

            $('#appointment_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".appointment_letter_div").html(fileName);
            });

            $('#experience_letter').change(function(e){
                var fileName = e.target.files[0].name;
                $(".experience_letter_div").html(fileName);
            });

            $('#pay_slips').change(function(e){
                var fileName = e.target.files[0].name;
                $(".pay_slips_div").html(fileName);
            });

            $('#form_26').change(function(e){
                var fileName = e.target.files[0].name;
                $(".form_26_div").html(fileName);
            });

            $('#id_proof').change(function(e){
                var fileName = e.target.files[0].name;
                $(".id_proof_div").html(fileName);
            });

            $('#passport').change(function(e){
                var fileName = e.target.files[0].name;
                $(".passport_div").html(fileName);
            });

            $('#pan_card').change(function(e){
                var fileName = e.target.files[0].name;
                $(".pan_card_div").html(fileName);
            });

            $('#cancelled_cheque').change(function(e){
                var fileName = e.target.files[0].name;
                $(".cancelled_cheque_div").html(fileName);
            });

            $('#address_proof').change(function(e){
                var fileName = e.target.files[0].name;
                $(".address_proof_div").html(fileName);
            });

            $('#aadhar_card').change(function(e){
                var fileName = e.target.files[0].name;
                $(".aadhar_card_div").html(fileName);
            });

            $('#resume').change(function(e){
                var fileName = e.target.files[0].name;
                $(".resume_div").html(fileName);
            });

            $('#passport_photo').change(function(e){
                var fileName = e.target.files[0].name;
                $(".passport_photo_div").html(fileName);
            });

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

            var doc_size = $("#doc_size").val();

            if(doc_size > 0) {
/*
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
                        "contact_number": {
                            required: true,
                        },
                        "date_of_birth": {
                            required: true,
                        },
                        "bank_name": {
                            required: true,
                        },
                        "acc_no": {
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
                        "date_of_joining": {
                            required: true,
                        },
                        "date_of_confirmation": {
                            required: true,
                        }
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
                        "contact_number": {
                            required: "Personal Contact Number is Required.",
                        },
                        "date_of_birth": {
                            required: "Please Select Birth Date.",
                        },
                        "bank_name": {
                            required: "Bank Name is Required.",
                        },
                        "acc_no": {
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
                        "date_of_joining": {
                            required: "Please Select Joining Date.",
                        },
                        "date_of_confirmation": {
                            required: "Please Select Confirmation Date.",
                        }
                    }
                });*/
            }
            else {
                /*$("#editprofile").validate({
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
                        "contact_number": {
                            required: true,
                        },
                        "date_of_birth": {
                            required: true,
                        },
                        "bank_name": {
                            required: true,
                        },
                        "acc_no": {
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
                        "date_of_joining": {
                            required: true,
                        },
                        "date_of_confirmation": {
                            required: true,
                        },
                        "university_certificate": {
                            required: true,
                        },
                        "pan_card": {
                            required: true,
                        },
                        "cancelled_cheque": {
                            required: true,
                        },
                        "aadhar_card": {
                            required: true,
                        },
                        "passport_photo": {
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
                        "contact_number": {
                            required: "Personal Contact Number is Required.",
                        },
                        "date_of_birth": {
                            required: "Please Select Birth Date.",
                        },
                        "bank_name": {
                            required: "Bank Name is Required.",
                        },
                        "acc_no": {
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
                        "date_of_joining": {
                            required: "Please Select Joining Date.",
                        },
                        "date_of_confirmation": {
                            required: "Please Select Confirmation Date.",
                        },
                        "university_certificate": {
                            required: "University Certificate is Required.",
                        },
                        "pan_card": {
                            required: "PAN Card is Required.",
                        },
                        "cancelled_cheque": {
                            required: "Cancelled Cheque is Required.",
                        },
                        "aadhar_card": {
                            required: "Aadhar Card is Required.",
                        },
                        "passport_photo": {
                            required: "Passport Photo is Required.",
                        },
                    }
                });*/
            }

            $("#date_of_birth").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#date_of_joining").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#date_of_confirmation").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#date_of_anniversary").datepicker({
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

    function countTotalSalary() {

        var fixed_salary = $("#fixed_salary").val();
        var performance_bonus = $("#performance_bonus").val();
        var total_salary = parseFloat(fixed_salary) + parseFloat(performance_bonus);
        $("#total_salary").val(total_salary);
    }
    </script>
@endsection