@extends('adminlte::page')

@section('title', 'User Profile')

@section('content_header')
    <h1></h1>
@stop

@section('content')


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

    {!! Form::open(array('route' => 'users.profilestore','method'=>'POST','id' => 'editprofile','files' => true)) !!}

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
                                <strong>Name: </strong>
                                {!! Form::text('name',$user['name'], array('id'=>'name','placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: </strong>
                                {!! Form::text('email',$user['email'], array('id'=>'email','placeholder' => 'Email','class' => 'form-control','disabled')) !!}
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

                            <div class="form-group {{ $errors->has('salary') ? 'has-error' : '' }}">
                                <strong>Fixed Salary: </strong>
                                {!! Form::text('salary',$user['salary'], array('id'=>'designation','placeholder' => 'Salary','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('salary'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('salary') }}</strong>
                                </span>
                                @endif
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
                                    <img src= "../{!!$user['photo']!!}" height="150px" width="150px" />
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
                                                <input type="file" name="image" id="upload_img">
                                                </span>
                                                <!-- <a href="#" class="btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> -->
                                        </div>
                                    </div>  
                                </div>
                            </div>
                       
                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation: </strong>
                                {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','disabled')) !!}
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
                <h3 class="box-title">Spouse Details</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('spouse_name') ? 'has-error' : '' }}">
                                <strong>Name: </strong>
                            
                                {!! Form::text('spouse_name',$user['spouse_name'],array('id'=>'spouse_name','placeholder' => 'Spouse Name','class' => 'form-control', 'tabindex' => '8' )) !!}
                           
                                @if ($errors->has('spouse_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('spouse_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                    </div>
                </div>
                         <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('spouse_profession') ? 'has-error' : '' }}">
                                <strong>Profession: </strong>

                                {!! Form::text('spouse_profession',$user['spouse_profession'], array('id'=>'spouse_profession','placeholder' => 'Spouse Profession','class' => 'form-control', 'tabindex' => '9'  )) !!}

                                @if ($errors->has('spouse_profession'))
                                <span class="help-block">
                                <strong>{{ $errors->first('spouse_profession') }}</strong>
                                </span>
                                @endif
                            </div>
                    </div>
                </div>
                         <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('spouse_contact_no') ? 'has-error' : '' }}">
                                <strong>Contact Number: </strong>

                                {!! Form::text('spouse_contact_no',$user['spouse_contact_number'], array('id'=>'spouse_contact_no','placeholder' => 'Spouse Contact No','class' => 'form-control', 'tabindex' => '10'  )) !!}

                                @if ($errors->has('spouse_contact_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('spouse_contact_no') }}</strong>
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
                <h3 class="box-title">Parents Details</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('father_name') ? 'has-error' : '' }}">
                                <strong>Father Name: </strong>
                            
                                {!! Form::text('father_name',$user['father_name'],array('id'=>'father_name','placeholder' => 'Father Name','class' => 'form-control', 'tabindex' => '11' )) !!}
                           
                                @if ($errors->has('father_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('father_name') }}</strong>
                                    </span>
                                @endif
                            </div>

                             <div class="form-group {{ $errors->has('mother_name') ? 'has-error' : '' }}">
                                <strong>Mother Name: </strong>
                            
                                {!! Form::text('mother_name',$user['mother_name'],array('id'=>'mother_name','placeholder' => 'Mother Name','class' => 'form-control', 'tabindex' => '14' )) !!}
                           
                                @if ($errors->has('mother_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('mother_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('father_profession') ? 'has-error' : '' }}">
                                <strong>Father Profession: </strong>

                                {!! Form::text('father_profession',$user['father_profession'], array('id'=>'father_profession','placeholder' => 'Father Profession','class' => 'form-control', 'tabindex' => '12'  )) !!}

                                @if ($errors->has('father_profession'))
                                <span class="help-block">
                                <strong>{{ $errors->first('father_profession') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('mother_profession') ? 'has-error' : '' }}">
                                <strong>Mother Profession: </strong>

                                {!! Form::text('mother_profession',$user['mother_profession'], array('id'=>'mother_profession','placeholder' => 'Mother Profession','class' => 'form-control', 'tabindex' => '15'  )) !!}

                                @if ($errors->has('mother_profession'))
                                <span class="help-block">
                                <strong>{{ $errors->first('mother_profession') }}</strong>
                                </span>
                                @endif
                            </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">

                            <div class="form-group {{ $errors->has('father_contact_no') ? 'has-error' : '' }}">
                                <strong>Father Contact Number: </strong>

                                {!! Form::text('father_contact_no',$user['father_contact_number'], array('id'=>'father_contact_no','placeholder' => 'Father Contact No','class' => 'form-control', 'tabindex' => '13'  )) !!}

                                @if ($errors->has('father_contact_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('father_contact_no') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('mother_contact_no') ? 'has-error' : '' }}">
                                <strong>Mother Contact Number: </strong>

                                {!! Form::text('mother_contact_no',$user['mother_contact_number'], array('id'=>'mother_contact_no','placeholder' => 'Mother Contact No','class' => 'form-control', 'tabindex' => '16'  )) !!}

                                @if ($errors->has('mother_contact_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('mother_contact_no') }}</strong>
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
                                <strong>Bank Name: </strong>
                            
                                {!! Form::text('bank_name',$user['bank_name'],array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '17' )) !!}
                           
                                @if ($errors->has('bank_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('account_no') ? 'has-error' : '' }}">
                                <strong>Account Number: </strong>

                                {!! Form::text('account_no',$user['acc_no'], array('id'=>'account_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '19'  )) !!}

                                @if ($errors->has('account_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('account_no') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('user_full_name') ? 'has-error' : '' }}">
                                <strong>Full Name: </strong>

                                {!! Form::text('user_full_name',$user['user_full_name'], array('id'=>'user_full_name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '21'  )) !!}

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
                                {!! Form::text('branch_name',$user['branch_name'], array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '18'  )) !!}
                                @if ($errors->has('branch_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('branch_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ifsc') ? 'has-error' : '' }}">
                                <strong>IFSC Code: </strong>
                                {!! Form::text('ifsc',$user['ifsc_code'], array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '20' )) !!}
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
            for(var i=0;i<total_file;i++)
            {
                $('#default_image').hide();
                $('#upload_images_div').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' height='150px' width='150px'>");
                $('#upload_images_div').append("<br/><br/>");
            }
    });
        });
    
    </script>
@endsection