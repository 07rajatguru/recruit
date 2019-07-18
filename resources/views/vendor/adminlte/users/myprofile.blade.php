@extends('adminlte::page')

@section('title', 'Profile')

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
                <h2>My Profile</h2>
                {{--@if($user['type'] == "Photo")
                    <img src= "../../{!!$user['photo']!!}" height="100px" width="100px" />
                @else
                    <img src= "../../uploads/User_Default.jpg" height="100px" width="100px" />
                @endif--}}
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.editprofile',$user_id) }}">Edit Profile</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="col-xs-7 col-sm-7 col-md-7">
                        <br/>
                        <table class="table table-bordered">
                            {{--<tr>
                                <th>Name</th>
                                <td>{{ $user['name'] }}</td>
                            </tr>--}}
                            <tr>
                                <th>Designation</th>
                                <td>{{ $user['designation'] }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                            <tr>
                                <th>Secondary Email</th>
                                <td>{{ $user['s_email'] }}</td>
                            </tr>
                            <tr>
                                <th>Birth Date</th>
                                <td>{{ $user['birth_date'] }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td>{{ $user['join_date'] }}</td>
                            </tr>
                            <tr>
                                <th>Anniversary Date</th>
                                <td>{{ $user['anni_date'] }}</td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>{{ $user['contact_number'] }}</td>
                            </tr>
                            @if($isSuperAdmin || $isAccountant) 
                                <tr>
                                    <th>Exit Date</th>
                                    <td>{{ $user['exit_date'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Current Address</th>
                                <td>{{ $user['current_address'] }}</td>
                            </tr>
                            <tr>
                                <th>Permanent Address</th>
                                <td>{{ $user['permanent_address'] }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5" style="padding-left: 120px;">
                        @if($user['type'] == "Photo")
                            <img src= "../../{!!$user['photo']!!}"/ style="height: 200px;width: 200px;border-radius: 50%;">
                        @else
                            <img src= "../../uploads/User_Default.jpg"/ style="height: 200px;width: 200px;border-radius: 50%;">
                        @endif
                        <br/>
                        <div>
                            <h3 style="padding-left: 55px;">{{ $user['name'] }}</h3>
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
                    <br/>
                    <table class="table table-bordered">
                         <tr>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>Occupation</th>
                            <th>Contact No</th>
                         </tr>
                         @if(isset($user_family) && $user_family != '')
                             @foreach($user_family as $key => $value)
                                <tr>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['relationship'] }}</td>
                                    <td>{{ $value['occupation'] }}</td>
                                    <td>{{ $value['contact_no'] }}</td>
                                </tr>
                             @endforeach
                         @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Bank Details</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <br/>
                    <table class="table table-bordered">
                         <tr>
                            <th>Full Name</th>
                            <td>{{ $user['user_full_name'] }}</td>
                            <th>Bank Name</th>
                            <td>{{ $user['bank_name'] }}</td>
                            <th>Account Number</th>
                            <td>{{ $user['acc_no'] }}</td>
                         </tr>
                         <tr>
                            <th>Branch Name</th>
                            <td>{{ $user['branch_name'] }}</td>
                            <th>IFSC Code</th>
                            <td>{{ $user['ifsc_code'] }}</td>
                         </tr>
                    </table>
                </div>
            </div>
        </div>

        @if($isSuperAdmin || $isAccountant)
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Salary Details</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <br/>
                    <table class="table table-bordered">
                         <tr>
                            <th>Fixed Salary</th>
                            <td>{{ $user['salary'] }}</td>
                         </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif
 
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::users.upload', ['data' => $user, 'name' => 'usersattachments'])  
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
                                    <a target="_blank" href="../{{ $value['url'] }}">{{ $value['name'] }}
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