@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate List</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
                <th>Mobile Number</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $candidate->fname or '' }} {{ $candidate->lname or '' }}</td>
                <td>{{ $candidate->owner or '' }}</td>
                <td>{{ $candidate->email or ''}}</td>
                <td>{{ $candidate->mobile or ''}}</td>
                <td>
                    <a class="fa fa-circle" href="{{ route('candidate.show',$candidate->id) }}" title="Show"></a>
                    <a class="fa fa-edit" href="{{ route('candidate.edit',$candidate->id) }}" title="Edit"></a>
                    @include('adminlte::partials.deleteModal', ['data' => $candidate, 'name' => 'candidate','display_name'=>'Candidate'])
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#candidate_table').DataTable( {
                responsive: true,
                "autoWidth": false
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection