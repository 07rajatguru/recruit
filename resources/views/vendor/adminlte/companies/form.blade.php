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
                <h2>Edit Company</h2>
            @else
                <h2>Create New Company</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('companies.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($companies,['method' => 'PATCH','id' => 'company_form','route' => ['companies.update', $companies->id]] ) !!}
@else
    {!! Form::open(array('route' => 'companies.store','method'=>'POST','id' => 'company_form')) !!}
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
                        <strong>Description:</strong>
                        {!! Form::textarea('description', null, array('id'=>'description','placeholder' => 'Description','class' => 'form-control', 'tabindex' => '2','rows' => '8')) !!}
                        @if ($errors->has('no_of_positions'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($companies) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

@section('customscripts')
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#description").wysihtml5();

        $("#company_form").validate({
            rules: {
                "name": {
                    required: true
                }, 
            },
            messages: {
                "name": {
                    required: "Name is Required Field."
                }  
            }
        });
    });
</script>
@endsection