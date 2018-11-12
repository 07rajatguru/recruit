@extends('adminlte::page')

@section('title', 'Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')

	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	                <h2>Holidays List ({{ $count or 0 }})</h2>  
	        </div>

	        <div class="pull-right">
                <a class="btn btn-success" href="{{ route('holidays.create') }}"> Create New Holiday</a>
            </div>
	    </div>
	</div>
	<br/>

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

	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="holidays_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Action</th>
                <th>Title</th>
                <th>Type</th>
                <th>Users</th>
                <th>From date</th>
                <th>To date</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
            @foreach($holidays as $key => $value)
            <tr>
                <td>{{++$i}}</td>
                <td>
                    <a title="Edit" class="fa fa-edit" href="{{ route('holidays.edit',$value['id']) }}"></a>
                    @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'holidays','display_name'=>'Holiday'])
                </td>
                <td>{{$value['title']}}</td>
                <td>{{$value['type']}}</td>
                <td>{{$value['users']}}</td>
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
            var table = jQuery('#holidays_table').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection