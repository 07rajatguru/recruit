@extends('adminlte::page')

@section('title', 'Recovery Listing')

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
            <h4><b>Financial Year</b> : {{ $financial_year }}</h4>

            <div class="pull-left">
                <h2>Recovery List ({{ $count or 0}})</h2>
            </div>
            
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('bills.recovery') }}">Back</a> 
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="bill_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Action</th>

                @if($access=='true')
                    <th>Added by</th>
                @endif

                <th>Job Openings</th>
                <th>Candidate Name</th>
                <th>Joining Date</th>
                <th>Fixed Salary</th>
                <th>Efforts</th>
                <th>Candidate <br/>Contact Number</th>
                <th>Job Location</th>

                @permission(('display-recovery'))
                  <th>Percentage Charged</th>
                @endpermission

                <th>Source</th>
                <th>Client Name</th>
                <th>Client Contact Number</th>
                <th>Client Email Id</th>

                @permission(('display-recovery'))
                  <th>Lead Efforts</th>
                @endpermission
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <input type="hidden" name="year" id="year" value="{{ $year }}">
    <input type="hidden" name="confirmation" id="confirmation" value="{{ $confirmation }}">
    <input type="hidden" name="cancel" id="cancel" value="{{ $cancel }}">
@stop

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            var numCols = $('#bill_table thead th').length;
            var year = $("#year").val();
            var confirmation = $("#confirmation").val();
            var cancel = $("#cancel").val();

            if(numCols == 16) {

                var table = jQuery('#bill_table').DataTable({

                    'bProcessing' : true,
                    'serverSide' : true,
                    'order' : [5,'desc'],
                    'columnDefs': [ {orderable: false, targets: [1]}],
                    "ajax" : {
                        'url' : '/recovery/all',
                        'type' : 'get',
                        "data": {'year':year,'confirmation':confirmation,'cancel':cancel},
                        error: function() {
                        }
                    },
                    initComplete:function( settings, json) {

                        var count = json.recordsTotal;
                        $("#count").html("(" + count + ")");
                    },
                    'responsive': true,
                    'pageLength': 25,
                    'pagingType': "full_numbers",
                    'stateSave' : true,
                    "fnRowCallback": function( Row, Data ) {

                        if ( Data[16] == "1" ) {
                            $('td:eq(4)', Row).css('background-color', '#00B0F0');
                        }
                        else if ( Data[16] == "2" ) {
                            $('td:eq(4)', Row).css('background-color', '#FFA500');
                        }
                        else if ( Data[16] == "3" ) {
                            $('td:eq(4)', Row).css('background-color', '#FFC0CB');
                        }
                        else if ( Data[16] == "4" ) {
                            $('td:eq(4)', Row).css('background-color', '#32CD32');
                        }
                    }
                });
            }
            else {

                var table = jQuery('#bill_table').DataTable({

                    'bProcessing' : true,
                    'serverSide' : true,
                    'order' : [4,'desc'],
                    'columnDefs': [ {orderable: false, targets: [1]}],
                    "ajax" : {
                        'url' : '/recovery/all',
                        'type' : 'get',
                        "data": {'year':year,'confirmation':confirmation,'cancel':cancel},
                        error: function() {
                        }
                    },
                    initComplete:function( settings, json) {

                        var count = json.recordsTotal;
                        $("#count").html("(" + count + ")");
                    },
                    'responsive': true,
                    'pageLength': 25,
                    'pagingType': "full_numbers",
                    'stateSave' : true,
                    "fnRowCallback": function( Row, Data ) {

                        if ( Data[13] == "1" ) {
                            $('td:eq(3)', Row).css('background-color', '#00B0F0');
                        }
                        else if ( Data[13] == "2" ) {
                            $('td:eq(3)', Row).css('background-color', '#FFA500');
                        }
                        else if ( Data[13] == "3" ) {
                            $('td:eq(3)', Row).css('background-color', '#FFC0CB');
                        }
                        else if ( Data[13] == "4" ) {
                            $('td:eq(3)', Row).css('background-color', '#32CD32');
                        }
                    }
                });
            }
        });
    </script>
@endsection