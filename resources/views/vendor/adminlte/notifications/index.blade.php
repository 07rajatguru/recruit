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
        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($notifications as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['module'] or '' }}</td>
                <td>{{ $value['msg'] or '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script>
        $(document).ready(function(){
            var table = jQuery('#noti_table').DataTable( {
                responsive: true
            } );
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection