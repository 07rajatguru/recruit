@extends('adminlte::page')

@section('title', 'Daily Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    Daily Report Details
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('dailyreport.index') }}"> Back</a>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th scope="row">Report Date:</th>
                                <td>{{ isset($report_date)? $report_date : null }}</td>
                                <th>Client Name</th>
                                <td>{{ isset($client_name)? $client_name : null }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Number of CVs sent to TL / Supervisor:</th>
                                <td>{{ isset($cvs_to_tl)? $cvs_to_tl : null }}</td>
                                <th>Number of Cvs reached to client after screening</th>
                                <td>{{ isset($cvs_to_client)? $cvs_to_client : null }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Position Name:</th>
                                <td>{{ isset($position_name)? $position_name : null }}</td>
                                <th>Candidate Status :</th>
                                <td>{{ isset($candidate_status)? $candidate_status : null }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Location :</th>
                                <td>{{ isset($location)? $location : null }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        @section('customscripts')
            <script>
                $(function() {
                    $( "#report_date" ).datepicker({
                        format: "dd/mm/yyyy",
                        autoclose: true,
                    });
                    $( "#client_id" ).select2();
                    $( "#candidate_status_id" ).select2();
                });
            </script>
        @endsection
    </div>

@stop

