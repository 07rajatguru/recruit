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
            @if( $action == 'edit')
                <h2>Edit Role</h2>
            @else
                <h2>Create New Role</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('userrole.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($role, ['method' => 'PATCH','id' => 'role_form','route' => ['userrole.update', $role->id]]) !!}
@else
    {!! Form::open(array('route' => 'userrole.store','method'=>'POST','id' => 'role_form')) !!}
@endif

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
                            {!! Form::checkbox('module_ids[]', $k, in_array($k,$module_ids_array), array('id'=>'module_ids','class' => 'module_ids','onclick' => 'getModulePermissions()')) !!}
                            {!! Form::label ($v) !!}
                        @endforeach
                        @if ($errors->has('module_ids'))
                            <span class="help-block">
                                <strong>{{ $errors->first('module_ids') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group append_permissions" style="border:1px solid black;display:none;"></div>

                    @if($action == 'edit')
                        @if(isset($get_permissions) && sizeof($get_permissions)>0)
                            <div class="form-group edit_append_permissions" style="border:1px solid black;">
                                @foreach($get_permissions as $key => $value)
                                    @if(in_array($value['id'],$permissions_ids_array))
                                        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="permission[]" value="{{ $value['id'] }}" checked="">&nbsp;&nbsp;
                                        <span style="font-size:15px;">{{ $value['display_name'] }}</span>&nbsp;&nbsp;<br/>
                                    @else
                                        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="permission[]" value="{{ $value['id'] }}">&nbsp;&nbsp;
                                        <span style="font-size:15px;">{{ $value['display_name'] }}</span>&nbsp;&nbsp;<br/>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="action" id="action" value="{{ $action }}">
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>

@section('customscripts')
    <script>
        $(document).ready(function(){

            $( "#user_ids" ).select2();

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

        function getModulePermissions()
        {
            // for module_ids
            var module_items=document.getElementsByName('module_ids[]');
            var module_selected_items="";
            var arr = [];

            for(var i=0; i<module_items.length; i++) {
                if(module_items[i].type=='checkbox' && module_items[i].checked==true)
                    arr.push(module_items[i].value);
            }

            $.ajax(
            {
                url:'/getPermissions',
                method:'GET',
                data:'arr='+arr,
                dataType:'json',
                success: function(data)
                {
                    if(data.length > 0)
                    {
                        $(".append_permissions").html('');
                        var html = '';
                        html += '<div>';

                        for (var i = 0; i < data.length; i++) 
                        {
                            html += '&nbsp;&nbsp;&nbsp;<input type="checkbox"  name="permission[]" value="'+data[i].id+'">';
                            html += '&nbsp;&nbsp;';
                            html += '<span style="font-size:15px;">'+data[i].display_name+'</span>';
                            html += '&nbsp;&nbsp;<br/>';
                        }
                        $(".append_permissions").append(html);
                        $(".append_permissions").show();
                        html += '</div>';
                    }
                    else
                    {
                        $(".append_permissions").html('');
                        $(".append_permissions").hide();
                    }
                }
            });
        }
    </script>
@endsection