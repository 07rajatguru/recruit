@extends('adminlte::page')

@section('title', 'Job Closings')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Closing List <span id="count">({{ $count }})</span></h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>--}}
               {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}

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
        </div>
    </div>
    <br/>
    <div class = "table-responsive">
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
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
            <th>Contact <br/> Point</th>
            <th>Edu Qualifications</th>
            <th>Target Industries</th>
            <th>Desired Candidate</th>
        </tr>
        </thead>
        <?php $i=0; ?>
        {{--<tbody>

        @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
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

                </td>
                <td>{{ $job_priority[$value['priority']] }}</td>
                <td>{{ $value['am_name'] or '' }}</td>
                <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>
                <td><a title="Show Associated Candidates" target="_blank" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a></td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['location'] or ''}}</td>
                <td>{{ $value['min_ctc'] or ''}}</td>
                <td>{{ $value['max_ctc'] or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] or '' }}</td>
                <td>{{ $value['created_date'] or ''}}</td>
                <td>{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{{ $value['desired_candidate'] or ''}}</td>

                <td>{{ $value['close_date'] or ''}}</td>


            </tr>
        @endforeach
        </tbody>--}}
    </table>
    </div>
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
                    data : {year:year},
                    error: function(){

                    }
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;
                    var close_priority = json.close_priority;
                    var job_priority = json.job_priority;
                    
                    $("#count").html("(" + count + ")");
                    if(typeof(close_priority['priority_4'])!="undefined"){
                        $("#on_hold").html(close_priority['priority_4']);
                    }
                    if(typeof(close_priority['priority_9'])!="undefined"){
                        $("#closed_us").html(close_priority['priority_9']);
                    }
                    if(typeof(close_priority['priority_10'])!="undefined"){
                        $("#closed_client").html(close_priority['priority_10']);
                    }

                    $("#on_hold_href").attr("href", '/jobs/priority/'+job_priority[4]+'/'+year);
                    $("#closed_us_href").attr("href", '/jobs/priority/'+job_priority[9]+'/'+year);
                    $("#closed_client_href").attr("href", '/jobs/priority/'+job_priority[10]+'/'+year);
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

        function select_data(){

            $("#jo_table").dataTable().fnDestroy();

            var year = $("#year").val();
            $("#jo_table").dataTable({
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
                    data : {year:year},
                    error: function(){

                    }
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;
                    var close_priority = json.close_priority;
                    var job_priority = json.job_priority;
                    
                    $("#count").html("(" + count + ")");
                    if(typeof(close_priority['priority_4'])!="undefined"){
                        $("#on_hold").html(close_priority['priority_4']);
                    }
                    if(typeof(close_priority['priority_9'])!="undefined"){
                        $("#closed_us").html(close_priority['priority_9']);
                    }
                    if(typeof(close_priority['priority_10'])!="undefined"){
                        $("#closed_client").html(close_priority['priority_10']);
                    }

                    $("#on_hold_href").attr("href", '/jobs/priority/'+job_priority[4]+'/'+year);
                    $("#closed_us_href").attr("href", '/jobs/priority/'+job_priority[9]+'/'+year);
                    $("#closed_client_href").attr("href", '/jobs/priority/'+job_priority[10]+'/'+year);
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
    </script>
@endsection