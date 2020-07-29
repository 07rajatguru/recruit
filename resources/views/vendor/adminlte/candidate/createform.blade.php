@extends('adminlte::master')

@section('title', 'Candidate')

@section('customs_css')
<style>
    .error{
        color:#f56954 !important;
    }
    body {

        /*margin: 5% 5% 5% 5%;
        border: 2px solid #000000;
        background-color: #9D5CAC;*/
    }
</style>

@endsection

@section('body')

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

<div class="row">
    <div class="col-lg-12 margin-tb" style="background: #9D5CAC;border: 2px solid #9D5CAC;">
        <div class="text-center">
            <h1 style="color: #ffffff;">Submit Your Resume</h1>
        </div>
    </div>
</div>

{!! Form::open(['files' => true,'autocomplete' => 'off','route' => 'candidate.storef' ,'id'=>'candidate_form']) !!}

{!! Form::hidden('action', $action, array('id'=>'action')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('fname') ? 'has-error' : '' }}">
                                <strong>Full Name: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('fname', null, array('id'=>'fname','placeholder' => 'Full Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('fname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fname') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                <strong>Mobile Number: <span class = "required_fields">*</span>
                                </strong>
                                {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' => 'Mobile Number','class' => 'form-control', 'tabindex' => '3','maxLength' => '10')) !!}

                                {!! Form::hidden('candidate_id', null, array('id'=>'candidate_id','placeholder' => 'Mobile Number','class' => 'form-control' )) !!}

                                @if ($errors->has('mobile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                <strong>Phone Number:</strong>
                                {!! Form::text('phone', null, array('id'=>'phone','placeholder' => 'Phone Number','class' => 'form-control', 'tabindex' => '5','maxLength' => '10')) !!}
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>Email: <span class = "required_fields">*</span> </strong>
                                {!! Form::email('email', null, array('id'=>'email','placeholder' => 'Email','class' => 'form-control', '', 'tabindex' => '2' )) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong> 
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('maritalStatus') ? 'has-error' : '' }}">
                                <strong>Marital Status:  </strong>
                                {!! Form::select('maritalStatus', $maritalStatus,null, array('id'=>'maritalStatus','class' => 'form-control', 'tabindex' => '4' )) !!}
                                @if ($errors->has('maritalStatus'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('maritalStatus') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidateSex') ? 'has-error' : '' }}">
                                <strong>Sex:</strong>
                                {!! Form::select('candidateSex', $candidateSex,null, array('id'=>'candidateSex','class' => 'form-control', 'tabindex' => '6' )) !!}
                                @if ($errors->has('candidateSex'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('candidateSex') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Address Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-sm-12 col-md-12 col-xss-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('candidate_address') ? 'has-error' : '' }}">
                                <strong>Candidate Address:</strong>
                                {!! Form::text('candidate_address', null, array('id'=>'candidate_address','placeholder' => 'Candidate Address','class' => 'form-control', 'tabindex' => '7' , 'onFocus'=>"geolocate()" )) !!}
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
                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('street1') ? 'has-error' : '' }}">
                                <strong>Street 1:</strong>
                                {!! Form::text('street1', null, array('id'=>'street1','placeholder' => 'Street1','class' => 'form-control', 'tabindex' => '8' )) !!}
                                @if ($errors->has('street1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street1') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                <strong>City:</strong>
                                {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control', 'tabindex' => '10' )) !!}
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('zipcode') ? 'has-error' : '' }}">
                                <strong>Zip Code:</strong>
                                {!! Form::text('zipcode', null, array('id'=>'zipcode','placeholder' => 'ZipCode','class' => 'form-control', 'tabindex' => '12' )) !!}
                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('street2') ? 'has-error' : '' }}">
                                <strong>Street 2:</strong>
                                {!! Form::text('street2', null, array('id'=>'street2','placeholder' => 'Street2','class' => 'form-control', 'tabindex' => '9' )) !!}
                                @if ($errors->has('street2'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street2') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                                <strong>State:</strong>
                                {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State','class' => 'form-control', 'tabindex' => '11' )) !!}
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                                <strong>Country:</strong>
                                {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country','class' => 'form-control', 'tabindex' => '13' )) !!}
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
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Education and Professional Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="form-group {{ $errors->has('current_employer') ? 'has-error' : '' }}">
                            <strong>Last Employer: <span class = "required_fields">*</span></strong>
                            {!! Form::text('current_employer', null, array('id'=>'current_employer','placeholder' => 'Last Employer','class' => 'form-control', 'tabindex' => '14' )) !!}
                            @if ($errors->has('current_employer'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_employer') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('skill') ? 'has-error' : '' }}">
                            <strong>Key Skills:</strong>
                            {!! Form::text('skill', null, array('id'=>'skill','placeholder' => 'Key Skills','class' => 'form-control', 'tabindex' => '16' )) !!}
                            @if ($errors->has('skill'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('skill') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('educational_qualification_id') ? 'has-error' : '' }}">
                            <strong>Education Qualification: <span class = "required_fields">*</span>
                            </strong>
                            {!! Form::select('educational_qualification_id', $educationqualification,null, array ('id'=> 'educational_qualification_id','class' => 'form-control', 'tabindex' => '18','onchange' => 'getSpecialization()')) !!}
                            @if ($errors->has('educational_qualification_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('educational_qualification_id') }}
                                    </strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 form-group no-padding exp-years {{ $errors->has('experience_years') ? 'has-error' : '' }}">
                            <strong>Experience years:</strong>
                            {!! Form::number('experience_years', null, array('id'=>'experience_years','placeholder' => 'Experience years','class' => 'form-control', 'min' => '0', 'tabindex' => '20' )) !!}
                            @if ($errors->has('experience_years'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('experience_years') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 form-group no-padding exp-month {{ $errors->has('experience_months') ? 'has-error' : '' }}">
                            <strong>Experience Months:</strong>
                            {!! Form::number('experience_months', null, array('id'=>'experience_months','placeholder' => 'Experience Months','class' => 'form-control', 'min' => '0', 'tabindex' => '21' )) !!}
                            @if ($errors->has('experience_months'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('experience_months') }}</strong>
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

                    <div class="box-body col-xs-6 col-sm-6 col-md-6 col-xss-12">
                        <div class="form-group {{ $errors->has('current_job_title') ? 'has-error' : '' }}">
                            <strong>Last Job Title: <span class = "required_fields">*</span></strong>
                            {!! Form::text('current_job_title', null, array('id'=>'current_job_title','placeholder' => 'Last Job Title','class' => 'form-control', 'tabindex' => '15' )) !!}
                            @if ($errors->has('current_job_title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_job_title') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('functional_roles_id') ? 'has-error' : '' }}">
                            <strong>Functional Roles: <span class = "required_fields">*</span></strong>
                            {!! Form::select('functional_roles_id', $functionalRoles,null, array('id'=>'functional_roles_id','class' => 'form-control', 'tabindex' => '17' )) !!}
                            @if ($errors->has('functional_roles_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('functional_roles_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('specialization') ? 'has-error' : '' }}">
                            <strong>Specialization: </strong>
                            {!! Form::select('specialization',array('' => '--- Select Specialization ---'),null, array ('id'=> 'specialization','class' => 'form-control', 'tabindex' => '19')) !!}

                            @if ($errors->has('specialization'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('specialization') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 form-group no-padding exp-years{{ $errors->has('current_salary') ? 'has-error' : '' }}">
                            <strong>Current Salary: (Per Anum.) &nbsp;<span class = "required_fields">*</span></strong>
                            {!! Form::number('current_salary', null, array('id'=>'current_salary','placeholder' => 'Current Salary','class' => 'form-control', 'tabindex' => '22','onchange' => 'currentSalaryValidation()')) !!}
                            @if ($errors->has('current_salary'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_salary') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 form-group no-padding exp-month{{ $errors->has('expected_salary') ? 'has-error' : '' }}">
                            <strong>Expected Salary: (Per Anum.)</strong>
                            {!! Form::number('expected_salary', null, array('id'=>'expected_salary','placeholder' => 'Expected Salary','class' => 'form-control', 'tabindex' => '23','onchange' => 'expectedSalaryValidation()')) !!}
                            @if ($errors->has('expected_salary'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('expected_salary') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            @if( $action == 'add')
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title">Attachment Information</h3>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12"><br/>
                        <div class="form-group {{ $errors->has('resume') ? 'has-error' : '' }}">
                            <strong>Resume:</strong>
                            {!! Form::file('resume', null, array('id'=>'resume','class' => 'form-control')) !!}
                            @if ($errors->has('resume'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('resume') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="margin-bottom: 20px;">
                {!! Form::submit(isset($candidate) ? 'Update' : 'SUBMIT', ['class' => 'btn btn-primary','style="font-size:18px;"' ]) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('adminlte_js')

    <script src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>

        jQuery(document).ready(function () {

            getNotifications();
            var interval = 1000 * 60 * 1;
            setInterval(function(){getNotifications();},interval)

            $("#functional_roles_id").select2();
            $("#educational_qualification_id").select2();
            $("#specialization").select2();

            // automaticaly open the select2 when it gets focus

            jQuery(document).on('focus', '.select2', function() {
                jQuery(this).siblings('select').select2('open');
            });
        });

        function getNotifications() {

            jQuery.ajax({

                url:'/notifications/all',
                dataType:'json',

                success: function(data) {

                    $(".notification-ul").empty();

                    for (var i=0; i<data.length; i++) {

                        var li = '';

                        li += '<li class="notification-li">';
                        li += '<a href="'+data[i].link+'" target="_blank">';
                        li += '<h4>';
                        li += data[i].module;
                        li += '</h4>';
                        li += '<p>'+data[i].msg+'</p>';
                        li += '</a>';
                        li += '</li>';

                        $(".notification-ul").append(li);
                    }

                    $(".notification-number").html(data.length);
                    $(".notification-display").html("You have "+data.length+" new notifications");
                }
            });
        }
    </script>

    @stack('js')
    @yield('js')
@stop

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
                    },
                    "current_employer": {
                        required: true
                    },
                    "current_salary": {
                        required: true
                    },
                    "current_job_title": {
                        required: true
                    },
                    "functional_roles_id": {
                        required: true
                    },
                    "educational_qualification_id": {
                        required: true
                    },
                },

                messages: {
                    "fname": {
                        required: "Name is Required."
                    },
                    "mobile": {
                        required: "Mobile Number is Required."
                    },
                    "email": {
                        required: "Email is Required."
                    },
                    "current_employer": {
                        required: "Last Employer is Required."
                    },
                    "current_salary": {
                        required: "Current Salary is Required."
                    },
                    "current_job_title": {
                        required: " Last Job Title is Required."
                    },
                    "functional_roles_id": {
                        required: "Please Select Functional Role."
                    },
                    "educational_qualification_id": {
                        required: "Please Select Education Qualification."
                    },
                }
            });
        });

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

        function getSpecialization() {

            var educational_qualification_id = $("#educational_qualification_id").val();

            if(educational_qualification_id>0) {

                $.ajax( {

                    url:'/specialization/getspecializationbyid',
                    data:{educational_qualification_id:educational_qualification_id},
                    dataType:'json',

                    success: function(data) {

                        if(data) {

                            $("#specialization").empty();
                            $("#specialization").append('<option value=""> --- Select Specialization --- </option>');

                            $.each(data,function(key, value) {

                              $('select[id="specialization"]').append('<option value="'+ value.specalization_id +'">' + value.specalization_nm + '</option>');
                            });

                            $("#specialization").focus();
                        }
                        else {

                            $("#specialization").empty();
                        }
                    }
                });
            }
            else {

                $("#specialization").empty();
            }
        }

        function currentSalaryValidation() {

            var current_salary = $("#current_salary").val();

            if(current_salary < 100000) {

                alert("Enter Salary Between 1,00,000 to 1,00,00,000");

                $("#current_salary").val(" ");
                $("#current_salary").focus();
                return false;
            }

            if(current_salary > 10000000) {

                alert("Enter Salary Between 1,00,000 to 1,00,00,000");

                $("#current_salary").val(" ");
                $("#current_salary").focus();
                return false;
            }
        }

        function expectedSalaryValidation() {

            var expected_salary = $("#expected_salary").val();

            if(expected_salary < 100000) {

                alert("Enter Salary Between 1,00,000 to 1,00,00,000");

                $("#expected_salary").val(" ");
                $("#expected_salary").focus();
                return false;
            }

            if(expected_salary > 10000000) {

                alert("Enter Salary Between 1,00,000 to 1,00,00,000");

                $("#expected_salary").val(" ");
                $("#expected_salary").focus();
                return false;
            }
        }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete" async defer></script>
@endsection