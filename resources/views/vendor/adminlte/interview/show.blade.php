@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ isset($posting_title)? $posting_title : null }}</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('interview.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
     
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Candidate Name :</th>
                            <td>{{ isset($candidate)? $candidate : null }}</td>
                            <th>Candidate Contact No. :</th>
                            <td>{{ isset($contact)? $contact : null }}</td>
                        </tr>
                        <tr>
                            <th>Interview Date :</th>
                            <td>{{ date('d-m-Y h:i A',strtotime($interview_date)) }}</td>
                            <th>Interview Type :</th>
                            <td>{{ isset($type)? $type : null }}</td>
                        </tr>
                        <tr>       
                            <th>Status :</th>
                            <td>{{ isset($status)? $status : null }}</td>
                            <th>Interview Cordinator :</th>
                            <td>{{ isset($interviewOwner)? $interviewOwner : null }}</td>
                        </tr>
                        <tr>
                            <th>About Client :</th>
                            <td width="500px">{!! isset($about)? $about : null !!}</td>
                            <th>Comments :</th>
                            <td>{{ isset($comments)? $comments : null }}</td>
                        </tr>
                        <tr>
                            @if(isset($skype_id) && $skype_id != '')
                                <th>Interview Round :</th>
                                <td>{{ isset($interview_round)? $interview_round : null }}</td>
                                <th>Video Id :</th>
                                <td>{{ isset($skype_id)? $skype_id : null }}</td>
                            @else
                                <th>Interview Round :</th>
                                <td colspan="3">{{ isset($interview_round)? $interview_round : null }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Candidate Location :</th>
                            <td>{{ isset($candidate_location)? $candidate_location : null }}</td>
                            <th>Interview Location :</th>
                            <td>{{ isset($interview_location)? $interview_location : null }}</td>
                        </tr>
                        @if(isset($location) && $location != '')
                        <tr>
                            <th>Interview Venue :</th>
                            <td colspan="3">{{ isset($location)? $location : null }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop