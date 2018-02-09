@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List</h2>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
        <thead>
        <tr>
            <th>No</th>
            <th>Action</th>
            <th>Managed By</th>
            <th>Company Name</th>
            <th>No. Of Positions</th>
            <th>Position Title</th>
            <th>Location</th>
            <th>Edu Qualifications</th>
            <th>Min CTC</th>
            <th>Max CTC</th>
            <th>Target Industries</th>
            <th>Desired Candidate</th>
            <th>Open Date</th>
            <th>Target Date</th>

        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>

        @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('jobopen.show',$value['id']) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('jobopen.edit',$value['id']) }}">Edit</a>
                    <?php $jobDetails = \App\JobOpen::find($value['id']); ?>
                    @include('adminlte::partials.deleteModal', ['data' => $jobDetails, 'name' => 'jobopen','display_name'=>'Job Openings'])
                </td>
                <td>{{ $value['am_name'] or '' }}</td>
                <td style="background-color: {{ $value['color'] }}">{{ $value['client'] or '' }}</td>
                <td>{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['posting_title'] or ''}}</td>
                <td>{{ $value['location'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['min_ctc'] or ''}}</td>
                <td>{{ $value['max_ctc'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{{ $value['desired_candidate'] or ''}}</td>
                <td>{{ $value['open_date'] or ''}}</td>
                <td>{{ $value['close_date'] or ''}}</td>


            </tr>
        @endforeach
        </tbody>
    </table>

@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = jQuery('#jo_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection