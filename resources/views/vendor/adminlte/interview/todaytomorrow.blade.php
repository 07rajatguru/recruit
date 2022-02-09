@extends('adminlte::page')

@section('title','Today & Tomorrow Interview')

@section('content_header')
	<h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Today & Tomorrow Interview ({{ $count }})</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-2">
                <a href="{{ route('interview.today') }}" style="text-decoration: none;color: black;"><div style="width:130px;height:40px;background-color:#8FB1D5;padding:9px 25px;font-weight: 600;border-radius: 22px;">Today ({{ $today_count }})</div></a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('interview.tomorrow') }}" style="text-decoration: none;color: black;"><div style="width:140px;height:40px;background-color:#feb80a;padding:9px 17px;font-weight: 600;border-radius: 22px;">Tomorrow ({{ $tomorrow_count }})</div></a>
            </div>
 		</div>
    </div>

    <br/>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="tt_interview_table">
        <thead>
            <tr>
                <th>No</th>
                <th width="80px">Action</th>
                <th>Posting Title</th>
                <th>Candidate</th>
                <th>Candidate <br/>Contact No.</th>
                <th>Candidate Email</th>
                <th>Interview Date</th>
                <th>Status</th>
                <th>Interview Venue</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @foreach ($todaytomorrow as $todaytomorrows)
                <?php
                $date = date('Y-m-d', strtotime('this week'));
                    if(date("Y-m-d") == date("Y-m-d",strtotime($todaytomorrows['interview_date'])))
                        $color = "#8FB1D5";
                    elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($todaytomorrows['interview_date'])))
                        $color = '#feb80a';
                ?>
            	<tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a title="Show"  class="fa fa-circle" href="{{ route('interview.show',$todaytomorrows['id']) }}"></a>
                        <a title="Edit" class="fa fa-edit" href="{{ route('interview.edit',array($todaytomorrows['id'],'tti')) }}"></a>

                        @permission(('interview-delete'))
                            @include('adminlte::partials.deleteInterview', ['data' => $todaytomorrows, 'name' => 'interview','display_name'=>'Interview'])
                        @endpermission
                    </td>

                    @if(isset($todaytomorrows['remote_working']) && $todaytomorrows['remote_working'] != '')
                        <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $todaytomorrows['client_name'] }} - {{ $todaytomorrows['posting_title'] }} , Remote</td>
                    @else
                        <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $todaytomorrows['client_name'] }} - {{ $todaytomorrows['posting_title'] }} , {{$todaytomorrows['city']}}</td>
                    @endif

                    <td>{{ $todaytomorrows['candidate_fname'] }}</td>
                    <td>{{ $todaytomorrows['contact'] }}</td>
                    <td>{{ $todaytomorrows['candidate_email'] }}</td>
                    <td>{{ date('d-m-Y h:i A',strtotime($todaytomorrows['interview_date'])) }}</td>
                    <td>{{ $todaytomorrows['status'] }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $todaytomorrows['location'] or ''}}</td>
                    
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

            var table = jQuery('#tt_interview_table').DataTable({
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