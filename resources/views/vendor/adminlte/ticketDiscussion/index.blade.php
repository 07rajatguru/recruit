@extends('adminlte::page')

@section('title', 'Ticket Discussion')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Ticket List ({{ $count or '0' }})</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('ticket.create') }}">Add New Ticket</a>
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

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="ticket_table">
    <thead>
        <tr>
	       <th width="5%">No</th>
           <th width="8%">Action</th>
           <th>Module</th>
           <th>Status</th>
	       <th>Question Type</th>
           <th>Added By</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($tickets_res as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-circle" href="{{ route('ticket.show',$value['id']) }}" title="Show"></a>
                    <a class="fa fa-edit" href="{{ route('ticket.edit',$value['id']) }}" title="Edit"></a>

                    @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'ticket','display_name'=>'Ticket'])

                    @include('adminlte::partials.ticketstatus', ['data' => $value, 'name' => 'ticket'])

                    <a class="fa fa-plus" href="{{ route('ticket.remarks',$value['id']) }}" title="Add Comment" target="_blank"></a>
                </td>
                <td>{{ $value['module_name'] }}</td>
                <td>{{ $value['status'] }}</td>
                <td>{{ $value['question_type'] }}</td>
                <td>{{ $value['added_by'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#ticket_table').DataTable({
                responsive: true,
                stateSave : true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection