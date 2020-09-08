@extends('adminlte::page')

@section('title', 'Training Material')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Training Material ({{$count or '0'}})</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('training.create') }}">Add New Training Material</a>
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

    <table id="training_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Training Name</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody id="training_table_tbody_id">
            <?php $i=0; ?>
            @foreach ($training as $key => $value)
                <tr id="{{ $value['id'] }}">

                    <td>{{ ++$i }}</td>
                  
                    <td>{{ $value['title'] }}</td>
                    
                    <td>
                        <a class="fa fa-circle" title="show" href="{{ route('training.show',$value['id']) }}"></a>
                       
                        @permission(('training-material-edit'))
                            @if(isset($value['access']) && $value['access']==1)
                                <a class="fa fa-edit" title="Edit" href="{{route('training.edit',$value['id']) }}"></a>
                            @endif
                        @endpermission

                        @permission(('training-material-delete'))
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'training','display_name'=>'Training'])                  
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
            
            /*var table = jQuery('#training_table').DataTable( {
                responsive: true,
                stateSave : true,
                "pageLength": 100,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );*/


            /*$("#training_table").DataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'asc'],
                "columnDefs": [ {orderable: false, targets: [2]},],
                "ajax" : {
                    'url' : 'training/all',
                    'type' : 'get',
                    error: function(){

                    }
                },
                responsive : true,
                "pageLength": 100,
                "pagingType" : "full_numbers",
                stateSave : true,
            });*/

            jQuery("#training_table_tbody_id").sortable( {

                update: function (event, ui) {

                    var order = $(this).sortable('toArray');
                    var dataString = 'ids=' + order;
                    $.ajax
                    ({
                        type: "GET",
                        url: '/training/update-position',
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