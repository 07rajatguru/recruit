@extends('adminlte::page')

@section('title', 'Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')

	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	           <h2>{{ $name }} Holidays List ({{ $count or 0 }})</h2>  
	        </div>
	        <div class="pull-right">
            </div>
	    </div>
	</div>

	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="holidays_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>From date</th>
                <th>To date</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
            @foreach($holiday_details as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{$value['title']}}</td>
                <td>{{$value['from_date']}}</td>
                <td>{{$value['to_date']}}</td>
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
                "order" : [2, 'desc'],
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection