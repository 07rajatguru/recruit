@extends('adminlte::page')

@section('title', 'Candidate Join')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate Joining This Month ({{ $count }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}">Create New Candidate</a>
            </div>
        </div>
    </div>

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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Candidate Name</th>
                <th>Client Name</th>
                <th>Position Name</th>
                <th>Fixed Salary</th>
                <th>Joining <br/>Date</th>
                <th>Efforts</th>
                <th>Candidate Email</th>
                <th>Mobile <br/>Number</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ ++$i }}</td>

                <td>{{ $candidate['candidate_name'] or '' }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate['company_name'] or '' }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;"><a target="_blank" title="Show Job Opening" href="{{ route('jobopen.show',$candidate['jid']) }}">{{ $candidate['position_name'] or '' }}</a></td>

                <td>{{ $candidate['salary'] }}</td>

                <td>{{ date('d-m-Y', strtotime($candidate['date'])) }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate['efforts'] or '' }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate['candidate_email'] or ''}}</td>

                <td>{{ $candidate['candidate_mobile'] or ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#candidate_table').DataTable({

                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0 },
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                ],

                "autoWidth": false,
                "pageLength": 100,
                "order" : [5,'asc'],
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection