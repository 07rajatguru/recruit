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

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Opened to all List ({{ $count or 0 }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}
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
            <tbody>
                @foreach($jobList as $key=>$value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a title="Show" class="fa fa-circle" href="{{ route('jobopen.show',$value['id']) }}"></a>

                            @if(isset($value['access']) && $value['access'] == 1)
                                <a title="Edit" class="fa fa-edit" href="{{ route('jobopen.edit',$value['id']) }}"></a>
                            @endif

                            @permission(('change-job-priority'))
                                @include('adminlte::partials.jobstatus', ['data' => $value, 'name' => 'jobopen','display_name'=>'More Information'])
                            @endpermission

                            @permission(('job-delete'))
                                @include('adminlte::partials.jobdelete', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job'])
                            @endpermission

                            @permission(('clone-job'))
                                <a title="Clone Job" class="fa fa-clone" href="{{ route('jobopen.clone', $value['id']) }}"></a>
                            @endpermission
                        </td>

                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['am_name'] or '' }}</td>
                        <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}
                        </td>
                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>
                        <td>
                            <a title="Show Associated Candidates" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a>
                        </td>

                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['city'] or ''}}</td>
                        <td>{{ $value['min_ctc'] or ''}}</td>
                        <td>{{ $value['max_ctc'] or ''}}</td>
                        <td>{{ $value['created_date'] or ''}}</td>
                        <td>{{ $value['no_of_positions'] or ''}}</td>

                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] or ''}}</td>
                        <td>{{ $value['qual'] or ''}}</td>
                        <td>{{ $value['industry'] or ''}}</td>
                        <td>{!! $value['desired_candidate'] or ''!!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var table = jQuery('#jo_table').DataTable({

                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0, "order": 'desc'},
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "150px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                    { "width": "5px", "targets": 10 },
                ],
                "pageLength": 100,
                stateSave: true
            });

            if (!table.data().any()) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection