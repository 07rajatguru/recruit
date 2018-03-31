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
                <h2>Please confirm the details and generate Bills Made</h2>
            @elseif( $action == 'edit')
                <h2>Edit BNM</h2>
            @else
                <h2>Create BNM</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('bnm.index') }}"> Back</a>
        </div>

    </div>

</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>

@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

@if( $action == 'edit')
    {!! Form::model($bnm,['method' => 'PATCH','files' => true, 'id' => 'bills_form','route' => ['bnm.update', $bnm->id]] ) !!}
@else
    {!! Form::open(array('route' => 'bnm.store','files' => true,'method'=>'POST','id' => 'bills_form')) !!}
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
                                <strong>Select Job Opening:</strong>
                                {!! Form::select('jobopen', $jobopen,$job_id, array('id'=>'jobopen','class' => 'form-control', 'tabindex' => '23','onchange'=>'prefilleddata()' )) !!}
                                @if ($errors->has('job'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('job') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('client_name') ? 'has-error' : '' }}">
                                <strong>Client Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('client_name', null, array('id'=>'client_name','placeholder' => 'Client Name','class' => 'form-control' )) !!}
                                @if ($errors->has('client_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('client_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('client_contact_number') ? 'has-error' : '' }}">
                                <strong>Client Contact Number: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('client_contact_number', null, array('id'=>'client_contact_number','placeholder' => 'Client Contact Number','class' => 'form-control' )) !!}
                                @if ($errors->has('client_contact_number'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('client_contact_number') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_name') ? 'has-error' : '' }}">
                                <strong>Candidate Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('candidate_name', array(),$candidate_id, array('id'=>'candidate_name','class' => 'form-control', 'tabindex' => '23','onchange'=>'prefilledcandidatedata()' )) !!}
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
                                    {!! Form::text('date_of_joining', isset($doj) ? $doj : null, array('id'=>'date_of_joining','placeholder' => 'Joining Date','class' => 'form-control' )) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('fixed_salary') ? 'has-error' : '' }}">
                                <strong>Fixed Salary: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('fixed_salary', null, array('id'=>'fixed_salary','placeholder' => 'Fixed Salary','class' => 'form-control' )) !!}
                                @if ($errors->has('fixed_salary'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('fixed_salary') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('source') ? 'has-error' : '' }}">
                                <strong>Source: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('source', null, array('id'=>'source','placeholder' => 'Source','class' => 'form-control' )) !!}
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
                            {!! Form::text('company_name', null, array('id'=>'company_name','placeholder' => 'Company Name','class' => 'form-control' )) !!}
                            @if ($errors->has('company_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('client_email_id') ? 'has-error' : '' }}">
                            <strong>Client Email ID: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('client_email_id', null, array('id'=>'client_email_id','placeholder' => 'Client Email ID','class' => 'form-control' )) !!}
                            @if ($errors->has('client_email_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('client_email_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('designation_offered') ? 'has-error' : '' }}">
                            <strong>Designation offered: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('designation_offered', null, array('id'=>'designation_offered','placeholder' => 'Designation offered','class' => 'form-control' )) !!}
                            @if ($errors->has('designation_offered'))
                                <span class="help-block">
                                <strong>{{ $errors->first('designation_offered') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('candidate_contact_number') ? 'has-error' : '' }}">
                            <strong>Candidate Contact Number: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('candidate_contact_number', null, array('id'=>'candidate_contact_number','placeholder' => 'Candidate Mobile','class' => 'form-control' )) !!}
                            @if ($errors->has('candidate_contact_number'))
                                <span class="help-block">
                                <strong>{{ $errors->first('candidate_contact_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_location') ? 'has-error' : '' }}">
                            <strong>Job Location: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('job_location', null, array('id'=>'job_location','placeholder' => 'Job Location','class' => 'form-control' )) !!}
                            @if ($errors->has('job_location'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_location') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('percentage_charged') ? 'has-error' : '' }}">
                            <strong>Percentage Charged: </strong>
                            {!! Form::text('percentage_charged', null, array('id'=>'percentage_charged','placeholder' => 'Percentage Charged','class' => 'form-control' )) !!}
                            @if ($errors->has('percentage_charged'))
                                <span class="help-block">
                                <strong>{{ $errors->first('percentage_charged') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('address_of_communication') ? 'has-error' : '' }}">
                            <strong>Address of communication: <span class = "required_fields">*</span> </strong>
                            {!! Form::textarea('address_of_communication', null, array('rows'=>'3','id'=>'address_of_communication','placeholder' => 'Address of communication','class' => 'form-control' )) !!}
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
                                <strong>Employee Name :  <span class = "required_fields">*</span> </strong>
                                {!! Form::select('employee_name_1', $users,$employee_name[0], array('id'=>'employee_name_1','class' => 'form-control', 'tabindex' => '7' )) !!}
                                {!! Form::select('employee_name_2', $users,$employee_name[1], array('id'=>'employee_name_2','class' => 'form-control', 'tabindex' => '7' )) !!}
                                {!! Form::select('employee_name_3', $users,$employee_name[2], array('id'=>'employee_name_3','class' => 'form-control', 'tabindex' => '7' )) !!}
                                {!! Form::select('employee_name_4', $users,$employee_name[3], array('id'=>'employee_name_4','class' => 'form-control', 'tabindex' => '7' )) !!}
                                {!! Form::select('employee_name_5', $users,$employee_name[4], array('id'=>'employee_name_5','class' => 'form-control', 'tabindex' => '7' )) !!}
                            </div>
                        </div>

                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group">
                                <strong>Employee Percentage:  <span class = "required_fields">*</span> </strong>
                                {!! Form::text('employee_percentage_1', $employee_percentage[0], array('id'=>'employee_percentage_1','placeholder' => 'Employee 1 Percentage','class' => 'form-control employee_perce' )) !!}
                                {!! Form::text('employee_percentage_2', $employee_percentage[1], array('id'=>'employee_percentage_2','placeholder' => 'Employee 2 Percentage','class' => 'form-control employee_perce' )) !!}
                                {!! Form::text('employee_percentage_3', $employee_percentage[2], array('id'=>'employee_percentage_3','placeholder' => 'Employee 3 Percentage','class' => 'form-control employee_perce' )) !!}
                                {!! Form::text('employee_percentage_4', $employee_percentage[3], array('id'=>'employee_percentage_4','placeholder' => 'Employee 4 Percentage','class' => 'form-control employee_perce' )) !!}
                                {!! Form::text('employee_percentage_5', $employee_percentage[4], array('id'=>'employee_percentage_5','placeholder' => 'Employee 5 Percentage','class' => 'form-control employee_perce' )) !!}
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
                            <strong>Upload Documents:</strong>
                            <input type="file" name="upload_documents[]" multiple class="form-control" />
                        </div>

                    </div>

                </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">

                <button type="submit" class="btn btn-primary">Submit</button>

            </div>

        </div>

    </div>

</div>
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

            prefilleddata();
            // on job select pre filled all data

            $(function () {
                $("#date_of_joining").datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
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
                    }
                },
                messages: {
                    "company_name": {
                        required: "Company Name is required field."
                    },
                    "candidate_name": {
                        required: "Candidate Name is required field."
                    },
                    "candidate_contact_number":{
                        required: "Candidate Contact Number is required field."
                    },
                    "designation_offered": {
                        required: "Designation Offered is required field."
                    },
                    "date_of_joining": {
                        required: "Date of joining is required field."
                    },
                    "job_location":{
                        required: "Job Location is required field."
                    },
                    "fixed_salary": {
                        required: "Fixed Salary is required field."
                    },
                    "source": {
                        required: "Source is required field."
                    },
                    "client_name": {
                        required: "Client Name is required field."
                    },
                    "client_contact_number":{
                        required: "Client Contact Number is required field."
                    },
                    "client_email_id": {
                        required: "Client Email Id is required field."
                    },
                    "address_of_communication":{
                        required: "Address of Communication is required field."
                    }
                }
            });
        });
        
        function prefilleddata() {

            var job_id = $("#jobopen").val();
            var candidate_id = $("#candidate_id").val();

            if(job_id>0){
                // get client data from job id
                $.ajax({
                    url:'/bills/getclientinfo',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        var cname = data.cname;
                        var coordinator_name = data.coordinator_name;
                        var mail = data.mail;
                        var mobile = data.mobile;
                        var designation = data.designation;
                        var location = data.job_location;

                        $("#company_name").val(cname);
                        $("#client_name").val(coordinator_name);
                        $("#client_email_id").val(mail);
                        $("#client_contact_number").val(mobile);
                        $("#designation_offered").val(designation);
                        $("#job_location").val(location);
                    }
                });

                // get candidate data
                $.ajax({
                    url:'/bills/getcandidateinfo',
                    data:'job_id='+job_id,
                    dataType:'json',
                    success: function(data){
                        var returnvalue = data.returnvalue;
                        var response = data.data;
                        if(returnvalue=='valid'){
                            $('#candidate_name').empty();
                            $('#candidate_name').append($('<option></option>').val(0).html(''));
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
