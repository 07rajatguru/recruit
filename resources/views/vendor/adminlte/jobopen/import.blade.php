@extends('adminlte::page')

@section('title', 'Job Openings Detail')

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
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if(isset($messages) && sizeof($messages)>0)
        <div class="alert alert-success">
            @foreach($messages as $key=>$value)
                <p>{{ $value }}</p>
            @endforeach
        </div>
    @endif

    
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Import Excel</h2>
            </div>
            <div class="pull-right">
                <a href="/uploads/import_files/jobs_sample.xlsx" target="_blank" class="btn btn-warning">Sample format of Excel</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <h4 class="box-title">Import Excel</h4>
                <form style="" action="{{ URL::to('jobs/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <input type="file" name="import_file" />{{ csrf_field() }}<br/>
                    <button class="btn btn-primary">Import CSV or Excel File</button>
                </form>
                <br>
            </div>
        </div>
    </div>
    
    <h2>Job Openings List</h2>
    <div class = "table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="job_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                    <th>Action</th>
                    <th>MB</th>
                    <th>Company Name</th>
                    <th>Position Title</th>
                    <th>CA</th>
                    <th>Location</th>
                    <th>Min CTC<br/>(in Lacs)</th>
                    <th>Max CTC<br/>(in Lacs)</th>
                    <th>Added Date</th>
                    <th>No. Of <br/> Positions </th>
                    <th>Edu Qualifications</th>
                    <th>Contact <br/> Point</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var app_url = "{!! env('APP_URL'); !!}";
            var year = $("#year").val();
            var client_heirarchy = $("#client_heirarchy").val();
            var mb_name = $("#mb_name").val();
            var company_name = $("#company_name").val();
            var posting_title = $("#posting_title").val();
            var location = $("#location").val();
            var min_ctc = $("#min_ctc").val();
            var max_ctc = $("#max_ctc").val();
            var added_date = $("#added_date").val();
            var no_of_positions = $("#no_of_positions").val();

            $("#job_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [10,'desc'],
                "columnDefs": [ 

                    { "width": "10px", "targets": 0, "searchable": false, "orderable": false},
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false},
                    { "width": "10px", "targets": 2, "searchable": false, "orderable": false},
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "150px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                    { "width": "5px",  "targets": 10 },
                ],
                "ajax" : {
                    'url': app_url+'/jobs/alls',
                    data: {
                        year:year,
                        client_heirarchy:client_heirarchy,
                        mb_name:mb_name,
                        company_name:company_name,
                        posting_title:posting_title,
                        location:location,
                        min_ctc:min_ctc,
                        max_ctc:max_ctc,
                        added_date:added_date,
                        no_of_positions:no_of_positions,
                    },
                    'type' : 'get',
                    error: function() {

                    },
                },
                initComplete:function( settings, json) {

                   
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {

                    if ( Data[16] == "0" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[16] == "1" ){
                        $('td:eq(4)', Row).css('background-color', '#FF0000');
                    }
                    else if ( Data[16] == "2" ){
                        $('td:eq(4)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[16] == "3" ){
                        $('td:eq(4)', Row).css('background-color', '#FABF8F');
                    }
                    else if ( Data[16] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[16] == "5" ){
                        $('td:eq(4)', Row).css('background-color', 'yellow');
                    }
                    else if ( Data[16] == "6" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[16] == "7" ){
                        $('td:eq(4)', Row).css('background-color', '#808080');
                    }
                    else if ( Data[16] == "8" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[16] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[16] == "10" ){
                        $('td:eq(4)', Row).css('background-color', '#FFFFFF');
                    }
                    else{
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                },
                stateSave : true,
            });
            $('#allcb').change(function() {
                if($(this).prop('checked')) {
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', true);
                    });
                }
                else {
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', false);
                    });
                }
            });
            $('.multiple_jobs').change(function() {
                if ($(this).prop('checked')) {
                    if ($('.multiple_jobs:checked').length == $('.multiple_jobs').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else{
                    $("#allcb").prop('checked', false);
                }
            });
        });

    </script>
@endsection
  