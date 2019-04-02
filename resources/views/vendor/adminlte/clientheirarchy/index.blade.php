@extends('adminlte::page')

@section('title', 'Client Heirarchy')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Client Heirarchy</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('clientheirarchy.create') }}"> Add New Client Heirarchy</a>
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

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_heirarchy_table">
    <thead>
        <tr>
	       <th width="50px">No</th>
	       <th width="100px">Action</th>
	       <th>Name</th>
           <th>Order</th>
	    </tr>
    </thead>
    <tbody id="sortable_data">
        <?php $i=0; ?>
        @foreach ($client_heirarchy as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    {{--<a class="fa fa-circle" href="{{ route('clientheirarchy.show',$value['id']) }}"></a>--}}
                    <a class="fa fa-edit" href="{{ route('clientheirarchy.edit',$value['id']) }}"></a>
                    @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'clientheirarchy','display_name'=>'Support'])
                </td>
                <td>{{ $value['name'] }}</td>
                <td>{{ $value['order'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('customscripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            /*var table = jQuery('#client_heirarchy_table').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );*/
            jQuery("#sortable_data").sortable({
                update: function ()
                {
                    var order = $("#sortable_data").sortable('toArray');
                    //alert(order);
                    var dataString = 'ids=' + order;
                    $.ajax
                    ({
                        type: "GET",
                        url: '/client-heirarchy/update-position',
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