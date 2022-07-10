@extends('adminlte::page')

@section('title', 'Recruitment Job Openings')

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
                <h2>Recruitment Job Openings({{ $count or 0 }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobopen.create') }}">Create Job Openings</a>
            </div>
        </div>
    </div>

    @permission(('display-job-priority-count-in-listing'))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Urgent Positions" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#FF0000;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_1 }}</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Identified candidates" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#92D050;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_8 }}</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="New Positions" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#00B0F0;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_2 }}</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Constant Deliveries needed" style="text-decoration: none;color: black;"><div style="width:max-content;height:40px;background-color:#FABF8F;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_3 }}</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Revived Positions" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:yellow;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_5 }}</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="No Deliveries Needed" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#808080;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_7 }}</div>
                    </a>
                </div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Under 10 Lacs" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#FFCC00;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><10L ({{ $under_ten_lacs }})</div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Between 10-20 Lacs" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#e87992;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">10-20L ({{ $between_ten_to_twenty_lacs }})</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a title="Above 20 Lacs" style="text-decoration: none;color: black;cursor: pointer;"><div style="width:max-content;height:40px;background-color:#f17a40;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"> >20L ({{ $above_twenty_lacs }})</div>
                    </a>
                </div>
            </div>
        </div><br/>
    @endpermission

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
                    'url' : 'recruitment-jobs/all',
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