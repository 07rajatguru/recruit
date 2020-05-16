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
                <h2>Edit Permission</h2>
            @else
                <h2>Create New Permission</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('permission.index') }}"> Back</a>
        </div>
    </div>
</div>

    @if($action == 'edit')
        {!! Form::model($permission,['method' => 'PUT', 'files' => true, 'route' => ['permission.update', $permission['id']],'class'=>'form-horizontal','id'=>'permissionForm']) !!}
        {!! Form::hidden('permission_id', $permission['id'], array('id'=>'permission_id')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'permission.store','class'=>'form-horizontal','id'=>'permissionForm']) !!}
    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('display_name') ? 'has-error' : '' }}">
                            <strong>Display Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('display_name', null, array('placeholder' => 'Display Name','class' => 'form-control', 'tabindex' => '2')) !!}
                            @if ($errors->has('display_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('display_name') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <strong>Description: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('description', null, array('placeholder' => 'Description','class' => 'form-control','style'=>'height:100px', 'tabindex' => '3')) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    {!! Form::submit(isset($permission) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}


@section('customscripts')
    <script>
        $(document).ready(function() {

            $("#permissionForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "display_name": {
                        required: true
                    },
                    "description": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Name is required."
                    },
                    "display_name": {
                        required: "Display Name is required."
                    },
                    "description": {
                        required: "Description is required."
                    }
                }
            });

        });
    </script>
@endsection