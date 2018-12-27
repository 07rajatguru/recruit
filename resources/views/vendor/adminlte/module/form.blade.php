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
                <h2>Edit Module</h2>
            @else
                <h2>Create New Module</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('module.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($module, ['method' => 'PATCH','route' => ['module.update', $module->id], 'id' => 'module_form']) !!}
@else
    {!! Form::open(array('route' => 'module.store','method'=>'POST', 'id' => 'module_form')) !!}
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

                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <strong>Description: <span class = "required_fields">*</span></strong>
                        {!! Form::textarea('description', null, array('placeholder' => 'Description','class' => 'form-control','tabindex' => '2', 'rows' => '5')) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        <strong> Status : </strong> &nbsp;&nbsp;
                        {!! Form::radio('status','0') !!}
                        {!! Form::label('0') !!} &nbsp;&nbsp;
                        {!! Form::radio('status','1', true) !!}
                        {!! Form::label('1') !!}
                        @if ($errors->has('status'))
                            <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong>
                        </span>
                        @endif
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
            
            $("#module_form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "description": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Name is required field."
                    },
                    "description": {
                        required: "Description is required field."
                    }
                }
            });
        });
    </script>
@endsection