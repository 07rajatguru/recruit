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
            @if($generate_bm==1)
                @if($status==0)
                    <h2>Please confirm the details and generate Recovery</h2>
                @else
                    <h2>Edit Recovery</h2>
                @endif
            @elseif( $action == 'edit')
                <h2>Edit Forecasting</h2>
            @else
                <h2>Create Forecasting</h2>
            @endif
        </div>
        <div class="pull-right">
            @if($status==0)
                <a class="btn btn-primary" href="{{ route('forecasting.index') }}"> Back</a>
            @else
                <a class="btn btn-primary" href="{{ route('bills.recovery') }}"> Back</a>
            @endif
        </div>

    </div>

</div>

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

@if( $action == 'edit')
    {!! Form::model($bnm,['method' => 'PATCH','files' => true, 'id' => 'bills_form','autocomplete' => 'off', 'route' => ['forecasting.update', $bnm->id]] ) !!}

    @if(isset($year) && $year != '')
        <input type="hidden" name="year" id="year" value="{{ $year }}">
    @endif
@else
    {!! Form::open(array('route' => 'forecasting.store','files' => true,'method'=>'POST','id' => 'bills_form','autocomplete' => 'off')) !!}
@endif

<input type="hidden" id="generateBM" name="generateBM" value="{{$generate_bm}}">

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group {{ $errors->has('job') ? 'has-error' : '' }}">
                                <strong>Select Job Opening: <span class = "required_fields">*</span></strong>
                                {!! Form::select('jobopen', $jobopen,$job_id, array('id'=>'jobopen','class' => 'form-control', 'tabindex' => '1','onchange'=>'prefilleddata()' )) !!}
                                @if ($errors->has('job'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('job') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('client_name') ? 'has-error' : '' }}">
                                <strong>Client Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('client_name', null, array('id'=>'client_name','placeholder' => 'Client Name','tabindex' => '3','class' => 'form-control' )) !!}
                                @if ($errors->has('client_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('client_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('client_contact_number') ? 'has-error' : '' }}">
                                <strong>Client Contact Number: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('client_contact_number', null, array('id'=>'client_contact_number','placeholder' => 'Client Contact Number','class' => 'form-control', 'tabindex' => '5')) !!}
                                @if ($errors->has('client_contact_number'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('client_contact_number') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_name') ? 'has-error' : '' }}">
                                <strong>Candidate Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('candidate_name', array(''=>'Select Candidate'),null, array('id'=>'candidate_name','class' => 'form-control', 'tabindex' => '7', 'onchange'=> 'prefilledcandidatedata()' )) !!}
                                @if ($errors->has('candidate_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('candidate_name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <input type="hidden" id="candidate_id" name="candidate_id" value="{{$candidate_id}}">

                            <div class="form-group {{ $errors->has('date_of_joining') ? 'has-error' : '' }}">
                                <strong>Joining Date: <span class = "required_fields">*</span> </strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('date_of_joining', isset($doj) ? $doj : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control','tabindex' => '9')) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('fixed_salary') ? 'has-error' : '' }}">
                                <strong>Fixed Salary: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('fixed_salary', null, array('id'=>'fixed_salary','placeholder' => 'Fixed Salary','class' => 'form-control', 'tabindex' => '11','onfocusout' => 'setDecimal();')) !!}
                                @if ($errors->has('fixed_salary'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('fixed_salary') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('source') ? 'has-error' : '' }}">
                                <strong>Candidate Source:</strong>
                                {!! Form::select('source', $candidateSource,null, array('id'=>'source','class' => 'form-control', 'tabindex' => '13')) !!}
                                @if ($errors->has('source'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('source') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">

                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            <strong>Company Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('company_name', null, array('id'=>'company_name','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '2')) !!}
                            @if ($errors->has('company_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('client_email_id') ? 'has-error' : '' }}">
                            <strong>Client Email ID: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('client_email_id', null, array('id'=>'client_email_id','placeholder' => 'Client Email ID','class' => 'form-control', 'tabindex' => '4' )) !!}
                            @if ($errors->has('client_email_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('client_email_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('designation_offered') ? 'has-error' : '' }}">
                            <strong>Designation offered: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('designation_offered', null, array('id'=>'designation_offered','placeholder' => 'Designation offered','class' => 'form-control', 'tabindex' => '6' )) !!}
                            @if ($errors->has('designation_offered'))
                                <span class="help-block">
                                <strong>{{ $errors->first('designation_offered') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('candidate_contact_number') ? 'has-error' : '' }}">
                            <strong>Candidate Contact Number: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('candidate_contact_number', null, array('id'=>'candidate_contact_number','placeholder' => 'Candidate Mobile','class' => 'form-control', 'tabindex' => '8' )) !!}
                            @if ($errors->has('candidate_contact_number'))
                                <span class="help-block">
                                <strong>{{ $errors->first('candidate_contact_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_location') ? 'has-error' : '' }}">
                            <strong>Job Location: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('job_location', null, array('id'=>'job_location','placeholder' => 'Job Location','class' => 'form-control', 'tabindex' => '10' )) !!}
                            @if ($errors->has('job_location'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_location') }}</strong>
                                </span>
                            @endif
                        </div>
                        @if($isSuperAdmin || $isAccountant || $isOperationsExecutive)
                        <div class="form-group {{ $errors->has('percentage_charged') ? 'has-error' : '' }}">
                            <strong>Percentage Charged:</strong>
                            {!! Form::text('percentage_charged', null, array('id'=>'percentage_charged','placeholder' => 'Percentage Charged','class' => 'form-control', 'tabindex' => '12' )) !!}
                            @if ($errors->has('percentage_charged'))
                                <span class="help-block">
                                <strong>{{ $errors->first('percentage_charged') }}</strong>
                                </span>
                            @endif
                        </div>
                        @endif

                        <div class="form-group {{ $errors->has('address_of_communication') ? 'has-error' : '' }}">
                            <strong>Address of Communication: <span class = "required_fields">*</span> </strong>
                            {!! Form::textarea('address_of_communication', null, array('rows'=>'3','id'=>'address_of_communication','placeholder' => 'Address of Communication','class' => 'form-control', 'tabindex' => '14' )) !!}
                            @if ($errors->has('address_of_communication'))
                                <span class="help-block">
                                <strong>{{ $errors->first('address_of_communication') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Efforts</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group">
                                <strong>Employee Name :  </strong>
                                {!! Form::select('employee_name_1', $users,$employee_name[0], array('id'=>'employee_name_1','class' => 'form-control', 'tabindex' => '15')) !!}
                                {!! Form::select('employee_name_2', $users,$employee_name[1], array('id'=>'employee_name_2','class' => 'form-control', 'tabindex' => '17' )) !!}
                                {!! Form::select('employee_name_3', $users,$employee_name[2], array('id'=>'employee_name_3','class' => 'form-control', 'tabindex' => '19' )) !!}
                                {!! Form::select('employee_name_4', $users,$employee_name[3], array('id'=>'employee_name_4','class' => 'form-control', 'tabindex' => '21' )) !!}
                                {!! Form::select('employee_name_5', $users,$employee_name[4], array('id'=>'employee_name_5','class' => 'form-control', 'tabindex' => '23' )) !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group">
                                <strong>Employee Percentage: </strong>
                                {!! Form::number('employee_percentage_1', $employee_percentage[0], array('id'=>'employee_percentage_1','placeholder' => 'Employee 1 Percentage','class' => 'form-control employee_perce' , 'tabindex' => '16','onfocusout' => 'checkPercentage();')) !!}
                                {!! Form::number('employee_percentage_2', $employee_percentage[1], array('id'=>'employee_percentage_2','placeholder' => 'Employee 2 Percentage','class' => 'form-control employee_perce', 'tabindex' => '18','onfocusout' => 'checkPercentage();' )) !!}
                                {!! Form::number('employee_percentage_3', $employee_percentage[2], array('id'=>'employee_percentage_3','placeholder' => 'Employee 3 Percentage','class' => 'form-control employee_perce', 'tabindex' => '20','onfocusout' => 'checkPercentage();')) !!}
                                {!! Form::number('employee_percentage_4', $employee_percentage[3], array('id'=>'employee_percentage_4','placeholder' => 'Employee 4 Percentage','class' => 'form-control employee_perce', 'tabindex' => '22','onfocusout' => 'checkPercentage();' )) !!}
                                {!! Form::number('employee_percentage_5', $employee_percentage[4], array('id'=>'employee_percentage_5','placeholder' => 'Employee 5 Percentage','class' => 'form-control employee_perce', 'tabindex' => '24','onfocusout' => 'checkPercentage();')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isSuperAdmin || $isAccountant || $isOperationsExecutive)
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title">Lead Efforts</h3>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            <div class="">
                                <div class="form-group">
                                    <strong>Employee Name :  </strong>
                                    {!! Form::select('lead_name', $users,$lead_name, array('id'=>'lead_name','class' => 'form-control', 'tabindex' => '25' )) !!}
                                </div>
                            </div>
                        </div>

                        <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            <div class="">
                                <div class="form-group">
                                    <strong>Employee Percentage:  </strong>
                                    {!! Form::number('lead_percentage', $lead_percentage, array('id'=>'lead_percentage','class' => 'form-control employee_perce','tabindex' => '26','onfocusout' => 'checkLeadPercentage();')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xs-12 col-sm-12 col-md-12">
            @if( $action == 'add')
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title">Attachment Information</h3>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Candidate Unedited Resume : <span class = "required_fields">*</span></strong>
                            <input type="file" name="unedited_resume" id="unedited_resume" class="form-control" tabindex="27">
                        </div>

                        <div class="form-group">
                            <strong>Offer Letter : </strong>
                            <input type="file" name="offer_letter" id="offer_letter" class="form-control" tabindex="28">
                        </div>

                        <div class="form-group">
                            <strong>Other Documents:</strong>
                            <input type="file" name="upload_documents[]" id="upload_documents" class="form-control" tabindex="29" multiple>
                        </div>
                    </div>
                </div>
            @elseif($action == 'edit')
             <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title">Attachmetns</h3>
                        &nbsp;&nbsp;
                        @include('adminlte::bills.upload', ['data' => $bnm, 'name' => 'billattachments'])
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Category</th>
                        </tr>
                                @if(isset($billsdetails['files']) && sizeof($billsdetails['files']) > 0)
                                @foreach($billsdetails['files'] as $key => $value)

                                <tr>
                                    <td>
                                       <a download href="{{ $value['url'] }}">
                                            <i class="fa fa-fw fa-download"></i>
                                        </a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $bnm, 'name' => 'billattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['fileName'] }}</a></td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['size'] }}</td>    
                                    <td>{{ $value['category'] }}</td>
                                </tr>
                             @endforeach
                            @endif
                    </table>
                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($bnm) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="action" id="action" value="{{ $action }}">
{!! Form::close() !!}

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            $("#jobopen").select2();
            $("#candidate_name").select2();
            $("#employee_name_1").select2();
            $("#employee_name_2").select2();
            $("#employee_name_3").select2();
            $("#employee_name_4").select2();
            $("#employee_name_5").select2();
            $("#lead_name").select2();
            //$("#address_of_communication").wysihtml5();

            prefilleddata();
            // on job select pre filled all data

            $(function () {
                $("#date_of_joining").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                });

                $('.fa-calendar').click(function() {
                    $("#date_of_joining").focus();
                });
            });

            // on click of enter dont submit the form
            $('#bills_form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#fixed_salary').keypress(function (e) {
                if(e.which == 46 || e.which == 190) {
                    return true;
                }
                else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $("#unedited_resume").bind('change', function() {

                var ext = $('#unedited_resume').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });

            $("#offer_letter").bind('change', function() {

                var ext = $('#offer_letter').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });

            $("#bills_form").validate({
                rules: {
                    "company_name": {
                        required: true
                    },
                    "candidate_name": {
                        required: true
                    },
                    "candidate_contact_number": {
                        required: true
                    },
                    "designation_offered": {
                        required: true
                    },
                    "date_of_joining": {
                        required: true
                    },
                    "job_location": {
                        required: true
                    },
                    "fixed_salary": {
                        required: true
                    },
                    "source": {
                        required: true
                    },
                    "client_name": {
                        required: true
                    },
                    "client_contact_number": {
                        required: true
                    },
                    "client_email_id": {
                        required: true
                    },
                    "address_of_communication": {
                        required: true
                    },
                    "lead_name":{
                        required: true
                    },
                    "lead_percentage": {
                        required: true
                    },
                    "unedited_resume": {
                        required: true
                    },
                },
                messages: {
                    "company_name": {
                        required: "Company Name is Required Field."
                    },
                    "candidate_name": {
                        required: "Candidate Name is Required Field."
                    },
                    "candidate_contact_number":{
                        required: "Candidate Contact Number is Required Field."
                    },
                    "designation_offered": {
                        required: "Designation Offered is Required Field."
                    },
                    "date_of_joining": {
                        required: "Date of joining is Required Field."
                    },
                    "job_location":{
                        required: "Job Location is Required Field."
                    },
                    "fixed_salary": {
                        required: "Fixed Salary is Required Field."
                    },
                    "source": {
                        required: "Source is Required Field."
                    },
                    "client_name": {
                        required: "Client Name is Required Field."
                    },
                    "client_contact_number":{
                        required: "Client Contact Number is Required Field."
                    },
                    "client_email_id": {
                        required: "Client Email Id is Required Field."
                    },
                    "address_of_communication":{
                        required: "Address of Communication is Required Field."
                    },
                    "lead_name":{
                        required: "Lead Efforts is Required Field."
                    },
                    "lead_percentage": {
                        required: "Lead Percentage is Required Field."
                    },
                    "unedited_resume": {
                        required: "Please Select File."
                    },
                }
            });
        });

        function setDecimal() {
            
            var fixed_salary = $("#fixed_salary").val();
            var decimal_value = parseFloat(fixed_salary).toFixed(2);
            $("#fixed_salary").val(decimal_value);
        }

        function checkPercentage() {

            for (var i = 1; i <= 5; i++) {

                var employee_percentage = $("#employee_percentage_"+i).val();

                if(employee_percentage > 0 ) {

                    if(employee_percentage > 100) {

                        alert('Please Enter Percentge upto 100.');
                        $("#employee_percentage_"+i).val('');
                        $("#employee_percentage_"+i).focus();
                        return false;
                    }
                    return true;
                }
            }
        }

        function checkLeadPercentage() {

            var lead_percentage = $("#lead_percentage").val();

            if(lead_percentage > 0 ) {

                if(lead_percentage > 100) {

                    alert('Please Enter Percentge upto 100.');
                    $("#lead_percentage").val('0');
                    $("#lead_percentage").focus();
                    return false;
                }
                return true;
            }
        }
        
        function prefilleddata() {

            var job_id = $("#jobopen").val();
            var candidate_id = $("#candidate_id").val();
            var app_url = "{!! env('APP_URL'); !!}";
            var action = $("#action").val();

            if(job_id>0){
                // get client data from job id
                $.ajax({
                    url: app_url+'/bills/getclientinfo',
                    //url: 'http://127.0.0.1:8000/bills/getclientinfo',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        var cname = data.cname;
                        var coordinator_name = data.coordinator_name;
                        var mail = data.mail;
                        var mobile = data.mobile;
                        var designation = data.designation;
                        var location = data.job_location;
                        var percentage_charged = data.percentage_charged;
                        
                        $("#company_name").val(cname);
                        $("#client_name").val(coordinator_name);
                        $("#client_email_id").val(mail);
                        $("#client_contact_number").val(mobile);
                        $("#designation_offered").val(designation);
                        $("#job_location").val(location);

                        if(action == 'add'){
                            $("#percentage_charged").val(percentage_charged);
                        }
                        else {
                            
                        }
                    }
                });

                // get candidate data
                $.ajax({
                    url: app_url+'/bills/getcandidateinfo',
                    //url: 'http://127.0.0.1:8000/bills/getcandidateinfo',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        var returnvalue = data.returnvalue;
                        var response = data.data;
                        if(returnvalue=='valid'){
                            $('#candidate_name').empty();
                            $('#candidate_name').append($('<option></option>').val('').html('Select'));
                            for(var i=0;i<response.length;i++){
                                $('#candidate_name').append($('<option data-content="'+response[i].mobile+'"></option>').val(response[i].id).html(response[i].name));
                                $('#candidate_name').select2();
                                $("#candidate_name").select2('val',candidate_id);
                            }
                        }
                    }
                });
            }
        }

        function prefilledcandidatedata() {
            var candidate_id = $("#candidate_name").val();
            var mobile  = $("#candidate_name>option:selected").attr('data-content');
            $("#candidate_contact_number").val(mobile);
        }
    </script>
@endsection
