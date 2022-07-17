@extends('adminlte::page')

@section('title', 'Job Closings')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Closing List <span id="count">({{ $count }})</span></h2>
            </div>

            @if(!$isClient)

                <div class="pull-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mastersearchmodal">Master Search</button>

                    @permission(('job-add'))
                        <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
                    @endpermission
                </div>
            @endif

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>
               <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <strong>Select Financial Year:</strong>
                @if($selected_year = Session::get('selected_year'))
                    {{Form::select('year',$year_array, $selected_year, array('id'=>'year','class'=> 'form-control'))}}
                @else
                    {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
                @endif
            </div>
        </div>

        <div class="box-body col-xs-12 col-sm-3 col-md-2">
            <div class="form-group" style="margin-top: 19px;">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>
    </div>
    <br/>

    @permission(('display-job-priority-count-in-listing'))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                 <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="on_hold_href" href="" title="On Hold" style="text-decoration: none;color: black;"><div  id="on_hold" style="width:max-content;height:40px;background-color:#B1A0C7;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $close_priority['priority_4'] }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="closed_us_href" href="" title="Closed By Us" style="text-decoration: none;color: black;"><div id="closed_us" style="width:max-content;height:40px;background-color:#92D050;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $close_priority['priority_9'] }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="closed_client_href" href="" title="Closed By Client" style="text-decoration: none;color: black;"><div id="closed_client" style="width:max-content;height:40px;background-color:#FFFFFF;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $close_priority['priority_10'] }}</div></a>
                </div>

                {{-- Changes from 28-05-2021 Salary Wise Listing --}}

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;"></div>

                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="under_ten_lacs_href" href="" title="Under 10 Lacs" style="text-decoration: none;color: black;">
                        <div style="width:max-content;height:40px;background-color:#FFCC00;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><10L (<span id="under_ten_lacs">{{ $under_ten_lacs }}</span>)
                        </div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="between_ten_to_twenty_lacs_href" href="" title="Between 10-20 Lacs" style="text-decoration: none;color: black;">
                        <div style="width:max-content;height:40px;background-color:#e87992;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px" >10-20L (<span id="between_ten_to_twenty_lacs">{{ $between_ten_to_twenty_lacs }}</span>)
                        </div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="above_twenty_lacs_href" href="" title="Above 20 Lacs" style="text-decoration: none;color: black;">
                        <div style="width:max-content;height:40px;background-color:#f17a40;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">>20L (<span id="above_twenty_lacs">{{ $above_twenty_lacs }}</span>)
                        </div>
                    </a>
                </div>
            </div>
        </div><br/>
    @endpermission

    @include('adminlte::partials.jobmastersearchmodal', ['field_list' => $field_list,'client_hierarchy_name' => $client_hierarchy_name,'users' => $users,'min_ctc_array' => $min_ctc_array,'max_ctc_array' => $max_ctc_array,'type' => 'Job Closings'])
    
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="job_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Action</th>
                <th>Status</th>
                <th>MB</th>
                <th>Company Name</th>
                <th>Position Title</th>
                <th>CA</th>
                <th>Location</th>
                <th>Min CTC<br/>(in Lacs)</th>
                <th>Max CTC<br/>(in Lacs)</th>
                <th>Added Date</th>
                <th>No. Of <br/> Positions</th>
                <th>Edu Qualifications</th>
                <th>Contact <br/> Point</th>
                <th>Target Industries</th>
                <th>Desired Candidate</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#job_priority").select2({width:"565px"});
            $("#selected_field").select2({width : '567px'});
            $("#client_heirarchy").select2({width:"565px"});
            $("#mb_name").select2({width:"565px"});
            $("#min_ctc").select2({width:"565px"});
            $("#max_ctc").select2({width:"565px"});

            $("#added_date").attr("autocomplete", "off");

            $("#added_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

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
                "order" : [0,'desc'],
                "columnDefs": [ { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 2,},
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "150px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                    { "width": "5px", "targets": 10 },
                ],
                "ajax" : {
                    'url' : 'allclose',
                    'type' : 'get',
                    data : {
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
                    error: function() {
                    }
                },
                initComplete:function( settings, json) {

                    var count = json.recordsTotal;
                    var close_priority = json.close_priority;
                    var job_priority = json.job_priority;
                    var job_salary = json.job_salary;

                    $("#count").html("(" + count + ")");

                    if(typeof(close_priority['priority_4'])!="undefined") {
                        $("#on_hold").html(close_priority['priority_4']);
                    }
                    if(typeof(close_priority['priority_9'])!="undefined") {
                        $("#closed_us").html(close_priority['priority_9']);
                    }
                    if(typeof(close_priority['priority_10'])!="undefined") {
                        $("#closed_client").html(close_priority['priority_10']);
                    }

                    $("#on_hold_href").attr("href", 'jobs/priority/'+job_priority[4]+'/'+year);
                    $("#closed_us_href").attr("href", 'jobs/priority/'+job_priority[9]+'/'+year);
                    $("#closed_client_href").attr("href", 'jobs/priority/'+job_priority[10]+'/'+year);

                    // For salary wise count

                    if(typeof(close_priority['under_ten_lacs'])!="undefined") {
                        $("#under_ten_lacs").html(close_priority['under_ten_lacs']);
                    }
                    if(typeof(close_priority['between_ten_to_twenty_lacs'])!="undefined") {
                        $("#between_ten_to_twenty_lacs").html(close_priority['between_ten_to_twenty_lacs']);
                    }
                    if(typeof(close_priority['above_twenty_lacs'])!="undefined") {
                        $("#above_twenty_lacs").html(close_priority['above_twenty_lacs']);
                    }

                    $("#under_ten_lacs_href").attr("href", 'jobs/salary/'+job_salary[0]+'/'+year);
                    $("#between_ten_to_twenty_lacs_href").attr("href", 'jobs/salary/'+job_salary[1]+'/'+year);
                    $("#above_twenty_lacs_href").attr("href", 'jobs/salary/'+job_salary[2]+'/'+year);
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                stateSave : true,
                fnRowCallback: function( Row, Data ) {
                    if ( Data[16] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[16] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[16] == "10" ){
                        $('td:eq(4)', Row).css('background-color', '#FFFFFF');
                    }
                },
            });
        });

        function select_data() {

            $("#job_table").dataTable().fnDestroy();

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
                "order" : [0,'desc'],
                "columnDefs": [ { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 2,},
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "150px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                    { "width": "5px", "targets": 10 },
                ],
                "ajax" : {
                    'url' : 'allclose',
                    'type' : 'get',
                    data : {
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
                    error: function(){

                    }
                },
                initComplete:function( settings, json) {

                    var count = json.recordsTotal;
                    var close_priority = json.close_priority;
                    var job_priority = json.job_priority;
                    var job_salary = json.job_salary;
                    var app_url = "{!! env('APP_URL'); !!}";
                    
                    $("#count").html("(" + count + ")");

                    if(typeof(close_priority['priority_4'])!="undefined") {
                        $("#on_hold").html(close_priority['priority_4']);
                    }
                    if(typeof(close_priority['priority_9'])!="undefined") {
                        $("#closed_us").html(close_priority['priority_9']);
                    }
                    if(typeof(close_priority['priority_10'])!="undefined") {
                        $("#closed_client").html(close_priority['priority_10']);
                    }

                    $("#on_hold_href").attr("href", app_url+'jobs/priority/'+job_priority[4]+'/'+year);
                    $("#closed_us_href").attr("href", app_url+'jobs/priority/'+job_priority[9]+'/'+year);
                    $("#closed_client_href").attr("href", app_url+'jobs/priority/'+job_priority[10]+'/'+year);

                    // For salary wise count

                     if(typeof(close_priority['under_ten_lacs'])!="undefined") {
                        $("#under_ten_lacs").html(close_priority['under_ten_lacs']);
                    }
                    if(typeof(close_priority['between_ten_to_twenty_lacs'])!="undefined") {
                        $("#between_ten_to_twenty_lacs").html(close_priority['between_ten_to_twenty_lacs']);
                    }
                    if(typeof(close_priority['above_twenty_lacs'])!="undefined") {
                        $("#above_twenty_lacs").html(close_priority['above_twenty_lacs']);
                    }

                    $("#under_ten_lacs_href").attr("href", app_url+'jobs/salary/'+job_salary[0]+'/'+year);
                    $("#between_ten_to_twenty_lacs_href").attr("href", app_url+'jobs/salary/'+job_salary[1]+'/'+year);
                    $("#above_twenty_lacs_href").attr("href", app_url+'jobs/salary/'+job_salary[2]+'/'+year);
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                stateSave : true,
                fnRowCallback: function( Row, Data ) {
                    if ( Data[16] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[16] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[16] == "10" ){
                        $('td:eq(4)', Row).css('background-color', '#FFFFFF');
                    }
                },
            });
        }

        function displaySelectedField() {

            var selected_field = $("#selected_field").val();

            if(selected_field == 'Job Position') {

                $(".job_position_cls").show();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Managed By') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").show();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Company Name') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").show();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

               resetInputFields();
            }

            if(selected_field == 'Posting Title') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").show();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Location') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").show();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Min CTC') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").show();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Max CTC') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").show();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'Added Date') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").show();
                $(".no_of_positions_cls").hide();

                resetInputFields();
            }

            if(selected_field == 'No. Of Positions') {

                $(".job_position_cls").hide();
                $(".mb_name_cls").hide();
                $(".company_name_cls").hide();
                $(".posting_title_cls").hide();
                $(".location_cls").hide();
                $(".min_ctc_cls").hide();
                $(".max_ctc_cls").hide();
                $(".added_date_cls").hide();
                $(".no_of_positions_cls").show();
                
                resetInputFields();
            }
        }

        function displayresults() {

            var client_heirarchy = $("#client_heirarchy").val();
            var mb_name = $("#mb_name").val();
            var company_name = $("#company_name").val();
            var posting_title = $("#posting_title").val();
            var location = $("#location").val();
            var min_ctc = $("#min_ctc").val();
            var max_ctc = $("#max_ctc").val();
            var added_date = $("#added_date").val();
            var no_of_positions = $("#no_of_positions").val();
            var year = $("#year").val();

            if(client_heirarchy == '' && mb_name == '' && company_name == '' && posting_title == '' && location == '' && min_ctc == '' && max_ctc == '' && added_date == '' && no_of_positions == '') {

                alert("Please enter field value.");
                return false;
            }
            else {

                var url = '/job/close-search';

                var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="client_heirarchy" value="'+client_heirarchy+'" />' +
                '<input type="text" name="mb_name" value="'+mb_name+'" />' +
                '<input type="text" name="company_name" value="'+company_name+'" />' +
                '<input type="text" name="posting_title" value="'+posting_title+'" />' +
                '<input type="text" name="location" value="'+location+'" />' +
                '<input type="text" name="min_ctc" value="'+min_ctc+'" />' +
                '<input type="text" name="max_ctc" value="'+max_ctc+'" />' +
                '<input type="text" name="added_date" value="'+added_date+'" />' +
                '<input type="text" name="no_of_positions" value="'+no_of_positions+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

                $('body').append(form);
                form.submit();
            }
        }

        function resetInputFields() {

            $("#client_heirarchy").val('');
            $("#client_heirarchy")[0].selectedIndex = '';

            $("#mb_name").val('');
            $("#mb_name")[0].selectedIndex = '';

            $("#company_name").val("");
            $("#posting_title").val("");
            $("#location").val("");
            $("#min_ctc").val("");
            $("#max_ctc").val("");
            $("#added_date").val("");
            $("#no_of_positions").val("");
        }
    </script>
@endsection