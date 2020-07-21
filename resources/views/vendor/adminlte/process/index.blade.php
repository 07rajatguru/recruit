@extends('adminlte::page')

@section('title', 'Process Manual')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Process Manual Management ({{ $count or '0'}})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('process.create') }}">Add New Process Manual</a>
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

    <table id="process_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Process Name</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody id="process_table_tbody_id">
            <?php $i=0; ?>
            @foreach ($processList as $key => $value)
                <tr id="{{ $value['id'] }}">

                    <td>{{ ++$i }}</td>
                    <td>{{ $value['title'] }}</td>
                    
                    <td>
                        <a class="fa fa-circle" title="show" href="{{ route('process.show',$value['id']) }}"></a>

                        @if(isset($value['access']) && $value['access']==1)
                            <a class="fa fa-edit" title="Edit" href="{{route('process.edit',$value['id']) }}"></a>
                        @else
                            @permission('process-manual-edit')
                                <a class="fa fa-edit" title="Edit" href="{{route('process.edit',$value['id']) }}"></a>
                            @endpermission
                        @endif

                        @permission('process-manual-delete')
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'process','display_name'=>'Process'])
                        @endpermission
                    </td>
               </tr>
            @endforeach
        </tbody>
    </table>
@stop
@section('customscripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            /*var table = jQuery('#process_table').DataTable( {
                responsive: true,
                stateSave : true,
                "pageLength": 100,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );*/

            /*$("#process_table").DataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'asc'],
                "columnDefs": [ {orderable: false, targets: [2]},],
                "ajax" : {
                    'url' : 'process/all',
                    'type' : 'get',
                    error: function(){

                    }
                },
                responsive : true,
                "pageLength": 100,
                "pagingType" : "full_numbers",
                stateSave : true,
            });*/

            jQuery("#process_table_tbody_id").sortable( {

                update: function (event, ui) {

                    var order = $(this).sortable('toArray');
                    var dataString = 'ids=' + order;
                    $.ajax({
                        
                        type: "GET",
                        url: '/process/update-position',
                        data: dataString,
                        cache: false,
                        success: function (data)
                        {
                            if (data == 'success') {
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection