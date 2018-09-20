@extends('adminlte::page')

@section('title', 'User')

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
            
             @include('adminlte::users.uploadphoto', ['data' => $user, 'name' => 'userphoto'])
              @include('adminlte::partials.deleteProfilePhoto', ['data' => $user,'id'=> $user['id'], 'name' => 'profilephoto' ,'display_name'=> 'ProfilePhoto'])
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
                                {!! Form::text('email',$user['email'], array('id'=>'email','placeholder' => 'Email','class' => 'form-control', 'tabindex' => '2','disabled')) !!}
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
                                {!! Form::text('date_of_birth', isset($user['birth_date']) ? $user['birth_date'] : null, array('id'=>'date_of_birth','placeholder' => 'Birth Date','class' => 'form-control','tabindex' => '3')) !!}
                                </div>
                            </div>

                             <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                <strong>Joining Date: </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                {!! Form::text('date_of_joining', isset($user['join_date']) ? $user['join_date'] : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '4')) !!}
                                </div>
                            </div>   

                            <div class="form-group {{ $errors->has('salary') ? 'has-error' : '' }}">
                                <strong>Fixed Salary: </strong>
                                {!! Form::text('salary',$user['salary'], array('id'=>'designation','placeholder' => 'Salary','class' => 'form-control', 'tabindex' => '5' )) !!}
                                @if ($errors->has('salary'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('salary') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group">
                                <strong>Profile Photo: </strong><br/>

                                @if($user['type'] == "Photo")
                                <img src= "../{!!$user['photo']!!}" height="172px" width="170px" />
                                @else
                                <img src= "../{!!$user['photo']!!}" height="172px" width="170px" />
                                @endif

                            </div>
                       
                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation: </strong>
                                {!! Form::text('designation',$user['designation'], array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control', 'tabindex' => '6','disabled')) !!}
                                @if ($errors->has('designation'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('designation') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('semail') ? 'has-error' : '' }}">
                                <strong>Secondary Gmail: </strong>
                                {!! Form::text('semail',$user['s_email'], array('id'=>'semail','placeholder' => 'Secondary Gmail','class' => 'form-control', 'tabindex' => '7' )) !!}
                                @if ($errors->has('semail'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('semail') }}</strong>
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
                            
                                {!! Form::text('bank_name',$user['bank_name'],array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '8' )) !!}
                           
                                @if ($errors->has('bank_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('account_no') ? 'has-error' : '' }}">
                                <strong>Account Number: </strong>

                                {!! Form::text('account_no',$user['acc_no'], array('id'=>'account_no','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '10'  )) !!}

                                @if ($errors->has('account_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('account_no') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('user_full_name') ? 'has-error' : '' }}">
                                <strong>Full Name: </strong>

                                {!! Form::text('user_full_name',$user['user_full_name'], array('id'=>'user_full_name','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '12'  )) !!}

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
                                {!! Form::text('branch_name',$user['branch_name'], array('id'=>'branch_name','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '9'  )) !!}
                                @if ($errors->has('branch_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('branch_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ifsc') ? 'has-error' : '' }}">
                                <strong>IFSC Code: </strong>
                                {!! Form::text('ifsc',$user['ifsc_code'], array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '11' )) !!}
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

                <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Size</th>
                    </tr>
                        @if(sizeof($user['doc'])>0)
                            @foreach($user['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $user['id'], 'name' => 'usersattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td>
                                    <a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}
                                    </a>
                                    </td>
                                    <td>{{ $value['size'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                </table>
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
        });
    
    </script>
@endsection