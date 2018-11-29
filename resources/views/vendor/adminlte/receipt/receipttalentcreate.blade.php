@extends('adminlte::page')

@section('title', 'Create Receipt Talent')

@section('content_header')
    <h1></h1>

@stop

@section('content')

@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
        .select2 {
			width:100%!important;
		}
    </style>
@endsection

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
    	{!! Form::open(array('route' => 'receipt.talentstore','method' => 'POST', 'id' => 'receipt_form')) !!}
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
	                            <strong>Reference No:<span class = "required_fields">*</span></strong>
	                            {!! Form::text('ref_no', null, array('id'=>'ref_no','class' => 'form-control','tabindex' => '1','placeholder' => 'Reference No.')) !!}
	                            @if ($errors->has('ref_no'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('ref_no') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date_hdfc') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date_hdfc', isset($value_date_hdfc) ? $value_date_hdfc : null, array('id'=>'value_date_hdfc','placeholder' => 'Value Date','class' => 'form-control', 'tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date_hdfc'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date_hdfc') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
	                            <strong>Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('date', isset($date) ? $date : null, array('id'=>'date','placeholder' => 'Date','class' => 'form-control', 'tabindex' => '3')) !!}
	                        	</div>
	                            @if ($errors->has('date'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('date') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('desc_hdfc') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('desc_hdfc', null, array('id'=>'desc_hdfc','class' => 'form-control','rows' => '7','tabindex' => '4','placeholder' => 'Description')) !!}
	                            @if ($errors->has('desc_hdfc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('desc_hdfc') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name_hdfc') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {{-- Client Name Display --}}
	                            {!! Form::select('company_name_hdfc', $clients,null, array('id'=>'company_name_hdfc','class' => 'form-control','tabindex' => '5')) !!}
	                            @if ($errors->has('company_name_hdfc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name_hdfc') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount_hdfc') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount_hdfc', null, array('id'=>'amount_hdfc','class' => 'form-control','tabindex' => '6','placeholder' => 'Amount')) !!}
	                            @if ($errors->has('amount_hdfc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount_hdfc') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks_hdfc') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks_hdfc', null, array('id'=>'remarks_hdfc','class' => 'form-control','rows' => '5','tabindex' => '7','placeholder' => 'Remarks')) !!}
	                            @if ($errors->has('remarks_hdfc'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks_hdfc') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit
                </button>
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
	                            <strong>Transaction ID:<span class = "required_fields">*</span></strong>
	                            {!! Form::text('tran_id', null, array('id'=>'tran_id','class' => 'form-control','tabindex' => '1','placeholder' => 'Transaction ID')) !!}
	                            @if ($errors->has('tran_id'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('tran_id') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date_icici') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date_icici', isset($value_date_icici) ? $value_date_icici : null, array('id'=>'value_date_icici','placeholder' => 'Value Date','class' => 'form-control','tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date_icici'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date_icici') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('txn_posted_date') ? 'has-error' : '' }}">
	                            <strong>Txn Posted Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('txn_posted_date', isset($txn_posted_date) ? $txn_posted_date : null, array('id'=>'txn_posted_date','placeholder' => 'Txn Posted Date','class' => 'form-control', 'tabindex' => '3')) !!}
	                        	</div>
	                            @if ($errors->has('txn_posted_date'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('txn_posted_date') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('cr_dr') ? 'has-error' : '' }}">
	                            <strong>CR/DR:</strong>
	                            {!! Form::text('cr_dr', null, array('id'=>'cr_dr','class' => 'form-control','tabindex' => '7','placeholder' => 'CR/DR')) !!}
	                            @if ($errors->has('cr_dr'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('cr_dr') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('desc_icici') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('desc_icici', null, array('id'=>'desc_icici','class' => 'form-control','rows' => '5','tabindex' => '4','placeholder' => 'Description')) !!}
	                            @if ($errors->has('desc_icici'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('desc_icici') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name_icici') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {{-- Client Name Display --}}
	                            {!! Form::select('company_name_icici', $clients, null, array('id'=>'company_name_icici','class' => 'form-control','tabindex' => '5')) !!}
	                            @if ($errors->has('company_name_icici'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name_icici') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount_icici') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount_icici', null, array('id'=>'amount_icici','class' => 'form-control','tabindex' => '6','placeholder' => 'Amount')) !!}
	                            @if ($errors->has('amount_icici'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount_icici') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks_icici') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks_icici', null, array('id'=>'remarks_icici','class' => 'form-control','rows' => '5','tabindex' => '8','placeholder' => 'Remarks')) !!}
	                            @if ($errors->has('remarks_icici'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks_icici') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit
                </button>
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
	                            <strong>Voucher No:<span class = "required_fields">*</span></strong>
	                            {!! Form::text('voucher_no', null, array('id'=>'voucher_no','class' => 'form-control','tabindex' => '1','placeholder' => 'Voucher No')) !!}
	                            @if ($errors->has('voucher_no'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('voucher_no') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date_other') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date_other', isset($value_date_other) ? $value_date_other : null, array('id'=>'value_date_other','placeholder' => 'Value Date','class' => 'form-control', 'tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date_other'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date_other') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks_other') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks_other', null, array('id'=>'remarks_other','class' => 'form-control','rows' => '5','tabindex' => '3','placeholder' => 'Remarks')) !!}
	                            @if ($errors->has('remarks_other'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks_other') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name_other') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {{-- Client Name Display --}}
	                            {!! Form::select('company_name_other', $clients, null, array('id'=>'company_name_other','class' => 'form-control','tabindex' => '4')) !!}
	                            @if ($errors->has('company_name_other'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name_other') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount_other') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount_other', null, array('id'=>'amount_other','class' => 'form-control','tabindex' => '5','placeholder' => 'Amount')) !!}
	                            @if ($errors->has('amount_other'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('amount_other') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('mode_of_receipt') ? 'has-error' : '' }}">
	                            <strong>Mode of Receipt:</strong>
	                            {!! Form::text('mode_of_receipt', null, array('id'=>'mode_of_receipt','class' => 'form-control','rows' => '5','tabindex' => '6','placeholder' => 'Mode Of Receipt')) !!}
	                            @if ($errors->has('mode_of_receipt'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('mode_of_receipt') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit
                </button>
             	</div>
        	</div>
        	{!! Form::close() !!}
        </div>
    </div>
</div>

@stop

@section('customscripts')
    <script type="text/javascript">

    $(document).ready(function(){
        $("#receipt_form").validate({
            rules: {
                "ref_no": {
                    required: true
                },
                "tran_id": {
                    required: true
                },
                "voucher_no": {
                    required: true
                },
            },
            messages: {
                "ref_no": {
                    required: "Reference Number is Required Field."
                },
                "tran_id": {
                    required: "Transaction ID is Required Field."
                },
                "voucher_no": {
                    required: "Voucher Number is Required Field."
                },
            }
        });

        $("#company_name_hdfc").select2();
        $("#company_name_icici").select2();
        $("#company_name_other").select2();

        $("#remarks_hdfc").wysihtml5();
        $("#remarks_icici").wysihtml5();
        $("#remarks_other").wysihtml5();

        // hdfc
        $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });
        $('#date').datepicker().datepicker('setDate', 'today');

        $("#value_date_hdfc").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });
        $('#value_date_hdfc').datepicker().datepicker('setDate', 'today');

        // icici
        $("#txn_posted_date").datetimepicker({
                format:'DD-MM-YYYY h:mm A',
        });

        $("#value_date_icici").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });
        $('#value_date_icici').datepicker().datepicker('setDate', 'today');


        // other
        $("#value_date_other").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });
        $('#value_date_other').datepicker().datepicker('setDate', 'today');
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
