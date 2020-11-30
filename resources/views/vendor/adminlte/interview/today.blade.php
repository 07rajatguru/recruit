@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> {{$source}} Interview ({{ $count }})</h2>
            </div>
            <div class="pull-right">
                @permission(('send-consolidated-schedule'))
                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-mail" onclick="checkIdsforMail()">Send Mail</button>
                @endpermission
                <a class="btn btn-success" href="{{ route('interview.create') }}">Create New Interview</a>
                <a class="btn btn-primary" href="{{ route('interview.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-2">
                <a href="{{ route('interview.today') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#8FB1D5;padding:9px 25px;font-weight: 600;border-radius: 22px;">Today</div></a>
            </div>&nbsp;
            
            <div class="col-md-2">
                <a href="{{ route('interview.tomorrow') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#feb80a;padding:9px 17px;font-weight: 600;border-radius: 22px;">Tomorrow</div></a>
            </div>&nbsp;

            <div class="col-md-2">
                <a href="{{ route('interview.thisweek') }}" style="text-decoration: none;color: black;"><div style="width:120px;height:40px;background-color:#C4D79B;padding:9px 25px;font-weight: 600;border-radius: 22px;">This Week</div></a>
            </div>&nbsp;

            <div class="col-md-2">
                <a href="{{ route('interview.upcomingprevious') }}" style="text-decoration: none;color: black;"><div style="width:165px;height:40px;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Upcoming/Previous</div></a>
            </div>
        </div>
    </div>

    <br/>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="interview_table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ Form::checkbox('interview[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th width="80px">Action</th>
                <th>Posting Title</th>
                <th>Candidate</th>
                <th>Candidate <br/>Contact No.</th>
                <th>Interview Date</th>
                <th>Interview Venue</th>
                <th>Status</th>
                <th>Candidate Owner</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @foreach ($interViews as $interView)
                <?php
                $date = date('Y-m-d', strtotime('this week'));
                    if(date("Y-m-d") == date("Y-m-d",strtotime($interView['interview_date'])))
                        $color = "#8FB1D5";
                    elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($interView['interview_date'])))
                        $color = '#feb80a';
                    elseif(date('Y-m-d', strtotime($date)) > date("Y-m-d",strtotime($interView['interview_date'])) || date('Y-m-d', strtotime($date.'+6days')) < date("Y-m-d",strtotime($interView['interview_date'])))
                        $color = '#F08080';
                    else
                        $color = '#C4D79B';
                 ?>
                <tr>
                    <td>{{ ++$i }}</td>

                    <td><input type="checkbox" name="interview_ids" value="{{ $interView['id'] }}" class="interview_ids" id="{{ $interView['id'] }}"/></td>

                    <td>
                        <a title="Show" class="fa fa-circle" href="{{ route('interview.show',$interView['id']) }}"></a>
                        
                        <a title="Edit" class="fa fa-edit" href="{{ route('interview.edit',array($interView['id'],'index')) }}"></a>

                        @permission(('interview-delete'))
                            @include('adminlte::partials.deleteInterview', ['data' => $interView, 'name' => 'interview','display_name'=>'Interview'])
                        @endpermission
                    </td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $interView['client_name'] }} - {{ $interView['posting_title'] }} , {{$interView['city']}}</td>
                    <td>{{ $interView['candidate_fname'] }}</td>
                    <td>{{ $interView['contact'] }}</td>
                    <td data-th="Lastrun" data-order="{{$interView['interview_date_ts']}}">{{ date('d-m-Y h:i A',strtotime($interView['interview_date'])) }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $interView['location'] or ''}}</td>
                    <td>{{ $interView['status'] or '' }}</td>
                    <td>{{ $interView['candidate_owner'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="modal-mail" class="modal text-left fade interview-mail" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Schedule Multiple Interview Mail</h1>
                </div>

                {!! Form::open(['method' => 'POST', 'route' => 'interview.multipleinterviewschedule']) !!}

                <div class="modal-body check-id"></div>

                <input type="hidden" name="inter_ids" id="inter_ids" value="">
                <input type="hidden" name="source" id="source" value="{{ $source }}">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="yes-btn">Send</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
    <script>
        $(document).ready(function() {

            $(".date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            $('#allcb').change(function() {
                if($(this).prop('checked')){
                    $('tbody tr td input[type="checkbox"]').each(function () {
                        $(this).prop('checked', true);
                    });
                }else{
                    $('tbody tr td input[type="checkbox"]').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('.interview_ids').change(function () {
                if ($(this).prop('checked')) {
                    if ($('.interview_ids:checked').length == $('.interview_ids').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else {
                    $("#allcb").prop('checked', false);
                }
            });

            var table = jQuery('#interview_table').DataTable({
                responsive: true,
                stateSave : true,
                "pageLength": 50,
                "columnDefs": [ 
                    { "targets": 1, "searchable": false, "orderable": false },
                    { "targets": 2, "searchable": false, "orderable": false },
                ],
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });

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
                url: '/interview/checkidsmail',
                data: { interview_ids:interview_ids, '_token':token },

                success: function(msg) {

                    $(".interview-mail").show();

                    if (msg.success == 'success') {

                        $(".check-id").append(msg.mail);
                        document.getElementById("yes-btn").disabled = false;
                    }
                    else {

                        $(".check-id").append(msg.err);
                        document.getElementById("yes-btn").disabled = true;
                    }
                }
            });
        }
    </script>
@endsection