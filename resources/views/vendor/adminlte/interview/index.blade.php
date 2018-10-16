@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Interview ({{ $count }})</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('interview.create') }}"> Create New Interview</a>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-2">
                <a href="{{ route('interview.today') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#8FB1D5;padding:9px 25px;font-weight: 600;border-radius: 22px;">Today</div></a>
            </div>
            &nbsp;
            <div class="col-md-2">
                <a href="{{ route('interview.tomorrow') }}" style="text-decoration: none;color: black;"><div style="width:100px;height:40px;background-color:#feb80a;padding:9px 17px;font-weight: 600;border-radius: 22px;">Tomorrow</div></a>
            </div>
            &nbsp;
            <div class="col-md-2">
                <a href="{{ route('interview.thisweek') }}" style="text-decoration: none;color: black;"><div style="width:120px;height:40px;background-color:#C4D79B;padding:9px 25px;font-weight: 600;border-radius: 22px;">This Week</div></a>
            </div>
            &nbsp;
            <div class="col-md-2">
                <a href="{{ route('interview.upcomingprevious') }}" style="text-decoration: none;color: black;"><div style="width:165px;height:40px;background-color:#ffffff;padding:9px 17px;font-weight: 600;border-radius: 22px;">Upcoming/Previous</div></a>
            </div>

        </div>
    </div>

    <br>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="interview_table">
        <thead>
            <tr>
                <th>No</th>
                {{--<th>Interview Name</th>--}}
                <th>Posting Title</th>
                <th>Candidate</th>
                <th>Candidate <br/>Contact No.</th>
               {{-- <th>Client</th>--}}
                <th>Interview Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Candidate Owner</th>
                <th width="280px">Action</th>
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
                $color = '';
            else
                $color = '#C4D79B';
         ?>
            <tr>
                <td>{{ ++$i }}</td>
                {{--<td style="background-color: {{ $color }}">{{ $interView['interview_name'] or '' }}</td>--}}
                <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $interView['client_name'] }} - {{ $interView['posting_title'] }} , {{$interView['city']}}</td>
                <td>{{ $interView['candidate_fname'] }}</td>
                <td>{{ $interView['contact'] }}</td>
             {{--   <td>{{ $interView['client_name'] or ''}}</td>--}}
                <td data-th="Lastrun" data-order="{{$interView['interview_date_ts']}}">{{ date('d-m-Y h:i A',strtotime($interView['interview_date'])) }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $interView['location'] or ''}}</td>
                <td>{{ $interView['status'] or '' }}</td>
                <td>{{ $interView['candidate_owner'] }}</td>
                <td>
                    <a title="Show"  class="fa fa-circle" href="{{ route('interview.show',$interView['id']) }}"></a>
                    <a title="Edit" class="fa fa-edit" href="{{ route('interview.edit',array($interView['id'],'index')) }}"></a>
                    @include('adminlte::partials.deleteInterview', ['data' => $interView, 'name' => 'interview','display_name'=>'Interview'])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script>
        $(document).ready(function(){
            $(".date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = jQuery('#interview_table').DataTable( {
                responsive: true,
                stateSave : true,
                "pageLength": 50,

            } );
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection