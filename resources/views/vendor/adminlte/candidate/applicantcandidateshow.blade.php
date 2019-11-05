@extends('adminlte::page')

@section('title', 'Candidate Detail')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">

            <div class="pull-left">
                <h2>Candidate Details</h2>
            </div>

           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('applicant.candidate') }}">Back</a>
            </div>
        </div>
    </div>

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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Candidate Name</th>
                            <td>{{ $candidateDetails['full_name'] }}</td>
                            <th>Email</th>
                            <td>{{ $candidateDetails['email'] }}</td>
                        </tr>

                        <tr>
                            <th scope="row">Gender</th>
                            <td>{{ $candidateDetails['gender'] }}</td>
                            <th>Marital Status</th>
                            <td>{{ $candidateDetails['marital_status'] }}</td>
                        </tr>

                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $candidateDetails['mobile'] }}</td>
                            <th>Phone</th>
                            <td>{{ $candidateDetails['phone'] }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Address Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Street 1</th>
                            <td>{{ $candidateDetails['street1'] }}</td>
                            <th>Street 2</th>
                            <td>{{ $candidateDetails['street2'] }}</td>
                        </tr>

                        <tr>
                            
                            <th>Country</th>
                            <td>{{ $candidateDetails['country'] }}</td>
                            <th>State</th>
                            <td>{{ $candidateDetails['state'] }}</td>
                        </tr>

                        <tr>
                            <th>City</th>
                            <td>{{ $candidateDetails['city'] }}</td>
                            <th>Zip Code</th>
                            <td>{{ $candidateDetails['zipcode'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Education and Professional Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Current Employer</th>
                            <td>{{ $candidateDetails['current_employer'] }}</td>
                            <th>Current Job Title</th>
                            <td>{{ $candidateDetails['current_job_title'] }}</td>
                        </tr>

                        <tr>
                            <th>Key Skills</th>
                            <td>{{ $candidateDetails['skill'] }}</td>
                            <th>Functional Roles</th>
                            <td>{{ $candidateDetails['functional_roles_name'] }}</td>
                        </tr>

                        <tr>
                            <th>Education Qualification</th>
                            <td>{{ $candidateDetails['eduction_qualification'] }}</td>
                            <th>Specialization</th>
                            <td>{{ $candidateDetails['education_specialization'] }}</td>
                        </tr>

                        <tr>
                            <th>Experience years</th>
                            <td>{{ $candidateDetails['experience_years'] }}</td>
                            <th>Experience Months</th>
                            <td>{{ $candidateDetails['experience_months'] }}</td>
                        </tr>

                        <tr>
                            <th>Current Salary</th>
                            <td>{{ $candidateDetails['current_salary'] }}</td>
                            <th>Expected Salary</th>
                            <td>{{ $candidateDetails['expected_salary'] }}</td>
                        </tr>

                        <tr>
                            <th>Skype Id</th>
                            <td colspan="3">{{ $candidateDetails['skype_id'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::candidate.upload', ['data' => $candidateDetails, 'name' => 'candidateattachments','form_name' => 'applicantShow'])
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Uploaded by</th>
                            <th>Size</th>
                            <th>Category</th>
                        </tr>

                        @if(isset($candidateDetails['files']) && sizeof($candidateDetails['files']) > 0)
                            @foreach($candidateDetails['files'] as $key => $value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}">
                                            <i class="fa fa-fw fa-download"></i>
                                        </a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $candidateDetails['candidate_id'], 'name' => 'candidateattachments' ,'display_name'=> 'Attachments','applicant_name' => 'applicantShow'])
                                    </td>

                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['fileName'] }}</a></td>
                                    <td>{{ $value['uploaded_by'] }}</td>
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
@endsection