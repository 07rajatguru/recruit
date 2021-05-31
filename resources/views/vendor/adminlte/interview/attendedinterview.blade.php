@extends('adminlte::page')

@section('title','Attended Interview')

@section('content_header')
	<h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            	<h2>Attended Interview({{$count}})</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-2">
                <div style="width:100px;height:40px;background-color:#8FB1D5;padding:9px 25px;font-weight: 600;border-radius: 22px;">Today</div>
            </div>&nbsp;
            
            <div class="col-md-2">
                <div style="width:100px;height:40px;background-color:#feb80a;padding:9px 17px;font-weight: 600;border-radius: 22px;">Tomorrow</div>
            </div>&nbsp;
            
            <div class="col-md-2">
                <div style="width:120px;height:40px;background-color:#C4D79B;padding:9px 25px;font-weight: 600;border-radius: 22px;">This Week</div>
            </div>&nbsp;
            
            <div class="col-md-2">
                <div style="width:165px;height:40px;background-color:#F08080;padding:9px 17px;font-weight: 600;border-radius: 22px;">Upcoming/Previous</div>
            </div>
        </div>
    </div>

    <br>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

     <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="attended_interview_table">
        <thead>
            <tr>
                <th>No</th>
                <th width="80px">Action</th>
                <th>Posting Title</th>
                <th>Candidate</th>
                <th>Candidate <br/>Contact No.</th>
                <th>Interview Date</th>
                <th>Interview Venue</th>
                <th>Status</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @foreach ($attended_interview as $attendedinterview)
                <?php
                $date = date('Y-m-d', strtotime('this week'));
                    if(date("Y-m-d") == date("Y-m-d",strtotime($attendedinterview['interview_date'])))
                        $color = "#8FB1D5";
                    elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($attendedinterview['interview_date'])))
                        $color = '#feb80a';
                     elseif(date('Y-m-d', strtotime($date)) > date("Y-m-d",strtotime($attendedinterview['interview_date'])) || date('Y-m-d', strtotime($date.'+6days')) < date("Y-m-d",strtotime($attendedinterview['interview_date'])))
                        $color = '#F08080';
                    else
                        $color = '#C4D79B';
                ?>
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a title="Show"  class="fa fa-circle" href="{{ route('interview.show',$attendedinterview['id']) }}"></a>
                        <a title="Edit" class="fa fa-edit" href="{{ route('interview.edit',array($attendedinterview['id'],'ai')) }}"></a>

                        @permission(('interview-delete'))
                            @include('adminlte::partials.deleteInterview', ['data' => $attendedinterview, 'name' => 'interview','display_name'=>'Interview'])
                        @endpermission
                    </td>

                    @if(isset($attendedinterview['remote_working']) && $attendedinterview['remote_working'] != '')

                        <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $attendedinterview['client_name'] }} - {{ $attendedinterview['posting_title'] }} , Remote</td>
                    @else
                        <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $attendedinterview['client_name'] }} - {{ $attendedinterview['posting_title'] }} , {{$attendedinterview['city']}}</td>
                    @endif
                    
                    <td>{{ $attendedinterview['candidate_fname'] }}</td>
                    <td>{{ $attendedinterview['contact'] }}</td>
                    <td>{{ date('d-m-Y h:i A',strtotime($attendedinterview['interview_date'])) }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $attendedinterview['location'] or ''}}</td>
                    <td>{{ $attendedinterview['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script>
        $(document).ready(function() {
            
            $(".date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = jQuery('#attended_interview_table').DataTable( {
                responsive: true,
                "pageLength": 50,
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection