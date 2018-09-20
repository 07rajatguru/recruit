@extends('adminlte::page')

@section('title', 'User Detail')

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
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-12 margin-tb">

           <div class="pull-left">
                @if($user['type'] == "Photo")
                    <img src= "../{!!$user['photo']!!}" height="100px" width="100px" />
                @else
                    <img src= "../../uploads/User_Default.jpg" height="100px" width="100px" />
                @endif
            </div>
            
           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.editprofile') }}">Edit Profile</a>
            </div>
            
        </div>

    </div>

    <div>
         <br/>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                     
                        <tr>
                            <th>Name</th>
                            <td>{{ $user['name'] }}</td>
                            <th>Designation</th>
                            <td>{{ $user['designation'] }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user['email'] }}</td>
                            <th>Secondary Email</th>
                            <td>{{ $user['s_email'] }}</td>
                        </tr>
                        <tr>
                            <th>Birth Date</th>
                            <td>{{ $user['birth_date'] }}</td>
                            <th>Joining Date</th>
                            <td>{{ $user['join_date'] }}</td>
                        </tr>
                        <tr>
                            <th>Fixed Salary</th>
                            <td>{{ $user['salary'] }}</td>
                        </tr>

                    </table>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Bank Details</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                         <tr>
                            <th>Full Name</th>
                            <td>{{ $user['user_full_name'] }}</td>
                            <th>Bank Name</th>
                            <td>{{ $user['bank_name'] }}</td>
                            <th>Branch Name</th>
                            <td>{{ $user['branch_name'] }}</td>
                         </tr>
                         <tr>
                            <th>Account Number</th>
                            <td>{{ $user['acc_no'] }}</td>
                            <th>IFSC Code</th>
                            <td>{{ $user['ifsc_code'] }}</td>
                         </tr>
                    </table>
                </div>

            </div>
        </div>

 
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header  col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                   
                </div>

                <div class="box-header  col-md-8 ">

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
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
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $value['id'], 'name' => 'usersattachments' ,'display_name'=> 'Attachments'])
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
    </div>
@endsection