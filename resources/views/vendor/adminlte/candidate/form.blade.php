
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
                <h2>Edit Candidate</h2>
            @else
                <h2>Create New Candidate</h2>
            @endif
            
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('candidate.index') }}"> Back</a>
        </div>
    </div>
</div>

@if($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

@if(isset($action))
    @if($action == 'edit')

        {!! Form::model($candidate,['method' => 'PUT', 'files' => true, 'route' => ['candidate.update', $candidate['id']],'id'=>'candidate_form', 'novalidate'=>'novalidate','autocomplete' => 'off','onsubmit' => "return formValidation()"]) !!}

        {!! Form::hidden('candidateId', $candidate['id'], array('id'=>'candidateId')) !!}

    @else

        {!! Form::open(['files' => true, 'route' => 'candidate.store','id'=>'candidate_form', 'novalidate'=>'novalidate','autocomplete' => 'off','onsubmit' => "return formValidation()"]) !!}

    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}
    {!! Form::hidden('jobid',isset($_GET['jobid'])?$_GET['jobid']:'0',array('id'=>'jobid')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            <div class="form-group {{ $errors->has('fname') ? 'has-error' : '' }}">
                                <strong>Full Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('fname', null, array('id'=>'fname','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '1','onchange' => 'validFullNameText();')) !!}
                                @if ($errors->has('fname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('fname') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                <strong>Mobile Number: <span class = "required_fields">*</span> </strong>

                                {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' => 'Mobile Number','class' => 'form-control', 'tabindex' => '3','maxLength' => '10','minLength' => '10')) !!}

                                {!! Form::hidden('candidate_id', null, array('id'=>'candidate_id', 'placeholder' => 'Mobile Number','class' => 'form-control')) !!}
                                
                                @if ($errors->has('mobile'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Other Number:</strong>
                                {!! Form::text('phone', null, array('id'=>'phone','placeholder' => 'Other Number','class' => 'form-control', 'tabindex' => '5','maxLength' => '10','minLength' => '10')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('job') ? 'has-error' : '' }}">
                             <strong>Associated to Company:</strong>
                                  {!! Form::select('jobopen', $jobopen,$job_id, array('id'=>'jobopen','class' => 'form-control', 'tabindex' => '7' )) !!}
                                 @if ($errors->has('job'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job') }}</strong>
                                </span>
                             @endif
                             </div>

                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">

                            {{--<div class="form-group {{ $errors->has('lname') ? 'has-error' : '' }}">
                                <strong>Last Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('lname', null, array('id'=>'lname','placeholder' => 'Last Name','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('lname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('lname') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: <span class = "required_fields">*</span> </strong>
                                {!! Form::email('email', null, array('id'=>'email','placeholder' => 'Email','class' => 'form-control', '', 'tabindex' => '2' )) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <strong>Marital Status:  </strong>
                                {!! Form::select('maritalStatus', $maritalStatus,null, array('id'=>'maritalStatus','class' => 'form-control', 'tabindex' => '4' )) !!}
                            </div>

                             <div class="form-group ">
                                <strong>Sex:</strong>
                                {!! Form::select('candidateSex', $candidateSex,null, array('id'=>'candidateSex','class' => 'form-control', 'tabindex' => '6' )) !!}
                            </div>

                            <div class="form-group">
                                <strong>Candidate Source:</strong>
                                {!! Form::select('candidateSource', $candidateSource,null, array('id'=>'candidateSource','class' => 'form-control', 'tabindex' => '8')) !!}
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
                            <div class="form-group {{ $errors->has('candidate_address') ? 'has-error' : '' }}">
                                <strong>Candidate Address:</strong>
                                {!! Form::text('candidate_address', null, array('id'=>'candidate_address','placeholder' => 'Candidate Address','class' => 'form-control', 'tabindex' => '9' , 'onFocus'=>"geolocate()" )) !!}
                                @if ($errors->has('candidate_address'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('candidate_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group {{ $errors->has('street1') ? 'has-error' : '' }}">
                                <strong>Street 1:</strong>
                                {!! Form::text('street1', null, array('id'=>'street1','placeholder' => 'Street1','class' => 'form-control', 'tabindex' => '10' )) !!}
                                @if ($errors->has('street1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street1') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                <strong>City:</strong>
                                {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control', 'tabindex' => '12' )) !!}
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('zipcode') ? 'has-error' : '' }}">
                                <strong>Zip Code:</strong>
                                {!! Form::text('zipcode', null, array('id'=>'zipcode','placeholder' => 'ZipCode','class' => 'form-control', 'tabindex' => '14' )) !!}
                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group {{ $errors->has('street2') ? 'has-error' : '' }}">
                                <strong>Street 2:</strong>
                                {!! Form::text('street2', null, array('id'=>'street2','placeholder' => 'Street2','class' => 'form-control', 'tabindex' => '11' )) !!}
                                @if ($errors->has('street2'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street2') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                                <strong>State:</strong>
                                {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State','class' => 'form-control', 'tabindex' => '13' )) !!}
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <strong>Country:</strong>
                                {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country','class' => 'form-control', 'tabindex' => '15' )) !!}
                                @if ($errors->has('country'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
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
                    <h3 class="box-title">Education and Professional Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group">
                                <strong>Education Qualification:</strong>
                                {!! Form::select('highest_qualification', $highest_qualification,$highest_qualification_id, array ('id'=> 'highest_qualification','class' => 'form-control', 'tabindex' => '16')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('experience_months') ? 'has-error' : '' }}">
                                <strong>Experience Months:</strong>
                                {!! Form::number('experience_months', null, array('id'=>'experience_months','placeholder' => 'Experience Months','class' => 'form-control', 'min' => '0', 'tabindex' => '18' )) !!}
                                @if ($errors->has('experience_months'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('experience_months') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('current_employer') ? 'has-error' : '' }}">
                                <strong>Current Employer:</strong>
                                {!! Form::text('current_employer', null, array('id'=>'current_employer','placeholder' => 'Current Employer','class' => 'form-control', 'tabindex' => '20' )) !!}
                                @if ($errors->has('current_employer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_employer') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('current_salary') ? 'has-error' : '' }}">
                                <strong>Current Salary:</strong>
                                {!! Form::text('current_salary', null, array('id'=>'current_salary','placeholder' => 'Current Salary','class' => 'form-control', 'tabindex' => '22' )) !!}
                                @if ($errors->has('current_salary'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_salary') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : '' }}">
                                <strong>Skype Id:</strong>
                                {!! Form::text('skype_id', null, array('id'=>'skype_id','placeholder' => 'Skype Id','class' => 'form-control', 'tabindex' => '24' )) !!}
                                @if ($errors->has('skype_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('skype_id') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group {{ $errors->has('experience_years') ? 'has-error' : '' }}">
                                <strong>Experience years:</strong>
                                {!! Form::number('experience_years', null, array('id'=>'experience_years','placeholder' => 'Experience years','class' => 'form-control', 'min' => '0', 'tabindex' => '17' )) !!}
                                @if ($errors->has('experience_years'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('experience_years') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('current_job_title') ? 'has-error' : '' }}">
                                <strong>Current Job Title:</strong>
                                {!! Form::text('current_job_title', null, array('id'=>'current_job_title','placeholder' => 'Current Job Title','class' => 'form-control', 'tabindex' => '19' )) !!}
                                @if ($errors->has('current_job_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_job_title') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('expected_salary') ? 'has-error' : '' }}">
                                <strong>Expected Salary:</strong>
                                {!! Form::text('expected_salary', null, array('id'=>'expected_salary','placeholder' => 'Expected Salary','class' => 'form-control', 'tabindex' => '21' )) !!}
                                @if ($errors->has('expected_salary'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expected_salary') }}</strong>
                                        </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('skill') ? 'has-error' : '' }}">
                                <strong>Skill:</strong>
                                {!! Form::text('skill', null, array('id'=>'skill','placeholder' => 'Skill','class' => 'form-control', 'tabindex' => '23' )) !!}
                                @if ($errors->has('skill'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('skill') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Other Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('candidateStatus') ? 'has-error' : '' }}">
                            <strong>Candidate Status:</strong>
                            {!! Form::select('candidateStatus', $candidateStatus,null, array('id'=>'candidateStatus','class' => 'form-control', 'tabindex' => '24' )) !!}
                            @if ($errors->has('candidateStatus'))
                                <span class="help-block">
                                <strong>{{ $errors->first('candidateStatus') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('candidateSource') ? 'has-error' : '' }}">
                            <strong>Candidate Source:</strong>
                            {!! Form::select('candidateSource', $candidateSource,null, array('id'=>'candidateSource','class' => 'form-control', 'tabindex' => '25')) !!}
                            @if ($errors->has('candidateSource'))
                                <span class="help-block">
                                <strong>{{ $errors->first('candidateSource') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="col-xs-12 col-sm-12 col-md-12">
    @if( $action == 'add')
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Attachments</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Resume:</strong>
                    <input type="file" name="resume" id="resume" class="form-control">
                </div>

                <div class="form-group">
                    <strong>Formatted Resume:</strong>
                    <input type="file" name="formatted_resume" id="formatted_resume" class="form-control">
                </div>

                <div class="form-group">
                    <strong>Cover Letter:</strong>
                    <input type="file" name="cover_letter" id="cover_letter" class="form-control">
                </div>

                <div class="form-group">
                    <strong>Others:</strong>
                    <input type="file" name="others[]" id="others" class="form-control" multiple>
                </div>
            </div>
        </div>
    @elseif($action == 'edit')
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Attachments</h3>
                &nbsp;&nbsp;
                @include('adminlte::candidate.upload', ['data' => $candidate, 'name' => 'candidateattachments'])
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Uploaded by</th>
                        <th>Size</th>
                        <th>Category</th>
                    </tr>
                    @if(isset($candidateDetails) && sizeof($candidateDetails) > 0)
                        @foreach($candidateDetails as $key => $value)
                            <tr>
                                <td>
                                    <a download href="{{ $value['url'] }}">
                                        <i class="fa fa-fw fa-download"></i>
                                    </a>
                                    &nbsp;
                                    @include('adminlte::partials.confirm', ['data' => $value,'id'=> $candidate['id'], 'name' => 'candidateattachments' ,'display_name'=> 'Candidate Attachment'])
                                </td>
                                <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['fileName'] }}</a></td>
                                <td>{{ $value['uploaded_by'] }}</td>
                                <td>{{ $value['size'] }}</td>
                                <td>{{ $value['category'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    @endif
    </div>
    <div class="form-group">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($candidate) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    </div>

    {!! Form::close() !!}
@else

    <div class="error-page">
        <h2 class="headline text-info"> 403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Whoops, looks like something went wrong.
            </h3>
        </div><!-- /.error-content -->
    </div>

@endif

@section('customscripts')
    <script>
        $(document).ready(function() {

            $('#candidate_form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $("#jobopen").select2();
             $("#highest_qualification").select2();

            $("#candidate_form").validate({
                rules: {
                    "fname": {
                        required: true
                    },
                    "mobile": {
                        required: true
                    },
                    "email": {
                        required: true
                    }
                },
                
                messages: {
                    "fname": {
                        required: "Full Name is Required."
                    },
                    "mobile": {
                        required: "Mobile Number is Required."
                    },
                    "email": {
                        required: "Email is Required."
                    }
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

            $('#phone').keypress(function (e) {

                var length = jQuery(this).val().length;

                if(length > 9) {
                    return false;
                } else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                } else if((length == 0) && (e.which == 48)) {
                    return false;
                }
            });

            $("#resume").bind('change', function() {

                var ext = $('#resume').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });

             $("#formatted_resume").bind('change', function() {

                var ext = $('#formatted_resume').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });

             $("#cover_letter").bind('change', function() {

                var ext = $('#cover_letter').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf','txt']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });

        });

        function validFullNameText() {

            var txt = document.getElementById("fname").value ;
            var FullNameLength = txt.trim().length;

            if(FullNameLength < 1) {

                alert("Blank Entry Not Allowed.")
                document.getElementById("fname").value = '';
                document.getElementById("fname").focus();
            }
        }

        function formValidation() {

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

            var experience_months = $("#experience_months").val();

            if(experience_months > 11) {

                alert('Please Enter Month upto 1 to 11.');
                document.getElementById("experience_months").value = '';
                document.getElementById("experience_months").focus(); 
                return false;
            }
            return true;
        }


        // This example displays an address form, using the autocomplete feature
        // of the Google Places API to help users fill in the information.

        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

        var placeSearch, autocomplete;
        var componentForm = {
            street1: 'short_name',
            street2: 'long_name',
            city: 'long_name',
            state: 'long_name',
            country: 'long_name',
            zipcode: 'short_name',
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */
                    (document.getElementById('candidate_address')),
                    {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);


        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            for (var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form in billing.
            try{
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if(addressType=='locality'){
                        document.getElementById('city').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='country'){
                        document.getElementById('country').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='administrative_area_level_1'){
                        document.getElementById('state').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='street_number'){
                        document.getElementById('street1').value = place.address_components[i]['short_name'];
                    }
                    if(addressType=='route'){
                        document.getElementById('street2').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='postal_code'){
                        document.getElementById('zipcode').value = place.address_components[i]['long_name'];
                    }
                }
            }
            catch (exception){

            }
        }

        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.

        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete" async defer></script>
@endsection
