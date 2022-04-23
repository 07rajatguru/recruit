@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Associated Candidates')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb" style="margin-top:15px;">
            <div class="pull-left">
                <h3>Candidates associated to Job Opening : {{ $posting_title }}</h3>
                <span></span>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Mobile No.</th>
                <th>Candidate Email</th>
                <th>Resume</th>
            </tr>
        </thead>

        <tbody>
            <?php $i = 0; ?>

            @foreach ($candidates as $candidate)
                <tr>

                    <td>{{ ++$i }}</td>
                    <td><a target="_blank" title="Show Candidate" href="{{ route('candidate.show',$candidate->cid) }}">{{ $candidate->fname or '' }}</a>
                    </td>
                    <td>{{ $candidate->owner or '' }}</td>
                    <td>{{ $candidate->mobile or '' }}</td>
                    <td>{{ $candidate->email or '' }}</td>
                    <td>
                        <?php
                            $candidate_resume = App\CandidateUploadedResume::getCandidateFormattedResume($candidate->cid);
                        ?>

                        @if($candidate_resume != '')
                            <a href="/{{ $candidate_resume }}" target="_blank">Resume</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <input type="hidden" name="token" id="token" value="{{ csrf_token() }}">

@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#candidate_table').DataTable({
                responsive: true,
                stateSave : true,
                "columnDefs": [{orderable: false, targets: [5]}],
            } );

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection