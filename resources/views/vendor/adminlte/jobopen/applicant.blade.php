@extends('adminlte::page')

@section('title', 'Applicant Jobs')

@section('content_header')
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
                <h2>Applicant Jobs List <span id="count">({{ $count or 0}})</span></h2>
            </div>
            
            <div class="pull-right">

                @permission(('update-multiple-jobs-priority'))
                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleJobId()">Update Status</button>
                @endpermission

                @permission(('job-add'))
                    <a class="btn btn-success" href="{{ route('jobopen.create') }}">Create Job Openings
                    </a>
                @endpermission
            </div>
        </div>
    </div>

    @permission(('display-job-priority-count-in-listing'))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                {{-- Changes from 12-12-2019 --}}
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_1" href="" title="Urgent Positions" style="text-decoration: none;color: black;" target="_blank"><div class="priority_1" style="width:max-content;height:40px;background-color:#FF0000;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_1 }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_8" href="" title="Identified candidates" style="text-decoration: none;color: black;" target="_blank"><div style="width:max-content;height:40px;background-color:#92D050;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px" class="priority_8">{{ $priority_8 }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_2" href="" title="New Positions" style="text-decoration: none;color: black;" target="_blank"><div class="priority_2" style="width:max-content;height:40px;background-color:#00B0F0;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_2 }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_3" href="" title="Constant Deliveries needed" style="text-decoration: none;color: black;" target="_blank"><div class="priority_3" style="width:max-content;height:40px;background-color:#FABF8F;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_3 }}
                    </div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_5" href="" title="Revived Positions" style="text-decoration: none;color: black;" target="_blank"><div class="priority_5" style="width:max-content;height:40px;background-color:yellow;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_5 }}</div></a>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                    <a id="priority_7" href="" title="No Deliveries Needed" style="text-decoration: none;color: black;" target="_blank"><div class="priority_7" style="width:max-content;height:40px;background-color:#808080;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px">{{ $priority_7 }}</div></a>
                </div>
            </div>
        </div>
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
                    <th>Applicant <br/>Count</th>
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
            <tbody></tbody>
        </table>
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
                        {!! Form::select('priority', $job_priority,null, array('id'=>'priority','class' => 'form-control')) !!}
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
        $(document).ready(function() {

            $("#job_priority").select2({width:"565px"});
            $("#priority").select2({width:"565px"});

            $("#job_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [12,'desc'],
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
                    { "visible": false,  "targets": 12 },
                ],
                "ajax" : {
                    'url' : 'allapplicant',
                    'type' : 'get',
                    error: function(){

                    },
                },
                initComplete:function( settings, json) {

                    var count = json.recordsTotal;
                    var job_priority = json.job_priority;

                    $("#count").html("(" + count + ")");

                    $("#priority_1").attr("href", '/jobs-applicant/priority/'+job_priority[1]);
                    $("#priority_2").attr("href", '/jobs-applicant/priority/'+job_priority[2]);
                    $("#priority_3").attr("href", '/jobs-applicant/priority/'+job_priority[3]);
                    $("#priority_5").attr("href", '/jobs-applicant/priority/'+job_priority[5]);
                    $("#priority_7").attr("href", '/jobs-applicant/priority/'+job_priority[7]);
                    $("#priority_8").attr("href", '/jobs-applicant/priority/'+job_priority[8]);
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {

                    if ( Data[18] == "0" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[18] == "1" ){
                        $('td:eq(4)', Row).css('background-color', '#FF0000');
                    }
                    else if ( Data[18] == "2" ){
                        $('td:eq(4)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[18] == "3" ){
                        $('td:eq(4)', Row).css('background-color', '#FABF8F');
                    }
                    else if ( Data[18] == "4" ){
                        $('td:eq(4)', Row).css('background-color', '#B1A0C7');
                    }
                    else if ( Data[18] == "5" ){
                        $('td:eq(4)', Row).css('background-color', 'yellow');
                    }
                    else if ( Data[18] == "6" ){
                        $('td:eq(4)', Row).css('background-color', '');
                    }
                    else if ( Data[18] == "7" ){
                        $('td:eq(4)', Row).css('background-color', '#808080');
                    }
                    else if ( Data[18] == "8" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[18] == "9" ){
                        $('td:eq(4)', Row).css('background-color', '#92D050');
                    }
                    else if ( Data[18] == "10" ){
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
                    }
                    else{
                        $(".status").hide();
                        $(".error").empty();
                        $('#submit').hide();
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

            var client_heirarchy=$("#client_heirarchy").val();
            //var year = $("#year").val();
            
            var url = '/jobs';
            if(client_heirarchy>=0)
            {
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
    </script>
@endsection