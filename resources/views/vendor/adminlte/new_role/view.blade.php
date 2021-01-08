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
	                <tr id="{{ $value['id'] }}" class="permission_id">
	                    <td>{{ $value['display_name'] }}</td>

	                    @if(isset($roles) && sizeof($roles) > 0)
	                    	@foreach($roles as $k => $v)
				                <td align="center"><input type="checkbox" class="rolecb" id="{{ $v['id'] }}"></td>
				            @endforeach
	                    @endif
	                </tr>
	            @endforeach
	        @endif
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $(document).on('click', '.rolecb', function (e) {

            	var url = 'rolewise-permissions/add';
                var token = $('input[name="csrf_token"]').val();
                var check = $(this).is(":checked");
                var role_id = $(this).attr('id');

                var row = $(this).closest('tr');
    			var permission_id = row.attr('id');

                $.ajax({
                    url : url,
                    type : "POST",
                    data : {role_id:role_id,permission_id:permission_id,'_token':token},
                    dataType:'json',
                    success: function(res) {

                    }
                });
            });
        });
    </script>
@endsection