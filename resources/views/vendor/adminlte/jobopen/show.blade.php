@extends('adminlte::page')

@section('title', 'Job Opening Detail')

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
                <h3>{{ $jobopen['posting_title'] }} - {{--<a target="_blank" href="/client/{{ $jobopen['client_id'] }}">--}}{{ $jobopen['client_name']  }}{{--</a>--}} </h3>
            </div>

            <div class="pull-right">
                <a class="btn bg-maroon" id="associated_candidates" href="{{ route('jobopen.associated_candidates_get',$jobopen['id']) }}">Associated Candidates (...)</a>
                <a class="btn btn-success" href="{{ route('jobopen.associate_candidate_get',$jobopen['id'] ) }}">Associate New Candidates</a>

                @if(isset($jobopen['access']) && $jobopen['access']=='1')
                    <a class="btn btn-primary" href="{{ route('jobopen.edit',$jobopen['id']) }}">Edit</a>
                @endif
                @include('adminlte::partials.MoreOptions', ['data' => $jobopen, 'name' => 'jobopen','display_name'=>'More Information'])
            </div>
        </div>

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
                            <th scope="row">Posting Title</th>
                            <td>{{ $jobopen['posting_title'] }}</td>
                            <th>Job Opening ID</th>
                            <td>{{ $jobopen['job_id'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client Name</th>
                            <td colspan="3">{{ $jobopen['client_name'] }}</td>
                           {{-- <th>Job Opening Status</th>
                            <td>{{ $jobopen['job_opening_status'] }}</td> --}}
                        </tr>
                        <tr>
                            <th scope="row">Hiring Manager</th>
                            <td>{{ $jobopen['hiring_manager_name'] }}</td>
                            <th>Number of Positions</th>
                            <td>{{ $jobopen['no_of_positions'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Target Date</th>
                            <td>{{ $jobopen['target_date'] }}</td>
                            <th>Date Opened</th>
                            <td>{{ $jobopen['date_opened'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Job Type</th>
                            <td>{{ $jobopen['job_type'] }}</td>
                            <th>Industry</th>
                            <td>{{ $jobopen['industry_name'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Work Experience (In years)</th>
                            <td>{{ $jobopen['work_experience'] }}</td>
                            <th>Salary</th>
                            <td>{{ $jobopen['salary'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row" >Job Description</th>
                            <td colspan="3">{{ $jobopen['description'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row" >Desired Candidates</th>
                            <td colspan="3">{{ $jobopen['desired_candidate'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row" >Users who can access the job</th>
                            <td colspan="3">{{ implode(",",$jobopen['users']) }}</td>
                        </tr>
                     </table>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Job Location</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Country</th>
                            <td>{{ $jobopen['country'] }}</td>
                            <th>State</th>
                            <td>{{ $jobopen['state'] }}</td>
                            <th>City</th>
                            <td>{{ $jobopen['city'] }}</td>
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
                    @include('adminlte::jobopen.upload', ['data' => $jobopen, 'name' => 'jobopen'])
                </div>

                <div class="box-header  col-md-8 ">

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
                        @if(sizeof($jobopen['doc'])>0)
                            @foreach($jobopen['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $jobopen['id'], 'name' => 'jobopenattachments' ,'display_name'=> 'Attachments'])
                                    </td>
                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
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
        <input type="hidden" id="token" value="{{ csrf_token() }}">
    </div>

@endsection

@section('customscripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            // call associated candidates with job
            associated_candidates(<?php echo $jobopen['id'] ?>);
        });

        function associated_candidates(jobid) {
            var token = $("#token").val();
            jQuery.ajax ({
                type: "POST",
                url: "/jobs/associated_candidates_count",
                data: "_token="+token+"&jobid="+jobid,
                dataType: "json"
            }).done(function( response ) {
                if(response.returnvalue=='valid'){
                    document.getElementById("associated_candidates").text ="Associated Candidates ("+response.count+")";
                }
            });

        }
    </script>
@endsection