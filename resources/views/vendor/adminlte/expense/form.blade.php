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
                <h2>Edit Expense</h2>
            @else
                <h2>Create New Expense</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('expense.index') }}">Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($expense,['method' => 'PATCH','files' => true,'autocomplete' => 'off','id' => 'expense_form', 'route' => ['expense.update', $expense->id]] ) !!}
@else
    {!! Form::open(array('route' => 'expense.store','files' => true,'autocomplete' => 'off','method'=>'POST', 'id' => 'expense_form')) !!}
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

                            <div class="form-group {{ $errors->has('gst_no') ? 'has-error' : '' }}">
                            <strong>Vendor GST No: </strong>
                            
                            {!! Form::text('gst_no', null,array('id'=>'gst_no','placeholder' => 'GST No.','class' => 'form-control', 'tabindex' => '3' )) !!}
                           
                            @if ($errors->has('gst_no'))
                                <span class="help-block">
                                <strong>{{ $errors->first('gst_no') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                                <strong>Billing Amount(Exclude GST):<span class = "required_fields">*</span></strong>
                                {!! Form::text('amount', null, array('id'=>'amount','placeholder' => 'Amount','class' => 'form-control', 'tabindex' => '5' )) !!}
                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('cgst') ? 'has-error' : '' }}" id="hcgst">
                            <strong>CGST : <span class = "required_fields">*</span></strong>
                            
                            {!! Form::text('cgst', null,array('id'=>'cgst','placeholder' => 'CGST','class' => 'form-control')) !!}
                           
                            @if ($errors->has('cgst'))
                                <span class="help-block">
                                <strong>{{ $errors->first('cgst') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('bill_amount') ? 'has-error' : '' }}">
                            <strong>Total Bill Amount : <span class = "required_fields">*</span> </strong>
                            
                            {!! Form::text('bill_amount', null,array('id'=>'bill_amount','placeholder' => 'Bill Amount','class' => 'form-control', 'tabindex' => '7' )) !!}
                           
                            @if ($errors->has('bill_amount'))
                                <span class="help-block">
                                <strong>{{ $errors->first('bill_amount') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('tax') ? 'has-error' : '' }}">
                                <strong>Input Tax Credit</strong>
                                {!! Form::select('tax', $input_tax, $tax, array('id'=>'tax','class' => 'form-control', 'tabindex' => '9' )) !!}
                                @if ($errors->has('tax'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('tax') }}</strong>
                                </span>
                                @endif
                            </div>

                             <div class="form-group {{ $errors->has('tds') ? 'has-error' : '' }}">
                            <strong>TDS:</strong>
                            
                            {!! Form::text('tds', null,array('id'=>'tds','placeholder' => 'TDS','class' => 'form-control', 'tabindex' => '11','onchange'=>'prefilledtds()')) !!}
                           
                            @if ($errors->has('tds'))
                                <span class="help-block">
                                <strong>{{ $errors->first('tds') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('tds_date') ? 'has-error' : '' }}">
                                <strong>TDS Payment Date:</strong>
                                {!! Form::text('tds_date', null, array('id'=>'tds_date','placeholder' => 'Date','class' => 'form-control', 'tabindex' => '13' )) !!}
                                @if ($errors->has('tds_date'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('tds_date') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                <strong>Remarks:</strong>
                                {!! Form::textarea('remarks', null, array('id'=>'remarks','placeholder' => 'Remarks','class' => 'form-control', 'tabindex' => '15'  )) !!}
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
                            

                            <div class="form-group {{ $errors->has('vendor_id') ? 'has-error' : '' }}">
                            <strong>Paid To: <span class = "required_fields">*</span></strong>
                            {!! Form::select('vendor_id', $vendor,$vendor_id, array('id'=>'vendor_id','class' => 'form-control','tabindex' => '2','onchange'=>'prefilleddata()')) !!}
                            @if ($errors->has('vendor_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('vendor_id') }}</strong>
                                </span>
                            @endif
                            </div>


                            <div class="form-group {{ $errors->has('pan_no') ? 'has-error' : '' }}">
                                <strong>Vendor PAN No : </strong>
                                {!! Form::text('pan_no', null, array('id'=>'pan_no','placeholder' => 'PAN','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('pan_no'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('pan_no') }}</strong>
                                </span>
                                @endif
                            </div>
                            

                            <div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }}">
                            <strong>GST : <span class = "required_fields">*</span></strong>
                            
                            {!! Form::text('gst', null,array('id'=>'gst','placeholder' => 'GST','class' => 'form-control', 'tabindex' => '6','onchange'=>'prefilledgst()')) !!}
                           
                            @if ($errors->has('gst'))
                                <span class="help-block">
                                <strong>{{ $errors->first('gst') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('igst') ? 'has-error' : '' }}" id="higst">
                            <strong>IGST : <span class = "required_fields">*</span></strong>
                            
                            {!! Form::text('igst', null,array('id'=>'igst','placeholder' => 'IGST','class' => 'form-control', 'tabindex' => '8' )) !!}
                           
                            @if ($errors->has('igst'))
                                <span class="help-block">
                                <strong>{{ $errors->first('igst') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('sgst') ? 'has-error' : '' }}" id="hsgst">
                            <strong>SGST : <span class = "required_fields">*</span></strong>
                            
                            {!! Form::text('sgst', null,array('id'=>'sgst','placeholder' => 'SGST','class' => 'form-control')) !!}
                           
                            @if ($errors->has('sgst'))
                                <span class="help-block">
                                <strong>{{ $errors->first('sgst') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('paid_amount') ? 'has-error' : '' }}">
                            <strong>Paid Amount: <span class = "required_fields">*</span> </strong>
                            
                            {!! Form::text('paid_amount', null,array('id'=>'paid_amount','placeholder' => 'Paid Amount','class' => 'form-control', 'tabindex' => '10' )) !!}
                           
                            @if ($errors->has('paid_amount'))
                                <span class="help-block">
                                <strong>{{ $errors->first('paid_amount') }}</strong>
                                </span>
                            @endif
                            </div>

                            <div class="form-group {{ $errors->has('head') ? 'has-error' : '' }}">
                                <strong>Expense Head: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('head', $head, $expense_head, array('id'=>'head','class' => 'form-control', 'tabindex' => '12' )) !!}
                                @if ($errors->has('head'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('head') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('tds_deduct') ? 'has-error' : '' }}">
                            <strong>TDS Detucted:</strong>
                            
                            {!! Form::text('tds_deduct', null,array('id'=>'tds_deduct','placeholder' => 'TDS Detucted','class' => 'form-control', 'tabindex' => '14' )) !!}
                           
                            @if ($errors->has('tds_deduct'))
                                <span class="help-block">
                                <strong>{{ $errors->first('tds_deduct') }}</strong>
                                </span>
                            @endif
                            </div>

                             <div class="form-group {{ $errors->has('pmode') ? 'has-error' : '' }}">
                                <strong>Payment Mode:<span class = "required_fields">*</span></strong>
                                {!! Form::select('pmode', $payment_mode, $pmode, array('id'=>'pmode','class' => 'form-control', 'tabindex' => '16' )) !!}
                                @if ($errors->has('pmode'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('pmode') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('ptype') ? 'has-error' : '' }}">
                                <strong>Payment Type:<span class = "required_fields">*</span></strong>
                                  {!! Form::select('ptype', $payment_type, $ptype, array('id'=>'ptype','class' => 'form-control', 'tabindex' => '18' )) !!}
                                 @if ($errors->has('ptype'))
                                <span class="help-block">
                                <strong>{{ $errors->first('ptype') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('reference_number') ? 'has-error' : '' }}">
                                <strong>Reference Number:</strong>
                                {!! Form::text('reference_number', null, array('id'=>'reference_number','placeholder' => 'Reference Number','class' => 'form-control', 'tabindex' => '20' )) !!}
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
                    <div class="form-group">
                        <strong>Document 1:</strong>
                        {!! Form::file('document1', null, array('id'=>'document1','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Document 2:</strong>
                        {!! Form::file('document2', null, array('id'=>'document2','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Document 3:</strong>
                        {!! Form::file('document3', null, array('id'=>'document3','class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="form-group">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($expense) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    </div>


@section('customscripts')
    <script>
        $(document).ready(function(){

             prefilleddata();
             prefilledgst();
             prefilledtds();

            $( "#head" ).select2();

            $("#vendor_id").select2();

          

            $("#date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#tds_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#expense_form").validate({
                rules: {
                    "date": {
                        required: true
                    },
                    "vendor_id": {
                        required: true
                    },
                    /*"gst_no": {
                        required: true
                    },
                    "pan_no": {
                        required: true
                    },*/
                    "amount": {
                        required: true
                    },
                     "gst": {
                        required: true
                    },
                     "igst": {
                        required: true
                    },
                     "sgst": {
                        required: true
                    },
                     "cgst": {
                        required: true
                    },
                     "bill_amount": {
                        required: true
                    },
                     "paid_amount": {
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
                    }
                },
                messages: {
                    "date": {
                        required: "Date is required field."
                    },
                    "vendor_id": {
                        required: "Paid To is required field."
                    },
                   /* "gst_no": {
                        required: "GST No. is required field."
                    },
                    "pan_no": {
                        required: "PAN No. is required field."
                    },*/
                    "amount": {
                        required: "Amount is required field."
                    },
                    "gst": {
                        required: "GST(%) is required field."
                    },
                    "cgst": {
                        required: "CGST is required field."
                    },
                    "sgst": {
                        required: "SGST is required field."
                    },
                    "igst": {
                        required: "IGST is required field."
                    },
                     "bill_amount": {
                        required: "Bill Amount is required field."
                    },
                     "paid_amount": {
                        required: "Paid Amount is required field."
                    },
                    "head": {
                        required: "Expense Head is required field."
                    },
                    "pmode": {
                        required: "Payment Mode is required field."
                    },
                    "ptype": {
                        required: "Payment Type is required field."
                    }
                }
            });
        });


    function prefilleddata() {

            var vendor_id = $("#vendor_id").val();

            if(vendor_id>0){
                
                $.ajax({
                    url:'/expense/getvendorinfo',
                    data:'vendor_id='+vendor_id,
                    dataType:'json',
                    success: function(data){
                     
                        var gstno = data.gstno;
                        var panno = data.panno;

                        $("#gst_no").val(gstno);
                        $("#pan_no").val(panno);

                        var gst_res=data.gst_res;

                        if(gst_res=="24")
                        {

                            $("#higst").hide();
                            $("#hcgst").show();
                            $("#hsgst").show();

                        }
                        else
                        {
                            $("#hcgst").hide();
                            $("#hsgst").hide();
                            $("#higst").show();
                        }

                    }
                });


            }

        }

        function prefilledgst()
        {
            var cgst=$("#amount").val()*$("#gst").val()/100;
            var c_gst=cgst/2;
            $("#cgst").val(c_gst);

            var sgst=$("#amount").val()*$("#gst").val()/100;
            var s_gst=sgst/2;
            $("#sgst").val(s_gst);

            var igst=$("#amount").val()*$("#gst").val()/100;
            $("#igst").val(igst);

            var bill=$("#amount").val();
            var cc_gst=$("#cgst").val();
            var ss_gst=$("#sgst").val();
            var total_bill=parseFloat(bill)+parseFloat(cc_gst)+parseFloat(ss_gst);
            if (total_bill > 0) {
                $("#bill_amount").val(total_bill);
                $("#paid_amount").val(total_bill);
            }
            else {
                $("#bill_amount").val(0);
                $("#paid_amount").val(0);   
            }
        }

        function prefilledtds()  
        {   
            var tds = $("#tds").val();
            if (tds > 0) {
                var c_tds=$("#bill_amount").val()*$("#tds").val()/100;
                $("#tds_deduct").val(c_tds);
            }
            else{
                $("#tds_deduct").val(0);
            }

           var total_amount=$("#bill_amount").val();
           var deduct_amount=$("#tds_deduct").val();
           var paid=parseFloat(total_amount)-parseFloat(deduct_amount);
           $("#paid_amount").val(paid);
        }

    
    </script>
@endsection

