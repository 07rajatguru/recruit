@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="noti_table">
        <thead>
        <tr>
            <th>No</th>
            <th>Module</th>
            <th>Message</th>
            <th>Action</th>
        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($notifications as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['module'] or '' }}</td>
                <td>{{ $value['msg'] or '' }}</td>
                <td><a target="_blank" title="Show"  class="fa fa-circle" href="{{ $value['link'] or '' }}"></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script>
        $(document).ready(function(){

            var table = jQuery('#noti_table').DataTable({
                responsive: true,
                stateSave : true,
            });
            
            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection