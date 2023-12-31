@section('customs_css')
    <style>
        div.scrollmenu {
            background-color: white;
            overflow: auto;
            white-space: nowrap;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Rolewise Permissions')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="scrollmenu">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="roles_table" style="border-color: black;">
            <thead>
    	        <tr>
    	            <th style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;background-color: #DCDCDC;border-color: black;">Permission</th>

    	            @if(isset($roles) && sizeof($roles) > 0)
    		            @foreach($roles as $key => $value)
    		                <th style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;background-color: #DCDCDC;border-color: black;"><center><b>{{ $value['display_name'] }}</b></center></th>
    		            @endforeach
    		        @endif
    	        </tr>
        	</thead>
            <tbody>
    	        @if(isset($permissions) && sizeof($permissions) > 0)
    	            @foreach($permissions as $key => $value)
    	                <tr id="{{ $value['id'] }}" class="permission_id" style="border-color: black;">
    	                   <td style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">{{ $value['display_name'] }}</td>

        	                @if(isset($roleswise) && sizeof($roleswise) > 0)
        	                    @foreach($roleswise as $k => $v)
                                    <?php 
                                        $v_array = explode(",", $v);
                                    ?>
                                    @if(in_array($value['id'], $v_array))
        				                <td align="center"><input type="checkbox" class="rolecb" id="{{ $k }}" checked=""></td>
                                    @else
                                        <td align="center"><input type="checkbox" class="rolecb" id="{{ $k }}"></td>
                                    @endif
        				        @endforeach
        	                @endif
    	                </tr>
    	            @endforeach
    	        @endif
            </tbody>
        </table>
    </div>
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
                    data : {role_id:role_id,permission_id:permission_id,check:check,'_token':token},
                    dataType:'json',
                    success: function(res) {

                    }
                });
            });
        });
    </script>
@endsection