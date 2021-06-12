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
                <h3>{{ $user['name'] }}</h3>
            </div>
            @permission(('edit-profile-of-loggedin-user'))
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('users.editprofile',$user_id) }}">Edit Profile</a>
                </div>
            @endpermission
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <br/>
                        <table class="table table-bordered">
                            <tr>
                                <th>Designation</th>
                                <td>{{ $user['designation'] }}</td>
                            </tr>
                            <tr>
                                <th>Official Email</th>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                            <tr>
                                <th>Official Gmail</th>
                                <td>{{ $user['semail'] }}</td>
                            </tr>
                            <tr>
                                <th>Personal Email</th>
                                <td>{{ $user['personal_email'] }}</td>
                            </tr>
                            <tr>
                                <th>Birth Date</th>
                                <td>{{ $user['date_of_birth'] }}</td>
                            </tr>
                            <tr>
                                <th>Blood Group</th>
                                <td>{{ $user['blood_group'] }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td>{{ $user['date_of_joining'] }}</td>
                            </tr>
                            <tr>
                                <th>Anniversary Date</th>
                                <td>{{ $user['date_of_anniversary'] }}</td>
                            </tr>
                            @permission(('edit-user-profile'))
                                <tr>
                                    <th>Exit Date</th>
                                    <td>{{ $user['date_of_exit'] }}</td>
                                </tr>
                            @endpermission
                        </table>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6" style="padding-left: 200px;">
                        @if($user['type'] == "Photo")
                            <img src= "../../{!!$user['photo']!!}"/ style="height: 150px;width: 150px;border-radius: 50%;">
                        @else
                            <img src= "../../uploads/User_Default.jpg"/ style="height: 150px;width: 150px;border-radius: 50%;">
                        @endif
                        <br/><br/>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Personal Contact Number</th>
                                <td>{{ $user['contact_number'] }}</td>
                            </tr>
                            <tr>
                                <th>Official Contact Number</th>
                                <td>{{ $user['contact_no_official'] }}</td>
                            </tr>
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

        @permission(('display-salary'))

        @if(isset($user['fixed_salary']) && $user['fixed_salary'] != '')
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title"><a href="#" data-toggle="modal" data-target="#salaryModal">View Salary Details</a></h3>
                    </div>
                </div>
            </div>
        @endif
        @endpermission

        <!-- Salary Popup -->
        <div id="salaryModal" class="modal text-left fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title"><b>Salary Details</b></h5>
                    </div>

                    <div class="modal-body">
                        <table id="salary_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                            <tr>
                                <td style="text-align: center;"><b>Fixed Salary (Monthly)</b></td>
                                <td style="text-align: center;"><b>Performance Bonus </b></td>
                                <td style="text-align: center;"><b>Total Salary</b></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">{{ $user['fixed_salary'] }}</td>
                                <td style="text-align: center;">{{ $user['performance_bonus'] }}</td>
                                <td style="text-align: center;">{{ $user['total_salary'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Salary Popup End -->
 
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
                            <th>Type</th>
                        </tr>
                        @if(sizeof($user['doc'])>0)
                            @foreach($user['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $value['id'], 'name' => 'usersattachments' ,'display_name'=> 'Attachments','type' => 'MyProfile'])
                                    </td>
                                    <td>
                                    <a target="_blank" href="../{{ $value['url'] }}">{{ $value['name'] }}
                                    </a>
                                    </td>
                                    <td>{{ $value['size'] }}</td>
                                    <td>{{ $value['type'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customscripts')
    <script type="text/javascript">

        jQuery(document).ready(function () {

            $("#users_upload_type").select2();
        });
    </script>
@stop