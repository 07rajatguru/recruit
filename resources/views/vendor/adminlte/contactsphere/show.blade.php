@extends('adminlte::page')

@section('title', 'Contact Detail')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Contact Details</h2>
            </div>
           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('contactsphere.index') }}">Back</a>
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
                            <th>Name</th>
                            <td>{{ $contact_details['name'] }}</td>
                            <th>Designation</th>
                            <td>{{ $contact_details['designation'] }}</td>
                        </tr>
                        <tr>
                            <th>Company</th>
                            <td>{{ $contact_details['company'] }}</td>
                            <th>Contact Number</th>
                            <td>{{ $contact_details['contact_number'] }}</td>
                        </tr>
                        <tr>
                            <th>Official Email Id</th>
                            <td>{{ $contact_details['official_email_id'] }}</td>
                            <th>Personal Id</th>
                            <td>{{ $contact_details['personal_id'] }}</td>
                        </tr>
                        <tr>
                            <th>Source</th>
                            <td>{{ $contact_details['source'] }}</td>
                            <th>Linkedin Profile Link</th>
                            <td>{{ $contact_details['linkedin_profile_link'] }}</td>
                        </tr>
                        <tr>
                            <th>Referred By</th>
                            <td>{{ $contact_details['referred_by'] }}</td>
                            <th>Location</th>
                            <td>{{ $contact_details['location'] }}</td>
                        </tr>
                        <tr>
                            <th>Self Remarks</th>
                            <td colspan="5">{{ $contact_details['self_remarks'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection