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
                @if($generate_lead==1)
                    <h2>Please confirm the details and generate Lead</h2>
                @elseif( $action == 'edit')
                    <h2>Edit Lead</h2>
                @else
                    <h2>Create New Lead</h2>
                @endif
            </div>
            <div class="pull-right">
                @if($cancel_lead==1)
                    <a class="btn btn-primary" href="{{ route('lead.leadcancel') }}"> Back</a>
                @else
                    <a class="btn btn-primary" href="{{ route('lead.index') }}"> Back</a>
                @endif
            </div>
        </div>
    </div>

    @if($action == 'edit')
        {!! Form::model($lead,['method' => 'PUT', 'files' => true, 'route' => ['lead.update', $lead['id']],'id'=>'lead_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}
        {!! Form::hidden('leadId', $lead['id'], array('id'=>'leadId')) !!}

    @elseif($action == 'copy')
        {!! Form::model($lead,['method' => 'POST', 'files' => true, 'route' => ['lead.clonestore'],'id'=>'lead_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}

    @else
        {!! Form::open(['files' => true, 'route' => 'lead.store','id'=>'lead_form','autocomplete' => 'off','onsubmit' => "return emailValidation()"]) !!}
    @endif
     {!! Form::hidden('action', $action, array('id'=>'action')) !!}

    <input type="hidden" id="generatelead" name="generatelead" value="{{$generate_lead}}">

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Company Name : <span class = "required_fields">*</span></strong>
                            {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Company Name','class' => 'form-control','tabindex' => '1','minLength' => '5','onchange' => 'validCompanyNameText();')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                
                        <div class="form-group">
                            <strong>Email : <span class = "required_fields">*</span></strong>
                            {!! Form::email('mail', null, array('id'=>'mail','placeholder' => 'E-mail','class' => 'form-control','tabindex' => '3')) !!}
                        </div>
                       
                        <div class="form-group">
                            <strong>Mobile : <span class = "required_fields">*</span></strong>
                            {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' => 'Mobile','class' => 'form-control','tabindex' => '5','minLength' => '10')) !!}
                        </div>

                        <div class="form-group {{ $errors->has('display_name') ? 'has-error' : '' }}">
                            <strong>Display Name : <span class = "required_fields">*</span></strong>
                            {!! Form::text('display_name', null, array('id'=>'display_name','placeholder' => 'Display Name','class' => 'form-control','tabindex' => '7','minLength' => '3','maxLength' => '10','onchange' => 'validDisplayNameText();')) !!}
                            @if ($errors->has('display_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('display_name') }}</strong>
                                </span>
                            @endif
                        </div>
                
                        <div class="form-group">
                            <strong>Status : </strong>
                            {!! Form::select('status',$status, $lead_status, array('id'=>'status','class' => 'form-control','tabindex' => '9')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Remarks : </strong>
                            {!! Form::textarea('remarks', null, array('id'=>'remarks','placeholder' => 'Remarks','class' => 'form-control','tabindex' => '11','rows' => '8')) !!}
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <strong>Contact Point : <span class = "required_fields">*</span></strong>
                        <div class="">
                           <div class="col-md-4 form-group" style="margin-left: -15px;">
                                {!! Form::select('co_category', $co_prefix, $co_category, array('id'=>'co_category','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('co_category'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('co_category') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-8 form-group {{ $errors->has('contact_point') ? 'has-error' : '' }}" style="margin-left: -15px;">
                                {!! Form::text('contact_point', null, array('id'=>'contact_point','placeholder' => 'Contact Point','class' => 'form-control','tabindex' => '2','minLength' => '3','onchange' => 'validContactPointText();')) !!}
                                @if ($errors->has('contact_point'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_point') }}</strong>
                                    </span>
                                @endif
                            </div>
                    
                            <div class="form-group">
                                <strong>Secondary Email : </strong>
                                {!! Form::email('s_email', null, array('placeholder' => 'Secondary Email','class' => 'form-control','tabindex' => '4','id' => 's_email')) !!}
                            </div>
                        
                            <div class="form-group">
                                <strong>Other number : </strong>
                                {!! Form::text('other_number', null, array('id' => 'other_number','placeholder' => 'Other Number','class' => 'form-control','tabindex' => '6','minLength' => '10')) !!}
                            </div> 

                            <div class="form-group">
                                <strong>Select Leads : </strong>
                                {!! Form::select('leads',$leadservices_status,$service, array('id'=>'leads','class' => 'form-control','tabindex' => '8')) !!}
                                @if ($errors->has('leads'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job_opening_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                       
                            <div class="form-group">
                                <strong>Website : </strong>
                                {!! Form::text('website', null, array('id'=>'website', 'placeholder' => 'Website','class' => 'form-control','tabindex' => '10')) !!}
                            </div>
                       
                            <div class="form-group">
                                <strong>Source : </strong>
                                {!! Form::text('source', null, array('placeholder' => 'Source','class' => 'form-control','tabindex' => '12')) !!}
                            </div>

                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation : </strong>
                                {!! Form::text('designation', null, array('id'=>'designation', 'placeholder' => 'Designation','class' => 'form-control','tabindex' => '13')) !!}
                            </div>
                        
                            <div class="form-group">
                                <strong>Referred By :</strong>
                                {!! Form::select('referredby_id',$users, $referredby, array('id'=>'referredby_id','class' => 'form-control','tabindex' => '14')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Address : </strong>
                            {!! Form::text('address', null, array('id'=>'address','placeholder' => 'Search Address','class' => 'form-control', 'onFocus'=>"geolocate()",'tabindex' => '15')) !!}
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>City : <span class = "required_fields">*</span></strong>
                                {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control','tabindex' => '16')) !!}
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>State :</strong>
                                {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State ','class' => 'form-control','tabindex' => '17')) !!}
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <strong>Country : </strong>
                                {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country ','class' => 'form-control','tabindex' => '18')) !!}
                            </div>
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>

    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: center;">
        {!! Form::submit(isset($lead) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('customscripts')

<script type="text/javascript">

    $(document).ready(function() {

        $('#lead_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#mobile').keypress(function (e) {

            var length = jQuery(this).val().length;

            if(e.which != 8 && e.which != 0 && e.which != 16 && e.which != 43 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            else if((length == 0) && (e.which == 48)) {
                return false;
            }
        });

        $('#other_number').keypress(function (e) {

            var length = jQuery(this).val().length;

            if(e.which != 8 && e.which != 0 && e.which != 16 && e.which != 43 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            else if((length == 0) && (e.which == 48)) {
                return false;
            }
        });

        $("#lead_form").validate({

            rules: {
                "name": {
                    required: true
                },
                "contact_point": {
                    required: true
                },
                "mail": {
                    required: true
                },
                "mobile": {
                    required: true
                },
                "city": {
                    required: true
                },
                "display_name": {
                    required: true
                }
            },
            messages: {
                "name": {
                    required: "Company Name is Required Field."
                },
                "contact_point": {
                    required: "Contact Point is Required Field."
                },
                "mail": {
                    required: "Email is Required Field."
                },
                "mobile": {
                    required: "Mobile Number is Required Field."
                },
                "city": {
                    required: "City is Required Field."
                },
                "display_name": {
                    required: "Display Name is Required Field."
                }
            }
        });

        $("#referredby_id").select2();
    });

    function validCompanyNameText() {

        var txt = document.getElementById("name").value ;
        var CompanyNameLength = txt.trim().length;

        if(CompanyNameLength < 1) {

            alert("Blank Entry Not Allowed.")
            document.getElementById("name").value = '';
            document.getElementById("name").focus();
        }
    }

    function validContactPointText() {

        var txt = document.getElementById("contact_point").value ;
        var ContactPointLength = txt.trim().length;

        if(ContactPointLength < 1) {

            alert("Blank Entry Not Allowed.")
            document.getElementById("contact_point").value = '';
            document.getElementById("contact_point").focus();
        }
    }

    function validDisplayNameText() {

        var txt = document.getElementById("display_name").value ;
        var DisplayNameLength = txt.trim().length;

        if(DisplayNameLength < 1) {

            alert("Blank Entry Not Allowed.")
            document.getElementById("display_name").value = '';
            document.getElementById("display_name").focus();
        }
    }

    function emailValidation() {

        var email_value = $("#mail").val();
        var s_email_value = $("#s_email").val();
        var website = $("#website").val();

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        var website_regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

        if(email_value != '') {
            if (reg.test(email_value) == false) {
                alert('Please Enter Valid Email Address');
                return false;
            }
        }

        if(s_email_value != '') {
            if (reg.test(s_email_value) == false) {
                alert('Please Enter Valid Secondary Email Address');
                return false;
            }
        }

        if(website != '') {
            if (website_regexp.test(website) == false) {
                alert('Please Enter Valid Website URL');
                document.getElementById("website").focus();
                return false;
            }
        }
        return true;
    }

    function is_url(str) {

        regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

        if (regexp.test(str)) {
            return true;
        }
        else {
            return false;
        }
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