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
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($role, ['method' => 'PATCH','id' => 'role_form','route' => ['roles.update', $role->id]]) !!}
@else
    {!! Form::open(array('route' => 'roles.store','method'=>'POST','id' => 'role_form')) !!}
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

                    <div class="form-group {{ $errors->has('permission') ? 'has-error' : '' }}" >
                        <strong>Permission <span class = "required_fields">*</span></strong>
                        <input type="checkbox" id="all_roles"/> <strong>Select All</strong>
                        <br/>
                        @foreach($permission as $value)
                            @if($action == 'add')
                                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                    {{ $value->display_name }}</label>
                            @else
                                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}

                                    {{ $value->display_name }}</label>
                            @endif
                            <br/>
                        @endforeach
                        @if ($errors->has('permission'))
                            <span class="help-block">
                                <strong>{{ $errors->first('permission') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($role) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
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
                    "permission[]": {
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
                    "permission[]": {
                        required: "Please Select Permissions"
                    }
                }
            });

            $("#all_roles").click(function () {
                $('.name').prop('checked', this.checked);
            });

            $(".name").click(function () {
                $("#all_roles").prop('checked', ($('.name:checked').length == $('.name').length) ? true : false);
            });

            if ($('.name:checked').length == $('.name').length){
              $('#all_roles').prop('checked',true);
            }
            else {
              $('#all_roles').prop('checked',false);
            }
        });
    </script>
@endsection

