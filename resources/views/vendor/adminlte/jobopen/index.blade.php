@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('customs_css')
<style>
    .select2-selection__rendered[title="Urgent Positions"] {
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
    }
</style>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List <span id="count">({{ $count or 0}})</span></h2>
            </div>

            @permission('job-create')
            <div class="pull-right">
                <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleJobId()">Update Status</button>
                <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
            </div>
            @endpermission

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>--}}
               {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <strong>Select Financial Year:</strong>
                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group" style="margin-top: 19px;">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>
    </div>
    <br/>

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
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_0" href="" title="-None-" style="text-decoration: none;color: black;"><div class="priority_0" style="width:max-content;height:40px;padding:9px 25px;border:solid 1px;font-weight: 600;border-radius: 22px;">{{ $priority_0 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_1" href="" title="Urgent Positions" style="text-decoration: none;color: black;"><div class="priority_1" style="width:max-content;height:40px;background-color:#FF0000;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $priority_1 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_2" href="" title="New Positions" style="text-decoration: none;color: black;"><div class="priority_2" style="width:max-content;height:40px;background-color:#00B0F0;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $priority_2 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_3" href="" title="Constant Deliveries needed" style="text-decoration: none;color: black;"><div class="priority_3" style="width:max-content;height:40px;background-color:#FABF8F;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $priority_3 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_5" href="" title="Revived Positions" style="text-decoration: none;color: black;"><div class="priority_5" style="width:max-content;height:40px;background-color:yellow;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $priority_5 }}</div></a>
            </div>
        
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_6" href="" title="Constant Deliveries needed for very old positions where many deliveries are done but no result yet" style="text-decoration: none;color: black;"><div class="priority_6" style="width:max-content;height:40px;padding:9px 25px;border:solid 1px;font-weight: 600;border-radius: 22px;">{{ $priority_6 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_7" href="" title="No Deliveries Needed" style="text-decoration: none;color: black;"><div class="priority_7" style="width:max-content;height:40px;background-color:#808080;padding:9px 25px;font-weight: 600;border-radius: 22px;">{{ $priority_7 }}</div></a>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="priority_8" href="" title="Identified candidates" style="text-decoration: none;color: black;"><div style="width:max-content;height:40px;background-color:#92D050;padding:9px 25px;font-weight: 600;border-radius: 22px;" class="priority_8">{{ $priority_8 }}</div></a>

            </div>
        </div>
    </div>

     {{-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box-body col-xs-1 col-sm-1 col-md-1" style="width: 7%; float:left; margin: 5px">
                <div style="height:30px;width:70px;background-color:#FFFFFF;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">None ({{ $priority_0 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 15%; float:left; margin: 5px">
                <div style="height:30px;width:150px;background-color:#FF0000;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Urgent Positions ({{ $priority_1 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 13%; float:left; margin: 5px">
                <div style="height:30px;width:130px;background-color:#00B0F0;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">New Positions ({{ $priority_2 }})
                </div>
            </div>

             <div class="box-body col-xs-3 col-sm-3 col-md-3" style="width: 21.5%; float:left; margin: 5px">
                <div style="height:30px;width:220px;background-color:#FABF8F;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Constant Deliveries needed ({{ $priority_3 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 25%; float:left; margin: 5px">
                <div style="height:30px;width:190px;background-color:#92D050;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Identified Candidates ({{ $priority_4 }})
                </div>
            </div>
        </div>
    </div>--}}

    &nbsp;

    {{--<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 16%; float:left; margin: 5px">
                <div style="height:30px;width:160px;background-color:yellow;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Revived Positions ({{ $priority_5 }})
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3" style="width: 19%; float:left; margin: 5px">
                <div style="height:30px;width:190px;background-color:#808080;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">No Deliveries Needed ({{ $priority_7 }})
                </div>
            </div>

             <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 9.5%; float:left; margin: 5px">
                <div style="height:30px;width:90px;background-color:#B1A0C7;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">On Hold ({{ $priority_8 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 13%; float:left; margin: 5px">
                <div style="height:30px;width:130px;background-color:#92D050;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Closed By Us ({{ $priority_9 }})
                </div>
            </div>

             <div class="box-body col-xs-2 col-sm-2 col-md-2" style="float:left; margin: 5px">
                <div style="height:30px;width:220px;background-color:#FFFFFF;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Closed By Client ({{ $priority_10 }})
                </div>
            </div>
        </div>
    </div>--}}

    &nbsp;

    {{--<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <div style="height:30px;width:700px;background-color:#808080;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Constant Deliveries needed for very old positions where many deliveries are done but no result yet ({{ $priority_6 }})
                </div>
            </div>

        </div>
    </div>--}}
    
    &nbsp;
    
    <div class = "table-responsive">
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
        <thead>
        <tr>
            <th>No</th>
            <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
            <th>Action</th>
            <th>MB</th>
            <th>Company Name</th>
            {{--<th>Level</th>--}}
            <th>Position Title</th>
            <th>CA</th>
            <th>Location</th>
            <th>Min CTC<br/>(in Lacs)</th>
            <th>Max CTC<br/>(in Lacs)</th>
            <th>HR/Coordinator  <br/> Name</th>
            <th>Added Date</th>
            <th>No. Of <br/> Positions</th>
            <th>Edu Qualifications</th>
            <th>Target Industries</th>
            <th>Desired Candidate</th>

            {{--<th>Target Date</th>--}}

        </tr>
        </thead>
        <?php $i=0; ?>
        {{--<tbody>

        @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                @if(isset($value['access']) && $value['access']==1)
                    <td>{{ Form::checkbox('job_ids',$value['id'],null,array('class'=>'multiple_jobs' ,'id'=>$value['id'] )) }}</td>
                @else
                    <td></td>
                @endif
                <td>
                    <a title="Show"  class="fa fa-circle" href="{{ route('jobopen.show',$value['id']) }}"></a>

                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Edit" class="fa fa-edit" href="{{ route('jobopen.edit',$value['id']) }}"></a>
                    @endif

                    @if(isset($value['access']) && $value['access']==1)
                    @include('adminlte::partials.jobstatus', ['data' => $value, 'name' => 'jobopen','display_name'=>'More Information'])
                    @endif

                    @if($isSuperAdmin)
                    @include('adminlte::partials.jobdelete', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job'])
                    @endif
                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Clone Job"  class="fa fa-clone" href="{{ route('jobopen.clone',$value['id']) }}"></a>
                    @endif
                  

                </td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['am_name'] or '' }}</td>
                <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>
                <td ><a title="Show Associated Candidates" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a></td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['location'] or ''}}</td>
                <td>{{ $value['min_ctc'] or ''}}</td>
                <td>{{ $value['max_ctc'] or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] or '' }}</td>
                <td>{{ $value['created_date'] or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{!! $value['desired_candidate'] or ''!!}</td>

                <td>{{ $value['close_date'] or ''}}</td>


            </tr>
        @endforeach
        </tbody>
    </table>--}}
    </div>

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
                    {!! Form::select('job_priority', $job_priority,null, array('id'=>'job_priority','class' => 'form-control')) !!}
                </div>
                <div class="error"></div>
            </div>

            <input type="hidden" name="job_ids" id="job_ids" value="">

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
        $(document).ready(function(){
            
            var year = $("#year").val();
            $("#jo_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0,'desc'],
                "columnDefs": [ { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 2, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                            ],
                "ajax" : {
                    'url' : 'jobs/all',
                    data : {year:year},
                    'type' : 'get',
                    error: function(){

                    },
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;
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
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                stateSave : true,
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
                }
            });

            $('#allcb').change(function(){
                if($(this).prop('checked')){
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', true);
                    });
                }else{
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

        $("#priority").select2();

        });

        function select_data(){

            $("#jo_table").dataTable().fnDestroy();

            var year = $("#year").val();
            $("#jo_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0,'desc'],
                "columnDefs": [ { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 2, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                            ],
                "ajax" : {
                    'url' : 'jobs/all',
                    data : {year:year},
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
                stateSave : true,
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
                }
            });
        }

        function multipleJobId(){
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
                success: function(msg){
                    $(".priority").show();
                    if (msg.success == 'success') {
                        $(".status").show();
                        $(".error").empty();
                        $('#submit').removeAttr('disabled');
                    }
                    else{
                        $(".status").hide();
                        $(".error").empty();
                        $('#submit').attr('disabled','disabled');
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
    </script>
@endsection