@extends('adminlte::page')

@section('title', 'Candidate Join')

@section('content_header')

@stop

@section('content')
    <h1>Under Maintenance</h1>
    {{--<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate Joining This Month ({{ $count }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
            </div>
        </div>
    </div>--}}

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

    <!-- <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
                <th>Mobile Number</th>
                <th>Joining Date</th>
                <th>Posting Title</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $candidate->fname or '' }}</td>
                <td>{{ $candidate->owner or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate->email or ''}}</td>
                <td>{{ $candidate->mobile or ''}}</td>
                <td>{{ $candidate->date or '' }}</td>
                <td><a target="_blank" title="Show Job Opening" href="{{ route('jobopen.show',$candidate['jid']) }}">
                        {{ $candidate->jobname or '' }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table> -->
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#candidate_table').DataTable( {
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
                stateSave: true,
                "autoWidth": false,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection