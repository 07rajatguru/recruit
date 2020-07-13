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
                <h2>Edit Accounting</h2>
            @else
                <h2>Create New Accounting</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('accounting.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($accounting,['method' => 'PATCH','files' => true, 'id' => 'accounting_form', 'route' => ['accounting.update', $accounting->id]] ) !!}
@else
    {!! Form::open(array('route' => 'accounting.store','files' => true,'method'=>'POST', 'id' => 'accounting_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <strong>Accounting Name: <span class = "required_fields">*</span></strong>
                        {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Accounting Name','tabindex' => '1','class' => 'form-control','required' )) !!}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <strong>Description:<span class = "required_fields">*</span></strong>
                        {!! Form::textarea('description', null, array('id'=>'description','placeholder' => 'Description','class' => 'form-control', 'tabindex' => '2','required' )) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($accounting) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>

@section('customscripts')
    <script>
        $(document).ready(function(){

            $("#accounting_form").validate({
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
                        required: "Name is Required Field."
                    },
                    "description": {
                        required: "Description is Required Field."
                    }
                }
            });
        });
    </script>
@endsection