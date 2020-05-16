@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
</style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Client Remarks</h2>
            @else
                <h2>Add Client Remarks</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($client_remarks,['method' => 'PATCH', 'files' => true, 'route' => ['clientremarks.update', $client_remarks['id']],'id'=>'client_remarks_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'clientremarks.store', 'id'=>'client_remarks_form', 'autocomplete' => 'off']) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                    <strong>Remarks: <span class = "required_fields">*</span> </strong>
                    {!! Form::text('remarks', null, array('id'=>'remarks','class' => 'form-control','placeholder' => 'Name','tabindex' => '1' )) !!}
                    @if ($errors->has('remarks'))
                        <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($client_remarks) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

@section('customscripts')
    <script>
        $(document).ready(function()
        {
            $("#client_remarks_form").validate(
            {
                rules: {
                    "remarks": {
                        required: true
                    }
                },
                messages: {
                    "remarks": {
                        required: "Remarks is Required Field."
                    }
                }
            });
        });
    </script>
@endsection