@extends('adminlte::page')

@section('title', 'Bills')

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
                <h2>Forecasting Details</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('forecasting.index') }}"> Back</a>
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
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Company Name :</th>
                            <td>{{ $billsdetails['company_name'] }}</td>
                            <th>Candidate Name :</th>
                            <td>{{ $billsdetails['candidate_name'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Candidate Contact Number :</th>
                            <td>{{ $billsdetails['candidate_contact_number'] }}</td>
                            <th>Designation Offered :</th>
                            <td>{{ $billsdetails['designation_offered'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Date of Joining :</th>
                            <td>{{ $billsdetails['date_of_joining'] }}</td>
                            <th>Job Location :</th>
                            <td>{{ $billsdetails['job_location'] }}</td>
                        </tr>
                        <tr>
                            @if($isSuperAdmin || $isAccountant || $isOperationsExecutive)
                                <th scope="row">Fix Salary :</th>
                                <td>{{ $billsdetails['fixed_salary'] }}</td>
                            @else
                                <th scope="row">Fix Salary :</th>
                                <td colspan="3">{{ $billsdetails['fixed_salary'] }}</td>
                            @endif
                            @if($isSuperAdmin || $isAccountant || $isOperationsExecutive)
                                <th scope="row">Percentage Charged :</th>
                                <td>{{ $billsdetails['percentage_charged'] }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Remarks :</th>
                            <td>{{ $billsdetails['description'] }}</td>
                            <th scope="row">Source :</th>
                            <td>{{ $billsdetails['source'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client Name :</th>
                            <td>{{ $billsdetails['client_name'] }}</td>
                            <th scope="row">Client Contact Number :</th>
                            <td>{{ $billsdetails['client_contact_number'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client Email_ID :</th>
                            <td>{{ $billsdetails['client_email_id'] }}</td>
                            <th scope="row">Address of Communication :</th>
                            <td>{{ $billsdetails['address_of_communication'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Efforts</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Employee Name 1 :</th>
                            @if(isset($employee_name[0]) && $employee_name[0] != '')
                                <td>{{ $employee_name[0] }}</td>
                            @else
                                <td></td>
                            @endif

                            <th>Employee Percentage 1 :</th>
                            @if(isset($employee_percentage[0]) && $employee_percentage[0] != '')
                                <td>{{ (int)$employee_percentage[0] }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Employee Name 2 :</th>
                            @if(isset($employee_name[1]) && $employee_name[1] != '')
                                <td>{{ $employee_name[1] }}</td>
                            @else
                                <td></td>
                            @endif

                            <th>Employee Percentage 2 :</th>
                            @if(isset($employee_percentage[1]) && $employee_percentage[1] != '')
                                <td>{{ (int)$employee_percentage[1] }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Employee Name 3 :</th>
                            @if(isset($employee_name[2]) && $employee_name[2] != '')
                                <td>{{ $employee_name[2] }}</td>
                            @else
                                <td></td>
                            @endif

                            <th>Employee Percentage 3 :</th>
                            @if(isset($employee_percentage[2]) && $employee_percentage[2] != '')
                                <td>{{ (int)$employee_percentage[2] }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Employee Name 4 :</th>
                            @if(isset($employee_name[3]) && $employee_name[3] != '')
                                <td>{{ $employee_name[3] }}</td>
                            @else
                                <td></td>
                            @endif

                            <th>Employee Percentage 4 :</th>
                            @if(isset($employee_percentage[3]) && $employee_percentage[3] != '')
                                <td>{{ (int)$employee_percentage[3] }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Employee Name 5 :</th>
                            @if(isset($employee_name[4]) && $employee_name[4] != '')
                                <td>{{ $employee_name[4] }}</td>
                            @else
                                <td></td>
                            @endif

                            <th>Employee Percentage 5 :</th>
                            @if(isset($employee_percentage[4]) && $employee_percentage[4]) != '')
                                <td>{{ (int)$employee_percentage[4] }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>
        </div> 

        @if($isSuperAdmin || $isAccountant || $isOperationsExecutive)
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header with-border col-md-6 ">
                            <h3 class="box-title">Lead Efforts</h3>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th scope="row">Employee Name :</th>
                                <td>{{ $lead_name }}</td>
                                <th>Employee Percentage :</th>
                                <td>{{ (int)$lead_percentage }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachmetns</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::bills.upload', ['data' => $billsdetails, 'name' => 'billattachments'])
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Category</th>
                        </tr>
                        @if(isset($billsdetails['files']) && sizeof($billsdetails['files']) > 0)
                            @foreach($billsdetails['files'] as $key => $value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}">
                                            <i class="fa fa-fw fa-download"></i>
                                        </a>
                                            &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $billsdetails['id'], 'name' => 'billattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['fileName'] }}</a></td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['size'] }}</td>
                                    <td>{{ $value['category'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop