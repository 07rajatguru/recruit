@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb" style="margin-top:15px;">
            <div class="pull-left">
                <h3>Associated Candidates to Job Opening : {{ $posting_title }}</h3>
                <span> </span>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('all.jobs') }}"> Back</a>
            </div>
        </div>
    </div>

    <table id="candidate_list_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Action</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
                <th>Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>
            @foreach ($candidateDetails as $candidate)
                <tr>
                    <td><a class="fa fa-circle" href="{{ route('candidate.show',$candidate->id) }}" title="Show"></a></td>
                    <td>{{ $candidate->fname or '' }}</a></td>
                    <td>{{ $candidate->owner or '' }}</td>
                    <td>{{ $candidate->email or '' }}</td>
                    <td>{{ $candidate->mobile or '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#candidate_list_table').DataTable(
            {
                responsive: true,
                "columnDefs": [ 
                    { "width": "10px", "targets": 0, "searchable": false, "orderable": false}
                ],
            });

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection