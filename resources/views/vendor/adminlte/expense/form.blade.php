<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Expense</h2>
            @else
                <h2>Create New Expense</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('expense.index') }}"> Back </a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($expense,['method' => 'PATCH','files' => true, 'id' => 'expense_form', 'route' => ['expense.update', $expense->id]] ) !!}
@else
    {!! Form::open(array('route' => 'expense.store','files' => true,'method'=>'POST', 'id' => 'expense_form')) !!}
@endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                                <strong>Date:<span class = "required_fields">*</span></strong>
                                {!! Form::text('date', null, array('id'=>'date','placeholder' => 'Date','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('date'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('date') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('paid_to') ? 'has-error' : '' }}">
                                <strong>Paid To:<span class = "required_fields">*</span></strong>
                                {!! Form::text('paid_to', null, array('id'=>'paid_to','placeholder' => 'Paid To','class' => 'form-control', 'tabindex' => '3' )) !!}
                                @if ($errors->has('paid_to'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('paid_to') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                <strong>Remarks:<span class = "required_fields">*</span></strong>
                                {!! Form::textarea('remarks', null, array('id'=>'remarks','placeholder' => 'Remarks','class' => 'form-control', 'tabindex' => '5'  )) !!}
                                @if ($errors->has('remarks'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('remarks') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            

                            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                            <strong>Select Client: <span class = "required_fields">*</span></strong>
                            {!! Form::select('client_id', $client,null, array('id'=>'client_id','class' => 'form-control')) !!}
                            @if ($errors->has('client_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('client_id') }}</strong>
                                </span>
                            @endif
                            </div>
                            
                            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                                <strong>Amount:<span class = "required_fields">*</span></strong>
                                {!! Form::text('amount', null, array('id'=>'amount','placeholder' => 'Amount','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('head') ? 'has-error' : '' }}">
                                <strong>Expense Head: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('head', $head, $expense_head, array('id'=>'head','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('head'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('head') }}</strong>
                                </span>
                                @endif
                            </div>

                             <div class="form-group {{ $errors->has('pmode') ? 'has-error' : '' }}">
                                <strong>Payment Mode:<span class = "required_fields">*</span></strong>
                                {!! Form::select('pmode', $payment_mode, $pmode, array('id'=>'pmode','class' => 'form-control', 'tabindex' => '6' )) !!}
                                @if ($errors->has('pmode'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('pmode') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ptype') ? 'has-error' : '' }}">
                                <strong>Payment Type:<span class = "required_fields">*</span></strong>
                                  {!! Form::select('ptype', $payment_type, $ptype, array('id'=>'ptype','class' => 'form-control', 'tabindex' => '7' )) !!}
                                 @if ($errors->has('ptype'))
                                <span class="help-block">
                                <strong>{{ $errors->first('ptype') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('reference_number') ? 'has-error' : '' }}">
                                <strong>Reference Number:</strong>
                                {!! Form::text('reference_number', null, array('id'=>'reference_number','placeholder' => 'Reference Number','class' => 'form-control', 'tabindex' => '8' )) !!}
                                @if ($errors->has('reference_number'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('reference_number') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        @if( $action == 'add')
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group {{ $errors->has('document') ? 'has-error' : '' }}">
                        <strong>Document:</strong>
                        {!! Form::file('document', null, array('id'=>'document','class' => 'form-control')) !!}
                        @if ($errors->has('document'))
                            <span class="help-block">
                                <strong>{{ $errors->first('document') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

        @endif
    </div>

    <div class="form-group">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($expense) ? 'Update' : 'Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
        </div>
    </div>

    </div>


@section('customscripts')
    <script>
        $(document).ready(function(){
            $( "#head" ).select2();

            $("#client_id").select2();

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });


            $("#expense_form").validate({
                rules: {
                    "date": {
                        required: true
                    },
                    "amount": {
                        required: true
                    },
                    "paid_to": {
                        required: true
                    },
                    "head": {
                        required: true
                    },
                    "pmode": {
                        required: true
                    },
                    "ptype": {
                        required: true
                    },
                    "remarks": {
                        required: true
                    }
                },
                messages: {
                    "date": {
                        required: "Date is required field."
                    },
                    "amount": {
                        required: "Amount is required field."
                    },
                    "paid_to": {
                        required: "Paid To is required field."
                    },
                    "head": {
                        required: "Expense Head is required field."
                    },
                    "pmode": {
                        required: "Payment Mode is required field."
                    },
                    "ptype": {
                        required: "Payment Type is required field."
                    },
                    "remarks": {
                        required: "Remarks is required field."
                    }
                }
            });
        });
    </script>
@endsection

