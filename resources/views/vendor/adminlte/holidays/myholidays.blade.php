@extends('adminlte::page')

@section('title', 'Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')

	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	           <h2>Holiday List ({{ $count or 0 }})</h2>  
	        </div>
	        <div class="pull-right">
            </div>
	    </div>
	</div>

	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="holidays_table">
        <thead>
            <tr>
                <th width="5px">No</th>
                <th width="10px">Type</th>
                <th width="10px">Title</th>
                <th width="5px">Date</th>
                @if($id == 1)
                    <th width="700px">Users</th>
                @endif
            </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
            @foreach($holiday_details as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{$value['type']}}</td>
                <td>{{$value['title']}}</td>
                <td>{{$value['from_date']}}</td>

                @if($id == 1)
                    <td style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">{{$value['users']}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#holidays_table').DataTable({
                responsive: true,
                stateSave : true,
                "order" : [3, 'desc'],
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection