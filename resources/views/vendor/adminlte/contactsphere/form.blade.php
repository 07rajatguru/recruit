@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                @if($generate_contact==1)
                    <h2>Please confirm the details and update contact</h2>
                @elseif($action == 'edit')
                    <h2>Edit Contact</h2>
                @else
                    <h2>Add New Contact</h2>
                @endif
            </div>
            <div class="pull-right">
                @if(isset($hold_contact) && $hold_contact == 1)
                    <a class="btn btn-primary" href="{{ route('contactsphere.hold') }}">Back</a>
                @elseif(isset($forbid_contact) && $forbid_contact == 1)
                    <a class="btn btn-primary" href="{{ route('contactsphere.forbid') }}">Back</a>
                @else
                    <a class="btn btn-primary" href="{{ route('contactsphere.index') }}">Back</a>
                @endif
            </div>
        </div>
    </div>

    @if($action == 'edit')
        {!! Form::model($contact,['method' => 'PUT', 'files' => true, 'route' => ['contactsphere.update', $contact['id']],'id'=>'contact_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}
        {!! Form::hidden('contactId', $contact['id'], array('id'=>'contactId')) !!}

    @elseif($action == 'copy')
        {!! Form::model($contact,['method' => 'POST', 'files' => true, 'route' => ['contactsphere.clonestore'],'id'=>'contact_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}

    @else
        {!! Form::open(['files' => true, 'route' => 'contactsphere.store', 'id'=>'contact_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}
    @endif

     {!! Form::hidden('action', $action, array('id'=>'action')) !!}

    <input type="hidden" id="generate_contact" name="generate_contact" value="{{$generate_contact}}">

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Name : <span class = "required_fields">*</span></strong>
                            {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Name','class' => 'form-control','tabindex' => '1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                
                        <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                            <strong>Company : <span class = "required_fields">*</span></strong>
                            {!! Form::text('company', null, array('id'=>'company','placeholder' => 'Company','class' => 'form-control','tabindex' => '3')) !!}
                            @if ($errors->has('company'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                            @endif
                        </div>

                       <div class="form-group {{ $errors->has('official_email_id') ? 'has-error' : '' }}">
                            <strong>Official Email Id : <span class = "required_fields">*</span></strong>
                            {!! Form::email('official_email_id', null, array('id'=>'official_email_id','placeholder' => 'Official Email Id','class' => 'form-control','tabindex' => '5')) !!}
                            @if ($errors->has('official_email_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('official_email_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Self Remarks : </strong>
                            {!! Form::textarea('self_remarks', null, array('id'=>'self_remarks','placeholder' => 'Self Remarks','class' => 'form-control','tabindex' => '7','rows' => '8')) !!}
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                            <strong>Designation : <span class = "required_fields">*</span></strong>
                            {!! Form::text('designation', null, array('id'=>'designation','placeholder' => 'Designation','class' => 'form-control','tabindex' => '2')) !!}
                            @if ($errors->has('designation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('designation') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('contact_number') ? 'has-error' : '' }}">
                            <strong>Contact Number : <span class = "required_fields">*</span></strong>
                            {!! Form::number('contact_number', null, array('id'=>'contact_number','placeholder' => 'Contact Number','class' => 'form-control','tabindex' => '4')) !!}
                            @if ($errors->has('contact_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contact_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('personal_id') ? 'has-error' : '' }}">
                            <strong>Personal Id : <span class = "required_fields">*</span></strong>
                            {!! Form::email('personal_id', null, array('id'=>'personal_id','placeholder' => 'Personal Id','class' => 'form-control','tabindex' => '6')) !!}
                            @if ($errors->has('personal_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('personal_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Source : </strong>
                            {!! Form::text('source', null, array('id'=>'source','placeholder' => 'Source','class' => 'form-control','tabindex' => '8')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Linkedin Profile Link : </strong>
                            {!! Form::text('linkedin_profile_link', null, array('id'=>'linkedin_profile_link','placeholder' => 'Linkedin Profile Link','class' => 'form-control','tabindex' => '9')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Referred By :</strong>
                            {!! Form::select('referred_by',$users, $referredby_id, array('id'=>'referred_by','class' => 'form-control','tabindex' => '10')) !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Address : </strong>
                            {!! Form::text('address', null, array('id'=>'address','placeholder' => 'Search Address','class' => 'form-control', 'onFocus'=>"geolocate()",'tabindex' => '11')) !!}
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>City : </strong>
                                {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control','tabindex' => '12')) !!}
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>State :</strong>
                                {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State ','class' => 'form-control','tabindex' => '12')) !!}
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>Country : </strong>
                                {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country ','class' => 'form-control','tabindex' => '12')) !!}
                            </div>
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>

    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: center;">
        {!! Form::submit(isset($contact) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('customscripts')

<script type="text/javascript">

    $(document).ready(function() {

        $('#contact_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#contact_number').keypress(function (e) {

            var length = jQuery(this).val().length;

            if(e.which != 8 && e.which != 0 && e.which != 16 && e.which != 43 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            else if((length == 0) && (e.which == 48)) {
                return false;
            }
        });

        $("#contact_form").validate({

            rules: {
                "name": {
                    required: true
                },
               /* "designation": {
                    required: true
                },
                "company": {
                    required: true
                },
                "contact_number": {
                    required: true
                },
                "official_email_id": {
                    required: true
                },
                "personal_id": {
                    required: true
                },*/
            },
            messages: {
                "name": {
                    required: "Name is Required Field."
                },
                /*"designation": {
                    required: "Designation is Required Field."
                },
                "company": {
                    required: "Company Name is Required Field."
                },
                "contact_number": {
                    required: "Contact Number is Required Field."
                },
                "official_email_id": {
                    required: "Official Email Id Required Field."
                },
                "personal_id": {
                    required: "Personal Id is Required Field."
                },*/
            }
        });

        $("#referred_by").select2();
    });

    function emailValidation() {

        var official_email_id = $("#official_email_id").val();
        var personal_id = $("#personal_id").val();

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if(official_email_id != '') {
            if (reg.test(official_email_id) == false) {
                alert('Please Enter Valid Email Address');
                return false;
            }
        }

        if(personal_id != '') {
            if (reg.test(personal_id) == false) {
                alert('Please Enter Valid Secondary Email Address');
                return false;
            }
        }

        return true;
    }

    var placeSearch, autocomplete;
    var componentForm = {
        city: 'long_name',
        state: 'long_name',
        country: 'long_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */
            (document.getElementById('address')),
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
        try {

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (addressType == 'locality') {
                        document.getElementById('city').value = place.address_components[i]['long_name'];
                }
                if (addressType == 'country') {
                        document.getElementById('country').value = place.address_components[i]['long_name'];
                }
                if (addressType == 'administrative_area_level_1') {
                        document.getElementById('state').value = place.address_components[i]['long_name'];
                }
            }
        }
        catch (exception) {
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {

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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete" async defer>
</script>
@endsection