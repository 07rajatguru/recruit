@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    @section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
	@endsection

	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	           <h2>Create New Role</h2>
	        </div>
	        <div class="pull-right">
	            <a class="btn btn-primary" href="{{ route('userrole.index') }}">Back</a>
	        </div>
	    </div>
	</div>

    {!! Form::open(array('route' => 'userrole.store','method'=>'POST','id' => 'role_form')) !!}

	<div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
	            <div class="box-header col-md-6 ">

	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control','tabindex' => '1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('display_name') ? 'has-error' : '' }}">
                            <strong>Display Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('display_name', null, array('placeholder' => 'Display Name','class' => 'form-control','tabindex' => '2')) !!}
                            @if ($errors->has('display_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('display_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Is this Role in Management Team? :</strong>
                            {!! Form::checkbox('role_in_mngmnt_team','1', $role_in_mngmnt_team, array('id' => 'role_in_mngmnt_team','tabindex' => '3')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Is this Role Visible to All Users? :</strong>
                            {!! Form::checkbox('role_visible_to_all','1', $role_visible_to_all, array('id' => 'role_visible_to_all','tabindex' => '4')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Department : <span class = "required_fields">*</span> </strong>
                            {!! Form::select('department', $departments,$department_id, array('id'=>'department','class' => 'form-control', 'tabindex' => '5')) !!}
                        </div>

                        <div class="form-group {{ $errors->has('position') ? 'has-error' : '' }}">
                            <strong>Position: <span class = "required_fields">*</span></strong>
                            {!! Form::text('position', null, array('placeholder' => 'Position','class' => 'form-control','tabindex' => '6')) !!}
                            @if ($errors->has('position'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('position') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <strong>Description: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('description', null, array('placeholder' => 'Description','class' => 'form-control','style'=>'height:100px','tabindex' => '7')) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('module_ids') ? 'has-error' : '' }}">
                            <strong>Select Module Permissions : <span class = "required_fields">*</span></strong>
                            <input type="checkbox" id="all_roles"/><strong>Select All</strong>
                            <br/><br/>
                            @foreach($modules as $k=>$v) &nbsp;&nbsp;
                                {!! Form::checkbox('module_ids[]', $k, in_array($k,$module_ids_array), array('id'=>'module_ids','class' => 'module_ids','onclick' => 'getModulePermissions("'.$k.'","'.$v.'")')) !!}
                                {!! Form::label ($v) !!}
                            @endforeach
                            <br/><br/>
                            @if ($errors->has('module_ids'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('module_ids') }}</strong>
                                </span>
                            @endif
                        </div>

                        <?php 
                            $str = '';
                            $id = '';
                        ?>
                        @foreach($modules as $k=>$v)
                            <div class="div_{{ $v }}" style="border:1px dotted black;display:none;">
                                <span><b>{{ $v }} : </b></span><br/><br/>
                            </div>
                            <?php
                                $id = $id . "," . $k;
                                $str = $str . "," . $v;
                            ?>
                        @endforeach
                        <input type="hidden" name="name_string" id="name_string" value="{{ $str }}">
                        <input type="hidden" name="id_string" id="id_string" value="{{ $id }}">
                    </div>
                </div>
            </div>
        </div>
   
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

@section('customscripts')
    <script>
        $(document).ready(function() {

            $("#department").select2();

            $("#role_form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "display_name": {
                        required: true
                    },
                    "position": {
                        required: true
                    },
                    "description": {
                        required: true
                    },
                   "module_ids[]": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Name is Required."
                    },
                    "display_name": {
                        required: "Display Name is Required."
                    },
                    "position": {
                        required: "Please Add Position of Role."
                    },
                    "description": {
                        required: "Description is Required."
                    },
                    "module_ids[]": {
                        required: "Please Select module for Permissions"
                    }
                }
            });
        });

        $("#all_roles").click(function () {

            var isChecked = $("#all_roles").is(":checked");
            var id_string = $("#id_string").val();
            var id_arr = id_string.split(",");
            var name_string = $("#name_string").val();
            var name_arr = name_string.split(",");

            if(isChecked == true) {
                $('.module_ids').prop('checked', this.checked);
                for (var i = 1; i < id_arr.length; i++) {
                    getModulePermissions(id_arr[i],name_arr[i]);
                }
            }
            else {
                $('.module_ids').prop('checked', false);
                $('.permission_class').prop('checked', false);

                for (var i = 1; i < name_arr.length; i++) {
                    $(".div_"+name_arr[i]).hide();
                }
            }
        });

        $(".module_ids").click(function () {
            $("#all_roles").prop('checked', ($('.module_ids:checked').length == $('.module_ids').length) ? true : false);
        });

        function permissionValidation() {

            var permission = document.getElementsByName('permission[]');
            var permission_selected_items="";

            for(var i=0; i<permission.length; i++) {

                if(permission[i].type == 'checkbox' && permission[i].checked == true) {
                    permission_selected_items += permission[i].value + "\n";
                }
            }

            if(permission_selected_items == '') {
                alert("Please Select Permissions.");
                return false;
            }
            return true;
        }

        function getModulePermissions(module_id,module_name) {

            $.ajax({

                url:'/getPermissions',
                method:'GET',
                data:{'module_id':module_id},
                dataType:'json',
                success: function(data) {

                    // for module_ids
                    var module_items=document.getElementsByName('module_ids[]');
                    var module_selected_items="";

                    for(var i=0; i<module_items.length; i++) {
                        if(module_items[i].type=='checkbox' && module_items[i].checked==true)
                            module_selected_items += module_items[i].value+",";
                    }

                    var search_str = ","+module_id+",";
                    var search_str_2 = module_id+",";

                    var bool_1 = module_selected_items.includes(module_id);
                    var bool_2 = module_selected_items.search(search_str) > -1;
                    var bool_3 = module_selected_items.search(search_str_2) > -1;

                    if(bool_1 == true || bool_2 == true || bool_3 == true) {

                        if(data.length > 0) {

                            $(".div_"+module_name).html('');

                            var html = '';
                            html += '<div>';
                            html += '<span><b>'+module_name+' : </b></span><br/><br/>';
                            html += '<table class="table table-striped table-bordered nowrap" style="margin-left: 22px;width: 1160px;"><tr>';

                            for (var i = 0; i < data.length; i++) {

                                if(i % 3 == 0) {
                                    html += '</tr><tr>';
                                }
                                html += '<td style="border:1px solid black;"><input type="checkbox" name="permission[]" value="'+data[i].id+'" class="permission_class">';
                                html += '&nbsp;&nbsp;';
                                html += '<span style="font-size:15px;">'+data[i].display_name+'</span>';
                                html += '&nbsp;&nbsp;</td>';   
                            }

                            html += '</tr></table>';
                            html += '</div>';

                            $(".div_"+module_name).append(html);
                            $(".div_"+module_name).show();

                            var isChecked = $("#all_roles").is(":checked");
                            if(isChecked == true) {
                                $('.permission_class').prop('checked', true);
                            }
                        }
                    }
                    else {

                        $(".div_"+module_name).html('');
                       	$(".div_"+module_name).hide();
                    }
                }
            });
        }
    </script>
@endsection
@stop