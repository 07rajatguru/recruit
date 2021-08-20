@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>
@stop

@section('customs_css')

<style>
   /* .select2-selection__rendered[title="Urgent Positions"] {
      background-color: #FF0000;
    }
    .select2-selection__rendered[title="New Positions"] {
      background-color: #00B0F0;
    }
    .select2-selection__rendered[title="Constant Deliveries needed"] {
      background-color: #FABF8F;
    }
    .select2-selection__rendered[title="On Hold"] {
      background-color: #B1A0C7;
    }
    .select2-selection__rendered[title="Revived Positions"] {
      background-color: yellow;
    }
    .select2-selection__rendered[title="No Deliveries Needed"] {
      background-color: #808080;
    }
    .select2-selection__rendered[title="Identified candidates"] {
      background-color: #92D050;
    }
    .select2-selection__rendered[title="Closed By Us"] {
      background-color: #92D050;
    }
    .select2-selection__rendered[title="Closed By Client"] {
      background-color: #FFFFFF;
    }

    .select2-results__option[id*="Urgent Positions"] {
      background-color: #FF0000;
    }
    .select2-results__option[id*="New Positions"] {
      background-color: #00B0F0;
    }
    .select2-results__option[id*="Constant Deliveries needed"] {
      background-color: #FABF8F;
    }
    .select2-results__option[id*="On Hold"] {
      background-color: #B1A0C7;
    }
    .select2-results__option[id*="Revived Positions"] {
      background-color: yellow;
    }
    .select2-results__option[id*="No Deliveries Needed"] {
      background-color: #808080;
    }
    .select2-results__option[id*="Identified candidates"] {
      background-color: #92D050;
    }
    .select2-results__option[id*="Closed By Us"] {
      background-color: #92D050;
    }
    .select2-results__option[id*="Closed By Client"] {
      background-color: #FFFFFF;
    }*/
