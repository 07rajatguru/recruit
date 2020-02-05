@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">
                <h2>Interview Details</h2>
            </div>

            <div class="pull-right">
                 <a class="btn btn-primary" href="{{ route('interview.index') }}">Back</a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            {{--<th scope="row">Interview Name:</th>
                            <td>{{ isset($interview_name)? $interview_name : null }}</td>--}}
                            <th>Posting Name</th>
                            <td>{{ isset($posting_title)? $posting_title : null }}</td>
                            <th scope="row">Candidate:</th>
                            <td>{{ isset($candidate)? $candidate : null }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Candidate Contact No.:</th>
                            <td>{{ isset($contact)? $contact : null }}</td>
                            <th>Type :</th>
                            <td>{{ isset($type)? $type : null }}</td>
                            {{--<th>Client:</th>
                            <td>{{ isset($client)? $client : null }}</td>--}}
                        </tr>
                        <tr>
                            <th scope="row">Interview Date:</th>
                            <td>{{ date('d-m-Y h:i A',strtotime($interview_date)) }}</td>
                            <th scope="row">Status :</th>
                            <td>{{ isset($status)? $status : null }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Location :</th>
                            <td>{{ isset($location)? $location : null }}</td>
                            <th scope="row">Interview Cordination:</th>
                            <td>{{ isset($interviewOwner)? $interviewOwner : null }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Comments :</th>
                            <td>{{ isset($comments)? $comments : null }}</td>
                            <th scope="row">About :</th>
                            <td>{{ isset($about)? $about : null }}</td>
                        </tr>
                        <tr>
                            @if(isset($skype_id) && $skype_id != '')
                                <th scope="row">Interview Round :</th>
                                <td>{{ isset($interview_round)? $interview_round : null }}</td>
                                <th scope="row">Video Id :</th>
                                <td>{{ isset($skype_id)? $skype_id : null }}</td>
                            @else
                                <th scope="row">Interview Round :</th>
                                <td colspan="3">{{ isset($interview_round)? $interview_round : null }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th scope="row">Candidate Location:</th>
                            <td>{{ isset($candidate_location)? $candidate_location : null }}</td>
                            <th scope="row">Interview Location:</th>
                            <td>{{ isset($interview_location)? $interview_location : null }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

