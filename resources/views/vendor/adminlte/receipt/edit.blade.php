@extends('adminlte::page')

@section('title', 'Edit Receipt')

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
            <h2>Edit Receipt {{$type}}</h2>
        </div>

        <div class="pull-right">
        	@if($type == 'talent' || $type == 'Talent')
        		<a class="btn btn-primary" href="{{ route('receipt.talent') }}"> Back</a>
        	@elseif($type == 'temp' || $type == 'Temp')
        		<a class="btn btn-primary" href="{{ route('receipt.temp') }}"> Back</a>
        	@else
        		<a class="btn btn-primary" href="{{ route('receipt.other') }}"> Back</a>
        	@endif
        </div>
    </div>
</div>

{!! Form::model($receipt,['method' => 'PATCH','route' => ['receipt.update',$receipt['id']],'id' => 'receipt_form']) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
    		{{--Edit HDFC Bank Receipt--}}
    		@if($bank_type == 'hdfc')
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
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date',isset($value_date) ? $value_date : null, array('id'=>'value_date','placeholder' => 'Value Date','class' => 'form-control', 'tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date') }}
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
	                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('description', null, array('id'=>'description','class' => 'form-control','rows' => '7','tabindex' => '4','placeholder' => 'Description')) !!}
	                            @if ($errors->has('description'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('description') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {!! Form::select('company_name', $vendors,$vendor_id, array('id'=>'company_name','class' => 'form-control','tabindex' => '5')) !!}
	                            @if ($errors->has('company_name'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('company_name') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
	                            <strong>Amount:</strong>
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control','tabindex' => '6','placeholder' => 'Amount')) !!}
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
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5','tabindex' => '7','placeholder' => 'Remarks')) !!}
	                            @if ($errors->has('remarks'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                	<button type="submit" class="btn btn-primary">Submit</button>
             	</div>

            {{--Edit ICICI Bank Receipt--}}
            @elseif($bank_type == 'icici')
             	<div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('trans_id') ? 'has-error' : '' }}">
	                            <strong>Transaction ID:<span class = "required_fields">*</span></strong>
	                            {!! Form::text('trans_id', null, array('id'=>'trans_id','class' => 'form-control','tabindex' => '1','placeholder' => 'Transaction ID')) !!}
	                            @if ($errors->has('trans_id'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('trans_id') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date', isset($value_date) ? $value_date : null, array('id'=>'value_date','placeholder' => 'Value Date','class' => 'form-control','tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date') }}
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
	                        <div class="form-group {{ $errors->has('cr') ? 'has-error' : '' }}">
	                            <strong>CR/DR:</strong>
	                            {!! Form::text('cr', null, array('id'=>'cr','class' => 'form-control','tabindex' => '7','placeholder' => 'CR/DR')) !!}
	                            @if ($errors->has('cr'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('cr') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
	                            <strong>Description:</strong>
	                            {!! Form::textarea('description', null, array('id'=>'description','class' => 'form-control','rows' => '5','tabindex' => '4','placeholder' => 'Description')) !!}
	                            @if ($errors->has('description'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('description') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>

	                <div class="box-body col-xs-6 col-sm-6 col-md-6">
	                    <div class="">
	                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
	                            <strong>Company Name:</strong>
	                            {!! Form::select('company_name', $vendors, $vendor_id, array('id'=>'company_name','class' => 'form-control','tabindex' => '5')) !!}
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
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control','tabindex' => '6','placeholder' => 'Amount')) !!}
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
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5','tabindex' => '8','placeholder' => 'Remarks')) !!}
	                            @if ($errors->has('remarks'))
	                                <span class="help-block">
	                            <strong>{{ $errors->first('remarks') }}</strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                	<button type="submit" class="btn btn-primary">Submit</button>
             	</div>

            {{--Edit Other Bank Receipt--}}
            @elseif($bank_type == 'other')
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
	                        <div class="form-group {{ $errors->has('value_date') ? 'has-error' : '' }}">
	                            <strong>Value Date:</strong>
	                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>  
	                            {!! Form::text('value_date', isset($value_date) ? $value_date : null, array('id'=>'value_date','placeholder' => 'Value Date','class' => 'form-control', 'tabindex' => '2')) !!}
	                        	</div>
	                            @if ($errors->has('value_date'))
	                            <span class="help-block">
	                            <strong>
	                            	{{ $errors->first('value_date') }}
	                            </strong>
	                            </span>
	                            @endif
	                        </div>
	                    </div>

	                    <div class="">
	                        <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
	                            <strong>Remarks:</strong>
	                            {!! Form::textarea('remarks', null, array('id'=>'remarks','class' => 'form-control','rows' => '5','tabindex' => '3','placeholder' => 'Remarks')) !!}
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
	                            {!! Form::select('company_name', $vendors, $vendor_id, array('id'=>'company_name','class' => 'form-control','tabindex' => '4')) !!}
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
	                            {!! Form::text('amount', null, array('id'=>'amount','class' => 'form-control','tabindex' => '5','placeholder' => 'Amount')) !!}
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
                	<button type="submit" class="btn btn-primary">Submit</button>
             	</div>
        	@endif
        </div>
    </div>
</div>
<input type="hidden" name="type" id="type" value="{{$type}}">
<input type="hidden" name="bank_type" id="bank_type" value="{{$bank_type}}">
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

        $("#company_name").select2();

        $("#remarks").wysihtml5();

        $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });

        $("#value_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
        });

		$("#txn_posted_date").datetimepicker({
                format:'DD-MM-YYYY h:mm A'
        });
    });
 </script>
@endsection
