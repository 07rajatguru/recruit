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

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2" style="width: 13%;">

                @if(isset($open) && $open != '')
                    <a href="{{ route('ticketdiscussion.status','Open') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#e87992;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Open Tickets">Open ({{ $open or '0' }})</div></a>
                @else
                    <a href="{{ route('ticketdiscussion.status','Open') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#e87992;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Open Tickets">Open (0)</div></a>
                @endif
            </div>

            <div class="col-md-2" style="width: 13%;">

                @if(isset($in_progress) && $in_progress != '')
                    <a href="{{ route('ticketdiscussion.status','In Progress') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="In Progress Tickets">In Progress ({{ $in_progress or '0' }})</div></a>
                @else
                    <a href="{{ route('ticketdiscussion.status','In Progress') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="In Progress Tickets">In Progress (0)</div></a>
                @endif
            </div>

            <div class="col-md-2" style="width: 13%;">

                @if(isset($closed) && $closed != '')
                    <a href="{{ route('ticketdiscussion.status','Closed') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Closed Tickets">Closed ({{ $closed or '0' }})
                    </div></a>
                @else
                    <a href="{{ route('ticketdiscussion.status','Closed') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Closed Tickets">Closed (0)</div></a>
                @endif
            </div>
        </div>
    </div><br/>

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
           <th>Ticket No.</th>
           <th>Module</th>
           <th>Status</th>
	       <th>Query Type</th>
           <th>Added By</th>
           <th>Added Date</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($tickets_res as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-circle" href="{{ route('ticket.show',\Crypt::encrypt($value['id'])) }}" title="Show"></a>
                    <a class="fa fa-edit" href="{{ route('ticket.edit',\Crypt::encrypt($value['id'])) }}" title="Edit"></a>

                    @include('adminlte::partials.ticketstatus', ['data' => $value, 'name' => 'ticket'])

                    <a class="fa fa-plus" href="{{ route('ticket.remarks',\Crypt::encrypt($value['id'])) }}" title="Add Comment" target="_blank"></a>
                </td>

                <td>{{ $value['ticket_no'] }}</td>
                <td>{{ $value['module_name'] }}</td>
                <td>{{ $value['status'] }}</td>
                <td>{{ $value['question_type'] }}</td>
                <td>{{ $value['added_by'] }}</td>
                <td>{{ $value['added_date'] }}</td>
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
                "order" : [2,'desc'],
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