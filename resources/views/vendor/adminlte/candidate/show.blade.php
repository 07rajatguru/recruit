@extends('adminlte::page')

@section('title', 'Candidate Detail')

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
                <h2>Candidate Details</h2>
            </div>

           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('candidate.index') }}">Back</a>
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
                            <th scope="row">Candidate Name</th>
                            <td>{{ $candidateDetails['fname'] }}</td>
                            <th>Email</th>
                            <td>{{ $candidateDetails['email'] }}</td>
                        </tr>

                        <tr>
                            <th scope="row">Candidate Sex</th>
                            <td>{{ $candidateDetails['candidateSex'] }}</td>
                            <th>Candidate Marital Status</th>
                            <td>{{ $candidateDetails['maritalStatus'] }}</td>
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
                            <th>City</th>
                            <td>{{ $candidateDetails['city'] }}</td>
                            <th>State</th>
                            <td>{{ $candidateDetails['state'] }}</td>
                        </tr>

                        <tr>
                            <th>Zip Code</th>
                            <td>{{ $candidateDetails['zipcode'] }}</td>
                            <th>Country</th>
                            <td>{{ $candidateDetails['country'] }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Personal Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Highest Qualification</th>
                            <td>{{ $candidateDetails['highest_qualification'] }}</td>
                            <th>Experience years</th>
                            <td>{{ $candidateDetails['experience_years'] }}</td>
                        </tr>

                        <tr>
                            <th>Experience Months</th>
                            <td>{{ $candidateDetails['experience_months'] }}</td>
                            <th>Current Job Title</th>
                            <td>{{ $candidateDetails['current_job_title'] }}</td>
                        </tr>

                        <tr>
                            <th>Current Employer</th>
                            <td>{{ $candidateDetails['current_employer'] }}</td>
                            <th>Expected Salary</th>
                            <td>{{ $candidateDetails['expected_salary'] }}</td>
                        </tr>

                        <tr>
                            <th>Current Salary</th>
                            <td>{{ $candidateDetails['current_salary'] }}</td>
                            <th>Skill</th>
                            <td>{{ $candidateDetails['skill'] }}</td>
                        </tr>

                        <tr>
                            <th>Skype Id</th>
                            <td>{{ $candidateDetails['skype_id'] }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Other Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Candidate Status</th>
                            <td>{{ $candidateDetails['candidate_status'] }}</td>
                            <th>CandidateSource</th>
                            <td>{{ $candidateDetails['candidate_source'] }}</td>
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
                    @include('adminlte::candidate.upload', ['data' => $candidateDetails, 'name' => 'candidateattachments'])
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
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $candidateDetails['id'], 'name' => 'candidateattachments' ,'display_name'=> 'Attachments'])
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

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Jobs associated with candidate</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Posting Title</th>
                            <th>Company Name</th>
                            <th>Location</th>
                            <th>MB</th>
                            <th>Date/Time</th>
                        </tr>

                        @if(isset($candidateDetails['job']) && sizeof($candidateDetails['job']) > 0)
                            @foreach($candidateDetails['job'] as $key => $value)
                                <tr>
                                    <td>{{ $value['posting_title'] }}</td>
                                    <td>{{ $value['company_name'] }}</td>
                                    <td>{{ $value['location'] }}</td>
                                    <td>{{ $value['managed_by'] }}</td>
                                    <td>{{ date('d-m-Y h:i A',strtotime($value['datetime'])) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection