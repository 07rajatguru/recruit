@extends('adminlte::page')

@section('title', 'Candidate Status')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Candidate Status Management</h2>
        </div>

        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('candidateStatus.create') }}"> Create New Candidate Status</a>
        </div>
    </div>
</div>

@if($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="status_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($candidateStatus as $key => $status)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $status->name }}</td>
                <td>
                    <a class="fa fa-edit" href="{{ route('candidateStatus.edit',$status->id) }}"></a>
                    @include('adminlte::partials.deleteModal', ['data' => $status, 'name' => 'candidateStatus','display_name'=>'Candidate Status'])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#status_table').DataTable({
                responsive: true,
                stateSave : true
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection