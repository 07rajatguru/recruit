@extends('adminlte::page')

@section('title', 'Create Receipt Talent')

@section('content_header')
    <h1></h1>

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create Receipt Talent</h2>
        </div>

        <div class="pull-right">
        	<a class="btn btn-primary" href="{{ route('receipt.talent') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('bank_type') ? 'has-error' : '' }}">
                            <strong>Select Bank type:</strong>
                            {!! Form::select('bank_type', $bank_type,null, array('id'=>'bank_type','class' => 'form-control','onchange' => 'select_bank()')) !!}
                            @if ($errors->has('bank_type'))
                                <span class="help-block">
                            <strong>{{ $errors->first('bank_type') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{--Create HDFC Bank Receipt--}}
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12 hdfc" style="display: none;">
            	<div class="box-header with-border col-md-6">
                    <h3 class="box-title">Create HDFC Receipt</h3>
                </div>
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : '' }}">
	                            <strong>Reference No:</strong>
	                            {!! Form::text('ref_no', null, array('id'=>'ref_no','class' => 'form-control')) !!}
	                            @if ($errors->has('ref_no'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('ref_no') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            {!! Form::text('value_date', null, array('id'=>'value_date','class' => 'form-control')) !!}
	                            @if ($errors->has('value_date'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('value_date') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
	                            <strong>Date:</strong>
	                            {!! Form::text('date', null, array('id'=>'date','class' => 'form-control')) !!}
	                            @if ($errors->has('date'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('date') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('desc') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('desc', null, array('id'=>'desc','class' => 'form-control','rows' => '5')) !!}
	                            @if ($errors->has('desc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('desc') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {!! Form::text('company_name', null, array('id'=>'company_name','class' => 'form-control')) !!}
	                            @if ($errors->has('company_name'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control')) !!}
	                            @if ($errors->has('amount'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5')) !!}
	                            @if ($errors->has('remarks'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
        	</div>

        	{{--Create ICICI Bank Receipt--}}
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12 icici" style="display: none;">
            	<div class="box-header with-border col-md-6">
                    <h3 class="box-title">Create ICICI Receipt</h3>
                </div>
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('tran_id') ? 'has-error' : '' }}">
	                            <strong>Transaction ID:</strong>
	                            {!! Form::text('tran_id', null, array('id'=>'tran_id','class' => 'form-control')) !!}
	                            @if ($errors->has('tran_id'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('tran_id') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            {!! Form::text('value_date', null, array('id'=>'value_date','class' => 'form-control')) !!}
	                            @if ($errors->has('value_date'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('value_date') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('txn_posted_date') ? 'has-error' : '' }}">
	                            <strong>Txn Posted Date:</strong>
	                            {!! Form::text('txn_posted_date', null, array('id'=>'txn_posted_date','class' => 'form-control')) !!}
	                            @if ($errors->has('txn_posted_date'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('txn_posted_date') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('desc') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('desc', null, array('id'=>'desc','class' => 'form-control','rows' => '5')) !!}
	                            @if ($errors->has('desc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('desc') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {!! Form::text('company_name', null, array('id'=>'company_name','class' => 'form-control')) !!}
	                            @if ($errors->has('company_name'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control')) !!}
	                            @if ($errors->has('amount'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('cr_dr') ? 'has-error' : '' }}">
	                            <strong>CR/DR:</strong>
	                            {!! Form::text('cr_dr', null, array('id'=>'cr_dr','class' => 'form-control')) !!}
	                            @if ($errors->has('cr_dr'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('cr_dr') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5')) !!}
	                            @if ($errors->has('remarks'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
        	</div>

        	{{--Create Other Bank Receipt--}}
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12 other" style="display: none;">
            	<div class="box-header with-border col-md-6">
                    <h3 class="box-title">Create Other Receipt</h3>
                </div>
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('voucher_no') ? 'has-error' : '' }}">
	                            <strong>Voucher No:</strong>
	                            {!! Form::text('voucher_no', null, array('id'=>'voucher_no','class' => 'form-control')) !!}
	                            @if ($errors->has('voucher_no'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('voucher_no') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            {!! Form::text('value_date', null, array('id'=>'value_date','class' => 'form-control')) !!}
	                            @if ($errors->has('value_date'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('value_date') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5')) !!}
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
	                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {!! Form::text('company_name', null, array('id'=>'company_name','class' => 'form-control')) !!}
	                            @if ($errors->has('company_name'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control')) !!}
	                            @if ($errors->has('amount'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('mode_of_receipt') ? 'has-error' : '' }}">
	                            <strong>Mode of Receipt:</strong>
	                            {!! Form::text('mode_of_receipt', null, array('id'=>'mode_of_receipt','class' => 'form-control','rows' => '5')) !!}
	                            @if ($errors->has('mode_of_receipt'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('mode_of_receipt') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
        	</div>
        </div>
    </div>
</div>

@stop

@section('customscripts')
    <script type="text/javascript">
        $("#recepit_talent_create").validate({
            rules: {
                "bank_type": {
                    required: true
                },
            },
            messages: {
                "bank_type": {
                    required: "Bank Type is required."
                },
            }
        });

        function select_bank() {
        	
        	var bank = $("#bank_type").val();
        	if (bank == 'hdfc') {
        		$(".hdfc").show();
        		$(".icici").hide();
        		$(".other").hide();
        	}
        	else if (bank == 'icici') {
        		$(".hdfc").hide();
        		$(".icici").show();
        		$(".other").hide();
        	}
        	else if (bank == 'other') {
        		$(".hdfc").hide();
        		$(".icici").hide();
        		$(".other").show();
        	}
        	else{
        		$(".hdfc").hide();
        		$(".icici").hide();
        		$(".other").hide();
        	}
        }
    </script>
@endsection