</style>
@endsection

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
                <h2>Job Openings List <span id="count">({{ $count or 0}})</span></h2>
            </div>
            
            <div class="pull-right">
                @if(!$isClient)
                
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mastersearchmodal">Master Search</button>

                    @permission(('update-multiple-jobs-priority'))
                        <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleJobId()">Update Status</button>
                    @endpermission

                    @permission(('job-add'))
                        <a class="btn btn-success" href="{{ route('jobopen.create') }}">Create Job Openings</a>
                    @endpermission
                @endif
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}
            </div>
        </div>
    </div>
    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
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
    </div> <br/> --}}

    {{--<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-md-3">
                <label >Search Open Jobs by selecting priority : </label>
            </div>

            <div class="col-md-3">
                <select class="form-control" id="priority">
                    <option value="-None-">Select Job Priority</option>
                    <option value="Urgent Positions" id="Urgent Positions">Urgent Positions</option>
                    <option value="New Positions" id="New Positions">New Positions</option>
                    <option value="Constant Deliveries needed" id="Constant Deliveries needed">Constant Deliveries needed</option>
                    <option value="On Hold" id="On Hold">On Hold</option>
                    <option value="Revived Positions" id="Revived Positions">Revived Positions</option>
                    <option value="Constant Deliverie needed for very old positions where many deliveries are done but no result yet">Constant Deliveries needed for very old positions where many deliveries are done but no result yet</option>
                    <option value="No Deliveries Needed" id="No Deliveries Needed">No Deliveries Needed</option>
                    <option value="Identified candidates" id="Identified candidates">Identified candidates</option>
                    <option value="Closed By Us" id="Closed By Us">Closed By Us</option>
                    <option value="Closed By Client" id="Closed By Client">Closed By Client</option>
                </select>
            </div>

            <div class="col-md-3">
                <button type="button" class="btn btn-success" onclick="prioritywise()">Submit</button></div>
            <div class="col-md-3">

            </div>
        </div>
    </div>--}}
    
    @permission(('display-job-priority-count-in-listing'))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_1" href="" title="Urgent Positions" style="text-decoration: none;color: black;"><div class="priority_1" style="width:max-content;height:40px;background-color:#FF0000;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><span id="priority_1_count">{{ $priority_1 }}</span></div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_8" href="" title="Identified candidates" style="text-decoration: none;color: black;"><div style="width:max-content;height:40px;background-color:#92D050;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px" class="priority_8"><span id="priority_8_count">{{ $priority_8 }}</span></div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_2" href="" title="New Positions" style="text-decoration: none;color: black;"><div class="priority_2" style="width:max-content;height:40px;background-color:#00B0F0;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><span id="priority_2_count">{{ $priority_2 }}</span></div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_3" href="" title="Constant Deliveries needed" style="text-decoration: none;color: black;"><div class="priority_3" style="width:max-content;height:40px;background-color:#FABF8F;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><span id="priority_3_count">{{ $priority_3 }}</span></div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_5" href="" title="Revived Positions" style="text-decoration: none;color: black;"><div class="priority_5" style="width:max-content;height:40px;background-color:yellow;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><span id="priority_5_count">{{ $priority_5 }}</span></div>
                    </a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_7" href="" title="No Deliveries Needed" style="text-decoration: none;color: black;"><div class="priority_7" style="width:max-content;height:40px;background-color:#808080;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><span id="priority_7_count">{{ $priority_7 }}</span>
                    </div></a>
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
                
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="under_ten_lacs" href="" title="Under 10 Lacs" style="text-decoration: none;color: black;"><div class="under_ten_lacs" style="width:max-content;height:40px;background-color:#FFCC00;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"><10L <span id="under_ten_lacs_count">({{ $under_ten_lacs }})</span></div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="between_ten_to_twenty_lacs" href="" title="Between 10-20 Lacs" style="text-decoration: none;color: black;"><div style="width:max-content;height:40px;background-color:#e87992;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px" class="between_ten_to_twenty_lacs"> 10-20L <span id="between_ten_to_twenty_lacs_count">({{ $between_ten_to_twenty_lacs }})</span></div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="above_twenty_lacs" href="" title="Above 20 Lacs" style="text-decoration: none;color: black;"><div class="above_twenty_lacs" style="width:max-content;height:40px;background-color:#f17a40;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px"> >20L <span id="above_twenty_lacs_count">({{ $above_twenty_lacs }})</span></div></a>
                </div>
            </div>
        </div><br/>
    @endpermission
    
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
                    <th>Updated Date</th>
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
    </div>

    @include('adminlte::partials.jobmastersearchmodal', ['field_list' => $field_list,'client_hierarchy_name' => $client_hierarchy_name,'users' => $users,'min_ctc_array' => $min_ctc_array,'max_ctc_array' => $max_ctc_array,'type' => 'Job Openings'])

    <div id="modal-status" class="modal text-left fade priority" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Select Job Priority</h1>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'jobopen.mutijobpriority']) !!}
                <div class="modal-body">
                    <div class="status">
                        <strong>Select Job Priority :</strong> <br>
                        {!! Form::select('priority', $job_priority,null, array('id'=>'priority','class' => 'form-control')) !!}
                    </div>
                    <div class="error"></div>
                </div>

                <input type="hidden" name="job_ids" id="job_ids" value="">
                <input type="hidden" name="job_page" id="job_page" value="Job Open">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#job_priority").select2({width:"565px"});
            $("#priority").select2({width:"565px"});
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
                "order" : [11,'desc'],
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
                    { "visible": false,  "targets": 11 },
                ],
                "ajax" : {
                    'url' : 'jobs/all',
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
                    'type' : 'get',
                    error: function() {

                    },
                },
                initComplete:function( settings, json) {

                    var job_priority = json.job_priority;
                    var job_salary = json.job_salary;

                    /*$("#priority_0").attr("href", '/jobs/priority/'+job_priority[0]+'/'+year);
                    $("#priority_1").attr("href", '/jobs/priority/'+job_priority[1]+'/'+year);
                    $("#priority_2").attr("href", '/jobs/priority/'+job_priority[2]+'/'+year);
                    $("#priority_3").attr("href", '/jobs/priority/'+job_priority[3]+'/'+year);
                    $("#priority_5").attr("href", '/jobs/priority/'+job_priority[5]+'/'+year);
                    $("#priority_6").attr("href", '/jobs/priority/'+job_priority[6]+'/'+year);
                    $("#priority_7").attr("href", '/jobs/priority/'+job_priority[7]+'/'+year);
                    $("#priority_8").attr("href", '/jobs/priority/'+job_priority[8]+'/'+year);*/

                    $("#priority_0").attr("href", '/jobs/priority/'+job_priority[0]);
                    $("#priority_1").attr("href", '/jobs/priority/'+job_priority[1]);
                    $("#priority_2").attr("href", '/jobs/priority/'+job_priority[2]);
                    $("#priority_3").attr("href", '/jobs/priority/'+job_priority[3]);
                    $("#priority_5").attr("href", '/jobs/priority/'+job_priority[5]);
                    $("#priority_6").attr("href", '/jobs/priority/'+job_priority[6]);
                    $("#priority_7").attr("href", '/jobs/priority/'+job_priority[7]);
                    $("#priority_8").attr("href", '/jobs/priority/'+job_priority[8]);

                    // For salary wise display job listing

                    $("#under_ten_lacs").attr("href", '/jobs/salary/'+job_salary[0]);
                    $("#between_ten_to_twenty_lacs").attr("href", '/jobs/salary/'+job_salary[1]);
                    $("#above_twenty_lacs").attr("href", '/jobs/salary/'+job_salary[2]);
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {

                    if ( Data[17] == "0" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[17] == "1" ){
                        $('td:eq(4)', Row).css('background-color', '#FF0000');
                    }
                    else if ( Data[17] == "2" ){
                        $('td:eq(4)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[17] == "3" ){
                        $('td:eq(4)', Row).css('background-color', '#FABF8F');
                    }
                    else if ( Data[17] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[17] == "5" ){
                        $('td:eq(4)', Row).css('background-color', 'yellow');
                    }
                    else if ( Data[17] == "6" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[17] == "7" ){
                        $('td:eq(4)', Row).css('background-color', '#808080');
                    }
                    else if ( Data[17] == "8" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[17] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[17] == "10" ){
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

        /*function select_data(){

            $("#job_table").dataTable().fnDestroy();

            var year = $("#year").val();
            var client_heirarchy = $("#client_heirarchy").val();

            $("#job_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [11,'desc'],
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
                    { "width": "5px", "targets": 10 },
                    { "visible": false,  "targets": 11 },
                ],
                "ajax" : {
                    'url' : 'jobs/all',
                    data : {year:year,client_heirarchy:client_heirarchy},
                    'type' : 'get',
                    error: function(){

                    },
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;
                    var priority = json.priority;
                    var job_priority = json.job_priority;

                    $("#count").html("(" + count + ")");

                    $("#priority_0").attr("href", '/jobs/priority/'+job_priority[0]+'/'+year);
                    $("#priority_1").attr("href", '/jobs/priority/'+job_priority[1]+'/'+year);
                    $("#priority_2").attr("href", '/jobs/priority/'+job_priority[2]+'/'+year);
                    $("#priority_3").attr("href", '/jobs/priority/'+job_priority[3]+'/'+year);
                    $("#priority_5").attr("href", '/jobs/priority/'+job_priority[5]+'/'+year);
                    $("#priority_6").attr("href", '/jobs/priority/'+job_priority[6]+'/'+year);
                    $("#priority_7").attr("href", '/jobs/priority/'+job_priority[7]+'/'+year);
                    $("#priority_8").attr("href", '/jobs/priority/'+job_priority[8]+'/'+year);

                    if(typeof(priority['priority_0'])!="undefined"){
                        $(".priority_0").html(priority['priority_0']);
                    }
                    if(typeof(priority['priority_1'])!="undefined"){
                        $(".priority_1").html(priority['priority_1']);
                    }
                    if(typeof(priority['priority_2'])!="undefined"){
                        $(".priority_2").html(priority['priority_2']);
                    }
                    if(typeof(priority['priority_3'])!="undefined"){
                        $(".priority_3").html(priority['priority_3']);
                    }
                    if(typeof(priority['priority_5'])!="undefined"){
                        $(".priority_5").html(priority['priority_5']);
                    }
                    if(typeof(priority['priority_6'])!="undefined"){
                        $(".priority_6").html(priority['priority_6']);
                    }
                    if(typeof(priority['priority_7'])!="undefined"){
                        $(".priority_7").html(priority['priority_7']);
                    }
                    if(typeof(priority['priority_8'])!="undefined"){
                        $(".priority_8").html(priority['priority_8']);
                    }
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {
                    if ( Data[17] == "0" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[17] == "1" ){
                        $('td:eq(4)', Row).css('background-color', '#FF0000');
                    }
                    else if ( Data[17] == "2" ){
                        $('td:eq(4)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[17] == "3" ){
                        $('td:eq(4)', Row).css('background-color', '#FABF8F');
                    }
                    else if ( Data[17] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[17] == "5" ){
                        $('td:eq(4)', Row).css('background-color', 'yellow');
                    }
                    else if ( Data[17] == "6" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[17] == "7" ){
                        $('td:eq(4)', Row).css('background-color', '#808080');
                    }
                    else if ( Data[17] == "8" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[17] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[17] == "10" ){
                        $('td:eq(4)', Row).css('background-color', '#FFFFFF');
                    }
                    else{
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                },
                stateSave : true,
            });
        }*/

        function multipleJobId() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var job_ids = new Array();

            $("input:checkbox[name=job_ids]:checked").each(function(){
                job_ids.push($(this).val());
            });

            $("#job_ids").val(job_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/jobs/checkJobId',
                data : {job_ids : job_ids, '_token':token},
                dataType : 'json',
                success: function(msg) {
                    
                    $(".priority").show();
                    if (msg.success == 'success') {
                        $(".status").show();
                        $(".error").empty();
                        $('#submit').show();
                        //$('#submit').removeAttr('disabled');
                    }
                    else{
                        $(".status").hide();
                        $(".error").empty();
                        $('#submit').hide();
                        //$('#submit').attr('disabled','disabled');
                        $(".error").append(msg.err);
                    }
                }
            });
        }

        function prioritywise() {
            var priority = $("#priority").val();

            var url = '/jobs/priority/'+priority;

            var form = $('<form action="'+url+ '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="priority" value="'+priority+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        function getJobsByPosition() {

            var client_heirarchy = $("#client_heirarchy").val();
            //var year = $("#year").val();
            
            var url = '/jobs';
            if(client_heirarchy>=0) {

                /*var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="client_heirarchy" value="'+client_heirarchy+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');*/

                var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="client_heirarchy" value="'+client_heirarchy+'" />' +
                '</form>');

                $('body').append(form);
                form.submit();
            }
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

                $("#client_heirarchy").val('0');
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

            console.log(client_heirarchy);

            if(client_heirarchy == '' && mb_name == '' && company_name == '' && posting_title == '' && location == '' && min_ctc == '' && max_ctc == '' && added_date == '' && no_of_positions == '') {

                alert("Please enter field value.");
                return false;
            }
            else {

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

                var url = '/job-search';

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
                '</form>');

                $('body').append(form);
                form.submit();
            }
        }
    </script>
@endsection