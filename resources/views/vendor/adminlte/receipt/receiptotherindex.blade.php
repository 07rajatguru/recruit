@extends('adminlte::page')

@section('title', 'Receipt Other')

@section('content_header')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Receipt Other</h2>
        </div>

        <div class="pull-right">
        	<a class="btn btn-primary" href="{{ route('receipt.otherimport') }}"> Import Recepit Other</a>
            <a class="btn btn-success" href="{{ route('receipt.othercreate') }}"> Create New Recepit Other</a>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
    	<div class="box-body col-xs-4 col-sm-4 col-md-4">
    		<div class="form-group">
	        	{!! Form::select('bank_type', $bank_type,$bank, array('id'=>'bank_type','class' => 'form-control')) !!}
        	</div>
		</div>

		<div class="box-body col-xs-2 col-sm-2 col-md-2">
			<div class="form-group">
				{!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_bank()']) !!}
			</div>
		</div>
	</div>
</div>

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="receipt_other">
    @if($bank == 'hdfc')
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Reference No</th>
                <th>Value Date</th>
                <th>Company Name</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <?php $i = 0;?>
        <tbody>
            @foreach($receipt_data as $key => $value)
            <tr>
                <td>{{++$i}}</td>
                <td>{{ $value['date'] }}</td>
                <td>{{ $value['ref_no'] }}</td>
                <td>{{ $value['value_date'] }}</td>
                <td>{{ $value['company_name'] }}</td>
                <td>{{ $value['amount'] }}</td>
                <td>{{ $value['description'] }}</td>
                <td>{{ $value['remarks'] }}</td>            
            </tr>
            @endforeach
        </tbody>
    @elseif($bank == 'icici')
        <thead>
            <tr>
                <th>No</th>
                <th>Transaction ID</th>
                <th>Value Date</th>
                <th>Txn Posted <br/>Date</th>
                <th>Company Name</th>
                <th>Amount</th>
                <th>CR/<br/>DR</th>
                <th>Description</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <?php $i = 0;?>
        <tbody>
            @foreach($receipt_data as $key => $value)
            <tr>
                <td>{{++$i}}</td>
                <td>{{ $value['trans_id'] }}</td>
                <td>{{ $value['value_date'] }}</td>
                <td>{{ $value['txn_posted_date'] }}</td>
                <td>{{ $value['company_name'] }}</td>
                <td>{{ $value['amount'] }}</td>
                <td>{{ $value['cr'] }}</td>
                <td>{{ $value['description'] }}</td>
                <td>{{ $value['remarks'] }}</td>            
            </tr>
            @endforeach
        </tbody>
    @elseif($bank == 'other')
        <thead>
            <tr>
                <th>No</th>
                <th>Voucher No</th>
                <th>Value Date</th>
                <th>Company Name</th>
                <th>Amount</th>
                <th>Mode of Receipt</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <?php $i = 0;?>
        <tbody>
            @foreach($receipt_data as $key => $value)
            <tr>
                <td>{{++$i}}</td>
                <td>{{ $value['voucher_no'] }}</td>
                <td>{{ $value['value_date'] }}</td>
                <td>{{ $value['company_name'] }}</td>
                <td>{{ $value['amount'] }}</td>
                <td>{{ $value['mode_of_receipt'] }}</td>
                <td>{{ $value['remarks'] }}</td>            
            </tr>
            @endforeach
        </tbody>
    @endif
</table>

@stop

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function(){
            var table = jQuery('#receipt_other').DataTable( {
                responsive: true,
                stateSave : true,
                "bStateSave": true,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );

            
        });

        function select_bank(){

            var bank = $("#bank_type").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/receipt/other';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="bank" value="'+bank+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection