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
                <h2>Edit Vendor</h2>
            @else
                <h2>Create New Vendor</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('vendor.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($vendor,['method' => 'PATCH','files' => true, 'id' => 'vendor_Form','autocomplete' => 'off', 'route' => ['vendor.update', $vendor->id]] ) !!}
@else
 {!! Form::open(array('route' => 'vendor.store','method'=>'POST','files' => true , 'id' => 'vendor_Form')) !!}
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
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Company Name: <span class = "required_fields">*</span> </strong>
                            
                            {!! Form::text('name', null,array('id'=>'name','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                           
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                         <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                            <strong>Mobile Number:<span class = "required_fields">*</span> </strong>
                            {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' =>'Mobile Number','class' => 'form-control', 'tabindex' => '3')) !!}
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('landline') ? 'has-error' : '' }}">
                            <strong>Landline Number:</strong>
                            {!! Form::number('landline', null, array('id'=>'landline','placeholder' => 'Landline Number','class' => 'form-control', 'tabindex' => '5'  )) !!}
                            @if ($errors->has('landline'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('landline') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <strong>Email:</strong>
                            {!! Form::email('email', null, array('id'=>'email','placeholder' => 'Email','class' => 'form-control', '', 'tabindex' => '7','onfocusout' => 'checkEmailValidation();')) !!}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                        <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                            <strong>Contact Point:</strong>
                            {!! Form::text('contact', null, array('id'=>'contact','placeholder' => 'Contact Point','class' => 'form-control', 'tabindex' => '2' )) !!}
                            @if ($errors->has('contact'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contact') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                            <strong>Designation:</strong>
                            {!! Form::text('designation', null, array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control', 'tabindex' => '4' )) !!}
                            @if ($errors->has('designation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('designation') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
                            <strong>Type Of Organization:</strong>
                            {!! Form::text('organization', null, array('id'=>'organization','placeholder' => 'Type Of Organization','class' => 'form-control', 'tabindex' => '6' )) !!}
                            @if ($errors->has('organization'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('organization') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">
                            <strong>Website:</strong>
                            {!! Form::text('website', null, array('id'=>'website','placeholder' => 'Website','class' => 'form-control', 'tabindex' => '8','onfocusout' => 'checkWebsiteValidation();')) !!}
                            @if ($errors->has('website'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('website') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Address Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group {{ $errors->has('vendor_address') ? 'has-error' : '' }}">
                            <strong>Vendor Address:</strong>
                            {!! Form::text('vendor_address', null, array('id'=>'vendor_address','placeholder' => 'Vendor Address','class' => 'form-control', 'tabindex' => '8')) !!}
                            @if ($errors->has('vendor_address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('vendor_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('pincode') ? 'has-error' : '' }}">
                            <strong>PinCode:</strong>
                            {!! Form::text('pincode', null, array('id'=>'pincode','placeholder' => 'PinCode','class' => 'form-control', 'tabindex' => '11' )) !!}
                            @if ($errors->has('pincode'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pincode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                            <strong>State:</strong>
                            {!! Form::select('state_id', $state,$state_id, array('id'=>'state_id','class' => 'form-control', 'tabindex' => '12' )) !!}
                            @if ($errors->has('state_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Bank Details</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                        <div class="form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
                            <strong>Bank Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('bank_name', null,array('id'=>'bank_name','placeholder' => 'Bank Name','class' => 'form-control', 'tabindex' => '13' )) !!}
                           
                            @if ($errors->has('bank_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('bank_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('account') ? 'has-error' : '' }}">
                            <strong>Account Number:<span class = "required_fields">*</span> </strong>
                            {!! Form::text('account', null, array('id'=>'account','placeholder' => 'Account Number','class' => 'form-control', 'tabindex' => '15'  )) !!}
                            @if ($errors->has('account'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('account') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('acc_type') ? 'has-error' : '' }}">
                            <strong>Type Of Account:<span class = "required_fields">*</span></strong>
                            {!! Form::select('acc_type', $acc_type,null, array('id'=>'acc_type','class' => 'form-control', 'tabindex' => '17' )) !!}
                            @if ($errors->has('acc_type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('acc_type') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('bank_address') ? 'has-error' : '' }}">
                            <strong>Bank Address:<span class = "required_fields">*</span> </strong>
                            {!! Form::text('bank_address', null, array('id'=>'bank_address','placeholder' => 'Bank Address','class' => 'form-control', 'tabindex' => '14'  )) !!}
                            @if ($errors->has('bank_address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('bank_address') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('ifsc') ? 'has-error' : '' }}">
                            <strong>IFSC Code:<span class = "required_fields">*</span> </strong>
                            {!! Form::text('ifsc', null, array('id'=>'ifsc','placeholder' => 'IFSC Code','class' => 'form-control', 'tabindex' => '16' )) !!}
                            @if ($errors->has('ifsc'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ifsc') }}</strong>
                                </span>
                            @endif
                       </div>

                        <div class="form-group {{ $errors->has('nicr') ? 'has-error' : '' }}">
                        <strong>MICR Code: </strong>
                        {!! Form::text('nicr', null, array('id'=>'nicr','placeholder' => 'MICR Code','class' => 'form-control', 'tabindex' => '18' )) !!}
                            @if ($errors->has('nicr'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nicr') }}</strong>
                                </span>
                            @endif
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Statutory Request</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('gst_no') ? 'has-error' : '' }}">
                            <strong>GST No: </strong>
                            {!! Form::text('gst_no', null,array('id'=>'gst_no','placeholder' => 'GST No.','class' => 'form-control', 'tabindex' => '19' )) !!}
                           
                            @if ($errors->has('gst_no'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gst_no') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('pan_no') ? 'has-error' : '' }}">
                            <strong>PAN Number: </strong>
                            {!! Form::text('pan_no', null, array('id'=>'pan_no','placeholder' => 'PAN Number','class' => 'form-control', 'tabindex' => '21'  )) !!}
                            @if ($errors->has('pan_no'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pan_no') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('gst_charge') ? 'has-error' : '' }}">
                            <strong>GST Charge(%): </strong>
                            {!! Form::select('gst_charge', $gst_charge,null, array('id'=>'gst_charge','class' => 'form-control', 'tabindex' => '20' )) !!}
                            @if ($errors->has('gst_charge'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gst_charge') }}</strong>
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
                        {!! Form::file('document1', null, array('id'=>'doc1','class' => 'form-control')) !!}
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
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
           {!! Form::submit(isset($vendor) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
  </div>
</div>

{!! Form::close() !!}

@section('customscripts')
    <script>
        $(document).ready(function() {

            $('#vendor_Form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#mobile').keypress(function (e) {

                var length = jQuery(this).val().length;

                if(length > 9) {
                    return false;
                } else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                } else if((length == 0) && (e.which == 48)) {
                    return false;
                }
            });

            $('#nicr').keypress(function (e) {

                var length = jQuery(this).val().length;

                if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                } else if((length == 0) && (e.which == 48)) {
                    return false;
                }
            });

            $("#gst_no").change(function () {

                var inputvalues = $(this).val();
                let regTest = /\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/.test(inputvalues);

                if(regTest){
                    let a=65,b=55,c=36;
                        return Array['from'](g).reduce((i,j,k,g)=>{ 
                    p=(p=(j.charCodeAt(0)<a?parseInt(j):j.charCodeAt(0)-b)*(k%2+1))>c?1+(p-c):p;
                        return k<14?i+p:j==((c=(c-(i%c)))<10?c:String.fromCharCode(c+b));
                    },0); 
                }
                else {
                    alert('Please Enter Valid GSTIN Number');    
                    document.getElementById("gst_no").value = '';
                    document.getElementById("gst_no").focus();  
                }    
            });

            $("#vendor_Form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "mobile": {
                        required: true
                    },
                    "bank_name": {
                        required: true
                    },
                    "acc_type": {
                        required: true
                    },
                    "bank_address": {
                        required: true
                    },
                    "account": {
                        required: true
                    },
                    "ifsc": {
                        required: true
                    }
                },
                messages: {
                     "name": {
                        required: "Name is required."
                    },
                    "mobile": {
                        required: "Mobile is required."
                    },
                    "bank_name": {
                        required: "Name of Bank is required."
                    },
                    "acc_type": {
                        required: "Account Type is required."
                    },
                    "bank_address": {
                        required: "Bank Address is required."
                    },
                    "account": {
                        required: "Accoutnt_No. is required."
                    },
                    "ifsc": {
                        required: "IFSC Code is required."
                    }
                },
            });
        });

        function checkEmailValidation() {

            var email_value = $("#email").val();

            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
       
            if(email_value != '') {
                if (reg.test(email_value) == false) {
                    
                    alert('Please Enter Valid Email Address.');
                    document.getElementById("email").value = '';
                    document.getElementById("email").focus(); 
                    return false;
                }
            }
            return true;
        }

        function checkWebsiteValidation() {

            var website = $("#website").val();

            var website_regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
       
            if(website != '') {
                if (website_regexp.test(website) == false) {
                    alert('Please Enter Valid Website URL');
                    return false;
                }
            }
            return true;
        }
    </script>
@endsection