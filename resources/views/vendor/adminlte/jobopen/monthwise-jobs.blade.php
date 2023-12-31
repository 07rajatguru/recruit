@extends('adminlte::page')

@section('title', 'Monthwise Job Openings')

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
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List({{ $count or 0 }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobopen.create') }}">Create Job Openings</a>
            </div>
        </div>
    </div>

    <div class = "table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="job_table">
            <thead>
            <tr>
                <th>No</th>
                <th>Action</th>
                <th>MB</th>
                <th>Company Name</th>
                <th>Position Title</th>
                <th>CA</th>
                <th>Location</th>
                <th>Min CTC<br/>(in Lacs)</th>
                <th>Max CTC<br/>(in Lacs)</th>
                <th>Added Date</th>
                <th>Updated Date</th>
                <th>No. Of <br/> Positions</th>
                <th>Edu Qualifications</th>
                <th>Contact <br/> Point</th>
                <th>Desired Candidate</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#job_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [9,'desc'],
                "columnDefs": [

                    { "width": "10px", "targets": 0},
                    { "width": "5px", "targets": 1, "searchable": false, "orderable": false},
                    { "width": "15px", "targets": 2},
                    { "width": "10px", "targets": 3},
                    { "width": "150px", "targets": 4},
                    { "width": "10px", "targets": 5},
                    { "width": "10px", "targets": 6},
                    { "width": "10px", "targets": 7},
                    { "width": "5px", "targets": 8,},
                    { "visible": false,  "targets": 10},
                ],
                "ajax" : {
                    'url' : 'monthwise-jobs/all',
                    'type' : 'get',
                    error: function(){

                    },
                },
                initComplete:function( settings, json) {

                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {

                    if ( Data[15] == "0" ) {
                        $('td:eq(3)', Row).css('background-color', '');
                    }
                    else if ( Data[15] == "1" ) {
                        $('td:eq(3)', Row).css('background-color', '#FF0000');
                    }
                    else if ( Data[15] == "2" ) {
                        $('td:eq(3)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[15] == "3" ) {
                        $('td:eq(3)', Row).css('background-color', '#FABF8F');
                    }
                    else if ( Data[15] == "4" ) {
                        $('td:eq(3)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[15] == "5" ) {
                        $('td:eq(3)', Row).css('background-color', 'yellow');
                    }
                    else if ( Data[15] == "6" ) {
                        $('td:eq(3)', Row).css('background-color', '');
                    }
                    else if ( Data[15] == "7" ) {
                        $('td:eq(3)', Row).css('background-color', '#808080');
                    }
                    else if ( Data[15] == "8" ) {
                        $('td:eq(3)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[15] == "9" ) {
                        $('td:eq(3)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[15] == "10" ) {
                        $('td:eq(3)', Row).css('background-color', '#FFFFFF');
                    }
                    else {
                        $('td:eq(3)', Row).css('background-color', '');
                    }
                },
            });
        });
    </script>
@endsection