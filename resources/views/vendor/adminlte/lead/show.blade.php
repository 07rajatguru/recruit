@extends('adminlte::page')

@section('title', 'Lead Detail')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $lead_details['name'] }}</h2>
            </div>
           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('lead.index') }}">Back</a>
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
                            <th>Contact Point</th>
                            <td colspan="3">{{ $lead_details['coordinator_name'] }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $lead_details['mail'] }}</td>
                            <th>Secondary Email</th>
                            <td>{{ $lead_details['s_email'] }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $lead_details['mobile'] }}</td>
                            <th>Other Number</th>
                            <td>{{ $lead_details['other_number'] }}</td>
                        </tr>
                        <tr>
                            <th>Display Name</th>
                            <td>{{ $lead_details['display_name'] }}</td>
                            <th>Lead Service</th>
                            <td>{{ $lead_details['service'] }}</td>
                        </tr>
                        <tr>
                            <th>Lead Status</th>
                            <td>{{ $lead_details['lead_status'] }}</td>
                            <th>Website</th>
                            <td>{{ $lead_details['website'] }}</td>
                        </tr>
                        <tr>
                            <th>Source</th>
                            <td>{{ $lead_details['source'] }}</td>
                            <th>Designation</th>
                            <td>{{ $lead_details['designation'] }}</td>
                            
                        </tr>
                        <tr>
                            <th>Referred By</th>
                            <td>{{ $lead_details['referredby'] }}</td>
                            <th>Remarks</th>
                            <td>{{ $lead_details['remarks'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Address Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Location</th>
                            <td>{{ $lead_details['location'] }}</td>
                        </tr>
                    </table>
                 </div>
             </div>
        </div>
    </div>
@endsection