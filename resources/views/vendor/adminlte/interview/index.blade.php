@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Interview <span id="count">({{ $count }})</span></h2>
            </div>
            <div class="pull-right">
                @permission(('send-consolidated-schedule'))
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-mail" onclick="checkIdsforMail()">Send Mail</button>

                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleInterviewStatus()">Update Status</button>
                @endpermission
                <a class="btn btn-success" href="{{ route('interview.create') }}">Add New Interview</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-2 col-sm-3">
                <a href="{{ route('interview.today') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#8FB1D5;padding:9px 25px;font-weight: 600;border-radius: 22px;">Today</div></a>
            </div>
            <div class="col-md-2 col-sm-3">
                <a href="{{ route('interview.tomorrow') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#feb80a;padding:9px 17px;font-weight: 600;border-radius: 22px;">Tomorrow</div></a>
            </div>
            <div class="col-md-2 col-sm-3">
                <a href="{{ route('interview.thisweek') }}" style="text-decoration: none;color: black;"><div style="width:120px;height:40px;background-color:#C4D79B;padding:9px 25px;font-weight: 600;border-radius: 22px;">This Week</div></a>
            </div>
            <div class="col-md-2 col-sm-3">
                <a href="{{ route('interview.upcomingprevious') }}" style="text-decoration: none;color: black;"><div style="width:165px;height:40px;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Upcoming/Previous</div></a>
            </div>
        </div>
    </div>

    <br>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="interview_table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ Form::checkbox('interview[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Posting Title</th>
                <th>Candidate</th>
                <th>Candidate <br/>Contact No.</th>
                <th>Candidate Email</th>
                <th>Interview Date</th>
                <th>Candidate Owner</th>
                <th>Status</th>
                <th>Interview Venue</th>
            </tr>
        </thead>
        <?php $i=0; ?>

        <tbody>
        </tbody>
    </table>

    <div id="modal-mail" class="modal text-left fade interview-mail" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Schedule Multiple Interview Mail</h1>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'interview.multipleinterviewschedule','id'=>'subject_form'])!!}
                <div class="modal-body check-id">
                    
                </div>
                <input type="hidden" name="inter_ids" id="inter_ids" value="">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="yes-btn">Send</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modal-status" class="modal text-left fade interview_status" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Change Interview Status</h1>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'interview.multistatus']) !!}
                <div class="modal-body">
                    <div class="status">
                        <strong>Select Interview Status :</strong> <br>
                        {!! Form::select('status', $interview_status,null, array('id'=>'status','class' => 'form-control')) !!}
                    </div>
                    <div class="error"></div>
                </div>

                <input type="hidden" name="multi_inter_ids" id="multi_inter_ids" value="">
                <input type="hidden" name="multi_inter_year" id="multi_inter_year" value="">

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
    <script>
        $(document).ready(function() {

            $("#subject_form").validate({

                rules: {
                    "subject": {
                        required: true
                    }
                },
                messages: {
                    "subject": {
                        required: "Subject is Required Field."
                    },
                }
            });

            $(".date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var year = $("#year").val();

            $("#interview_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [7,'desc'],
                "columnDefs": [ { "targets": 1, "searchable": false, "orderable": false },
                                { "targets": 2, "searchable": false, "orderable": false },
                            ],
                "ajax" : {
                    'url' : 'interview/all',
                    data : {year:year},
                    'type' : 'get',
                    error: function(){

                    }
                },
                initComplete:function( settings, json) {
                    var count = json.recordsTotal;
                    $("#count").html("(" + count + ")");
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {
                    $('td:eq(3)', Row).css('background-color', Data[10]);
                }
            });

            $('#allcb').change(function() {
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
            $('.interview_ids').change(function() {
                if ($(this).prop('checked')) {
                    if ($('.interview_ids:checked').length == $('.interview_ids').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else{
                    $("#allcb").prop('checked', false);
                }
            });
        });

        function select_data() {

            $("#interview_table").dataTable().fnDestroy();

            var year = $("#year").val();

            $("#interview_table").dataTable( {

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [6,'desc'],
                "columnDefs": [ 
                    { "targets": 1, "searchable": false, "orderable": false },
                    { "targets": 2, "searchable": false, "orderable": false },
                ],
                "ajax" : {
                    'url' : 'interview/all',
                    data : {year:year},
                    'type' : 'get',
                    error: function() {
                    },
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;
                    $("#count").html("(" + count + ")");
                },
                responsive: true,
                "pageLength": 50,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {
                    $('td:eq(3)', Row).css('background-color', Data[10]);
                }
            });
        }

        function checkIdsforMail() {
            var token = $('input[name="csrf_token"]').val();
            var interview_ids = new Array();

            $("input:checkbox[name=interview_ids]:checked").each(function(){
                interview_ids.push($(this).val());
            });

            $("#inter_ids").val(interview_ids);
            $(".check-id").empty();

            $.ajax({

                type: 'POST',
                url: 'interview/checkidsmail',
                data: { interview_ids:interview_ids, '_token':token },
                success: function(msg){   
                    $(".interview-mail").show();
                    if (msg.success == 'success') {
                        $(".check-id").append(msg.mail);
                        document.getElementById("yes-btn").disabled = false;
                    }
                    else{
                        $(".check-id").append(msg.err);
                        document.getElementById("yes-btn").disabled = true;
                    }
                }
            });
        }

        function multipleInterviewStatus() {

            var token = $('input[name="csrf_token"]').val();
            var year = $("#year").val();
            
            var interview_ids = new Array();

            $("input:checkbox[name=interview_ids]:checked").each(function(){
                interview_ids.push($(this).val());
            });

            $("#multi_inter_ids").val(interview_ids);
            $("#multi_inter_year").val(year);

            $.ajax({

                type: 'POST',
                url: 'interview/checkidsmail',
                data: { interview_ids:interview_ids, '_token':token },
                success: function(msg) {
                    
                    $(".interview_status").show();

                    if (msg.success == 'success') {

                        $(".status").show();
                        $(".error").empty();
                        $('#submit').show();
                    }
                    else {

                        $(".status").hide();
                        $(".error").empty();
                        $('#submit').hide();
                        $(".error").append(msg.err);
                    }
                }
            });
        }
    </script>
@endsection