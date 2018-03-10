@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Interview</h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="interview_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Interview Name</th>
                <th>Candidate</th>
                <th>Client</th>
                <th>InterView Date</th>
                <th>Location</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($interViews as $interView)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $interView->interview_name or '' }}</td>
                <td>{{ $interView->candidate_name or '' }}</td>
                <td>{{ $interView->client_name or ''}}</td>
                <td>{{ $interView->interview_date or ''}} </td>
                <td>{{ $interView->location or ''}}</td>

                <td>
                    <a title="Show"  class="fa fa-circle" href="{{ route('interview.show',$interView['id']) }}"></a>
                    <a title="Edit" class="fa fa-edit" href="{{ route('interview.edit',$interView['id']) }}"></a>
                    @include('adminlte::partials.deleteModal', ['data' => $interView, 'name' => 'interview','display_name'=>'Interview'])
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
                responsive: true
            } );
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection