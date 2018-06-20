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
    {!! Form::model($accounting,['method' => 'PATCH','files' => true, 'id' => 'expense_form', 'route' => ['accounting.update', $accounting->id]] ) !!}
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
                                <strong>Date:</strong>
                                {!! Form::text('date', null, array('id'=>'date','placeholder' => 'Date','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('date'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('date') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('paid_to') ? 'has-error' : '' }}">
                                <strong>Paid To:</strong>
                                {!! Form::text('paid_to', null, array('id'=>'paid_to','placeholder' => 'Paid To','class' => 'form-control', 'tabindex' => '3' )) !!}
                                @if ($errors->has('paid_to'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('paid_to') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                <strong>Remarks:</strong>
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
                            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                                <strong>Amount:</strong>
                                {!! Form::text('amount', null, array('id'=>'amount','placeholder' => 'Amount','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('head') ? 'has-error' : '' }}">
                                <strong>Expense Head:  </strong>
                                {!! Form::select('head', $head, null, array('id'=>'head','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('head'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('head') }}</strong>
                                </span>
                                @endif
                            </div>

                             <div class="form-group {{ $errors->has('pmode') ? 'has-error' : '' }}">
                                <strong>Payment Mode:</strong>
                                {!! Form::select('pmode', $payment_mode, null, array('id'=>'pmode','class' => 'form-control', 'tabindex' => '6' )) !!}
                                @if ($errors->has('pmode'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('pmode') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ptype') ? 'has-error' : '' }}">
                                <strong>Payment Type:</strong>
                                  {!! Form::select('ptype', $payment_type, null, array('id'=>'ptype','class' => 'form-control', 'tabindex' => '7' )) !!}
                                 @if ($errors->has('ptype'))
                                <span class="help-block">
                                <strong>{{ $errors->first('ptype') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
                                <strong>Reference Number:</strong>
                                {!! Form::text('number', null, array('id'=>'number','placeholder' => 'Reference Number','class' => 'form-control', 'tabindex' => '8' )) !!}
                                @if ($errors->has('number'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('number') }}</strong>
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
            {!! Form::submit('Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
        </div>
    </div>

    </div>


@section('customscripts')
    <script>
        $(document).ready(function(){
            $( "#head" ).select2();

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });


           /* $("#team_form").validate({
                rules: {
                    "team_name": {
                        required: true
                    },
                    "user_ids": {
                        required: true
                    }
                },
                messages: {
                    "team_name": {
                        required: "Team Name is required field."
                    },
                    "user_ids": {
                        required: "Select Users is required field."
                    }
                }
            });*/
        });
    </script>
@endsection

