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
	            <a class="btn btn-primary" href="{{ route('userrole.index') }}"> Back</a>
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

                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <strong>Description: <span class = "required_fields">*</span></strong>
                        {!! Form::textarea('description', null, array('placeholder' => 'Description','class' => 'form-control','style'=>'height:100px','tabindex' => '3')) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('module_ids') ? 'has-error' : '' }}">
                        <strong>Select Module Permissions : <span class = "required_fields">*</span></strong>
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

                        @foreach($modules as $k=>$v)
                            <div class="div_{{ $v }}" style="border:1px dotted black;display:none;"></div>
                        @endforeach
                    </div>
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
        $(document).ready(function(){

            $("#role_form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "display_name": {
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
                    "description": {
                        required: "Description is Required."
                    },
                    "module_ids[]": {
                        required: "Please Select module for Permissions"
                    }
                }
            });
        });

        function getModulePermissions(module_id,module_name)
        {
            $.ajax(
            {
                url:'/getPermissions',
                method:'GET',
                data:{'module_id':module_id},
                dataType:'json',
                success: function(data)
                {
                    // for module_ids
                    var module_items=document.getElementsByName('module_ids[]');
                    var module_selected_items="";

                    for(var i=0; i<module_items.length; i++) {
                        if(module_items[i].type=='checkbox' && module_items[i].checked==true)
                            module_selected_items += module_items[i].value+",";
                    }

                    var check_bool = module_selected_items.includes(module_id);

                    if(check_bool == true) {
                        if(data.length > 0)
                        {
                            $(".div_"+module_name).html('');

                            var html = '';
                            html += '<div>';
                            html += '<span><b>'+module_name+' : </b></span><br/><br/>';

                            for (var i = 0; i < data.length; i++) 
                            {
                                html += '&nbsp;&nbsp;&nbsp;<input type="checkbox"  name="permission[]" value="'+data[i].id+'">';
                                html += '&nbsp;&nbsp;';
                                html += '<span style="font-size:15px;">'+data[i].display_name+'</span>';
                                html += '&nbsp;&nbsp;';
                            }

                            html += '</div>';

                            $(".div_"+module_name).append(html);
                            $(".div_"+module_name).show();   
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