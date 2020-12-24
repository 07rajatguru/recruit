@extends('adminlte::page')

@section('title', 'Rolewise Permissions')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="roles_table">
        <thead>
	        <tr>
	            <th>Permission</th>

	            @if(isset($roles) && sizeof($roles) > 0)
		            @foreach($roles as $key => $value)
		                <th><center><b>{{ $value['name'] }}</b></center></th>
		            @endforeach
		        @endif
	        </tr>
    	</thead>
        <tbody>
	        @if(isset($permissions) && sizeof($permissions) > 0)
	            @foreach($permissions as $key => $value)
	                <tr>
	                    <td>{{ $value['display_name'] }}</td>
	                    @if(isset($roles) && sizeof($roles) > 0)
	                    	@foreach($roles as $key => $value)
				                <td align="center"><input type="checkbox"></td>
				            @endforeach
	                    @endif
	                </tr>
	            @endforeach
	        @endif
        </tbody>
    </table>
@stop