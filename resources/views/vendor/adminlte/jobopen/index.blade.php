@extends('adminlte::page')

@section('title', 'Job Openings')

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
                <h2>Job Openings List ({{ $count }})</h2>
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
    <div class = "table-responsive">
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
        <thead>
        <tr>
            <th>No</th>
            <th>Action</th>
            <th>MB</th>
            <th>Company Name</th>
            <th>Position Title</th>
            <th>CA</th>
            <th>Location</th>
            <th>Min CTC</th>
            <th>Max CTC</th>
            <th>HR/Coordinator Name</th>
            <th>No. Of Positions</th>
            <th>Edu Qualifications</th>
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
                    <a title="Show"  class="fa fa-circle" href="{{ route('jobopen.show',$value['id']) }}"></a>

                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Edit" class="fa fa-edit" href="{{ route('jobopen.edit',$value['id']) }}"></a>
                    @endif

                    @if(isset($value['access']) && $value['access']==1)
                    @include('adminlte::partials.jobstatus', ['data' => $value, 'name' => 'jobopen','display_name'=>'More Information'])
                    @endif

                    <?php if($isSuperAdmin) {?>
                    @include('adminlte::partials.jobdelete', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job'])
                    <?php   }?>
                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Clone Job"  class="fa fa-clone" href="{{ route('jobopen.clone',$value['id']) }}"></a>
                    @endif
                  

                </td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['am_name'] or '' }}</td>
                <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>
                <td ><a title="Show Associated Candidates" target="_blank" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a></td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['location'] or ''}}</td>
                <td>{{ $value['min_ctc'] or ''}}</td>
                <td>{{ $value['max_ctc'] or ''}}</td>
                <td >{{ $value['coordinator_name'] or '' }}</td>
                <td>{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{{ $value['desired_candidate'] or ''}}</td>
                <td>{{ $value['open_date'] or ''}}</td>
                <td>{{ $value['close_date'] or ''}}</td>


            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = jQuery('#jo_table').DataTable( {
                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0 },
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 }
                ],
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection