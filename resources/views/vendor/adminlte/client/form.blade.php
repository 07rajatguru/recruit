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
            @if($generate_lead==0)
                <h2>Please confirm the details and generate Client</h2>
            @elseif( $action == 'edit')
                <h2>Edit Client</h2>
            @else
                <h2>Create New Client</h2>
            @endif
        </div>
        <div class="pull-right">
            @if( $action == 'copy')
                <a class="btn btn-primary" href="{{ route('lead.index') }}"> Back</a>
            @else
                <a class="btn btn-primary" href="{{ route('client.index') }}"> Back</a>
            @endif
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($client,['method' => 'PATCH','files' => true, 'id' => 'clientForm','autocomplete' => 'off', 'route' => ['client.update', $client->id]] ) !!}
@elseif( $action == 'copy')
    {!! Form::model($lead,['method' => 'POST','files' => true, 'id' => 'leadForm','autocomplete' => 'off', 'route' => ['lead.clonestore', $lead->id]] ) !!}
@else
    {!! Form::open(array('route' => 'client.store','files' => true,'method'=>'POST', 'id' => 'clientForm','autocomplete' => 'off')) !!}
@endif

<input type="hidden" id="generatelead" name="generatelead" value="1">

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

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Display Name: </strong>
                            {!! Form::text('display_name', null, array('id'=>'display_name','placeholder' => 'Display Name','class' => 'form-control', 'tabindex' => '3'  )) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                            <strong>Mobile Number: <span class = "required_fields">*</span></strong>
                            {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' => 'Contact Number','class' => 'form-control', 'tabindex' => '5'  )) !!}
                            {!! Form::hidden('client_id', null, array('id'=>'client_id','placeholder' => 'Contact Number','class' => 'form-control' )) !!}
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('other_number') ? 'has-error' : '' }}">
                            <strong>Other Number:</strong>
                            {!! Form::text('other_number', null, array('id'=>'other_number','placeholder' => 'Other Number','class' => 'form-control', 'tabindex' => '7'  )) !!}
                            @if ($errors->has('other_number'))
                                <span class="help-block">
                                <strong>{{ $errors->first('other_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('account_manager_id') ? 'has-error' : '' }}">
                            <strong>Account Manager:</strong>
                            @if(isset($isSuperAdmin) && $isSuperAdmin==1)
                                {!! Form::select('account_manager_id', $users, $user_id, array('id'=>'account_manager_id','class' => 'form-control', 'tabindex' => '9' )) !!}
                            @else
                                {!! Form::select('account_manager_id', $users, $user_id, array('id'=>'account_manager_id','class' => 'form-control','disabled', 'tabindex' => '9' )) !!}
                            @endif
                            @if ($errors->has('account_manager_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('account_manager_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="account_manager" id="account_manager" value="{{$user_id}}" />

                        <div class="form-group {{ $errors->has('industry_id') ? 'has-error' : '' }}">
                            <strong>Industry <span class = "required_fields">*</span> </strong>
                            {!! Form::select('industry_id', $industry,$industry_id, array('id'=>'industry_id','class' => 'form-control', 'tabindex' => '11' )) !!}
                            @if ($errors->has('industry_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('industry_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group {{ $errors->has('source') ? 'has-error' : '' }}">
                            <strong>Source:</strong>
                            {!! Form::text('source', null, array('id'=>'source','placeholder' => 'Source','class' => 'form-control', 'tabindex' => '13' )) !!}
                            @if ($errors->has('source'))
                                <span class="help-block">
                                <strong>{{ $errors->first('source') }}</strong>
                                </span>
                            @endif
                        </div>
                        @endif

                        {{--<div class="form-group">
                            <strong>Fax</strong>
                            {!! Form::text('fax', null, array('id'=>'fax','placeholder' => 'Fax','class' => 'form-control')) !!}
                        </div>--}}
                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group">
                            <strong>GST Number</strong>
                            {!! Form::text('gst_no', null, array('id'=>'gst_no','placeholder' => 'GST Number','class' => 'form-control', 'tabindex' => '15' )) !!}
                        </div>
                        @endif
                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group">
                            <strong>TDS </strong>
                            {!! Form::text('tds', null, array('id'=>'tds','placeholder' => 'TDS','class' => 'form-control', 'tabindex' => '17' )) !!}
                        </div>
                        @endif

                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group">
                            <strong>Percentage charged below AM position </strong>
                            {!! Form::text('percentage_charged_below', null, array('id'=>'percentage_charged_below','placeholder' => 'Percentage charged below AM position','class' => 'form-control', 'tabindex' => '17' )) !!}
                        </div>
                        @endif

                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group">
                            <strong>Percentage charged above AM position </strong>
                            {!! Form::text('percentage_charged_above', null, array('id'=>'percentage_charged_above','placeholder' => 'Percentage charged above AM position','class' => 'form-control', 'tabindex' => '17' )) !!}
                        </div>
                        @endif

                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                        <div class="form-group {{ $errors->has('coordinator_name') ? 'has-error' : '' }}">
                            <strong>HR/Coordinator Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('coordinator_name', null, array('id'=>'coordinator_name','placeholder' => 'HR/Coordinator Name','class' => 'form-control', 'tabindex' => '2' )) !!}
                            @if ($errors->has('coordinator_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('coordinator_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('mail') ? 'has-error' : '' }}">
                            <strong>Email: <span class = "required_fields">*</span></strong>
                            {!! Form::text('mail', null, array('id'=>'mail','placeholder' => 'Email','class' => 'form-control', 'tabindex' => '4' )) !!}
                            @if ($errors->has('mail'))
                                <span class="help-block">
                                <strong>{{ $errors->first('mail') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('s_mail') ? 'has-error' : '' }}">
                            <strong>Secondary Email: </strong>
                            {!! Form::text('s_email', null, array('id'=>'s_email','placeholder' => 'Email','class' => 'form-control', 'tabindex' => '6' )) !!}
                            @if ($errors->has('s_email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('s_email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Website:</strong>
                            {!! Form::text('website', null, array('id'=>'website','placeholder' => 'Website','class' => 'form-control', 'tabindex' => '8' )) !!}
                        </div>
                        @if($isSuperAdmin || $isAdmin)
                        <div class="form-group">
                            <strong>TAN:</strong>
                            {!! Form::text('tan', null, array('id'=>'tan','placeholder' => 'TAN','class' => 'form-control', 'tabindex' => '10' )) !!}
                        </div>
                        @endif

                        <div class="form-group">
                            <strong>About:</strong>
                            {!! Form::textarea('description', null, array('id'=>'description','placeholder' => 'About','class' => 'form-control', 'tabindex' => '12' )) !!}
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
            {!! Form::hidden('client_address_id', null, array('id'=>'client_address_id','placeholder' => 'client_address_id','class' => 'form-control' )) !!}
            <div class="box-header with-border col-md-6">
                <button type="button" onclick="copyAddress('toshipping');" class="btn btn-primary">Address 1 to address 2</button>
                <button type="button" onclick="copyAddress('tobilling');" class="btn btn-primary">Address 2 to address 1</button>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>Enter your address 1:</strong>
                            {!! Form::text('client_address', null, array('id'=>'client_address','placeholder' => 'Enter your address 1','class' => 'form-control' , 'onFocus'=>"geolocate()", 'tabindex' => '18' )) !!}
                        </div>

                        <div class="form-group">
                            <strong>Street address:</strong>
                            {!! Form::text('billing_street1', null, array('id'=>'billing_street1','placeholder' => 'Address Line 1','class' => 'form-control', 'tabindex' => '19')) !!}
                            {!! Form::text('billing_street2', null, array('id'=>'billing_street2','placeholder' => 'Address Line 2','class' => 'form-control', 'tabindex' => '20')) !!}
                        </div>

                        <div class="form-group">
                            <strong>City:</strong>
                            @if( $action == 'copy')
                            {!! Form::text('billing_city', $billing_city, array('id'=>'billing_city','placeholder' => 'City','class' => 'form-control', 'tabindex' => '21')) !!}
                            @else
                            {!! Form::text('billing_city', null, array('id'=>'billing_city','placeholder' => 'City','class' => 'form-control', 'tabindex' => '21')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>State:</strong>
                            @if( $action == 'copy')
                            {!! Form::text('billing_state', $billing_state, array('id'=>'billing_state','placeholder' => 'State','class' => 'form-control', 'tabindex' => '22')) !!}
                            @else
                            {!! Form::text('billing_state', null, array('id'=>'billing_state','placeholder' => 'State','class' => 'form-control', 'tabindex' => '22')) !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Code:</strong>
                            {!! Form::text('billing_code', null, array('id'=>'billing_code','placeholder' => 'Code','class' => 'form-control', 'tabindex' => '23')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Country:</strong>
                            @if( $action == 'copy')
                            {!! Form::text('billing_country', $billing_country, array('id'=>'billing_country','placeholder' => 'Country','class' => 'form-control', 'tabindex' => '24')) !!}
                            @else
                            {!! Form::text('billing_country', null, array('id'=>'billing_country','placeholder' => 'Country','class' => 'form-control', 'tabindex' => '24')) !!}
                            @endif
                        </div>

                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>Enter your address 2:</strong>
                            {!! Form::text('shipping_address', null, array('id'=>'shipping_address','placeholder' => 'Enter your address 2','class' => 'form-control' , 'onFocus'=>"geolocate()")) !!}
                        </div>

                        <div class="form-group">
                            <strong>Street address:</strong>
                            {!! Form::text('shipping_street1', null, array('id'=>'shipping_street1','placeholder' => 'Address Line 1','class' => 'form-control')) !!}
                            {!! Form::text('shipping_street2', null, array('id'=>'shipping_street2','placeholder' => 'Address Line 2','class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                            <strong>City:</strong>
                            {!! Form::text('shipping_city', null, array('id'=>'shipping_city','placeholder' => 'City','class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                            <strong>State:</strong>
                            {!! Form::text('shipping_state', null, array('id'=>'shipping_state','placeholder' => 'State','class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Code:</strong>
                            {!! Form::text('shipping_code', null, array('id'=>'shipping_code','placeholder' => 'Code','class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                            <strong>Country:</strong>
                            {!! Form::text('shipping_country', null, array('id'=>'shipping_country','placeholder' => 'Country','class' => 'form-control')) !!}
                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    @if($isSuperAdmin || $isAdmin)
        @if( $action == 'add')
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Attachment Information</h3>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Client Contract:</strong>
                    {!! Form::file('client_contract', null, array('id'=>'client_contract','class' => 'form-control')) !!}
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Client Logo:</strong>
                    {!! Form::file('client_logo', null, array('id'=>'client_logo','class' => 'form-control')) !!}
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Others:</strong>
                    {!! Form::file('others_doc', null, array('id'=>'others_doc','class' => 'form-control')) !!}
                </div>

            </div>
    </div>
        @endif
    @endif
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>

{!! Form::close() !!}

@section('customscripts')
    <script>

        $(document).ready(function() {

            $('#account_manager_id').on('change', function (e) {
                $("#account_manager").val(this.value);
            });

            $('#clientForm').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $("#clientForm").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "mail": {
                        required: true
                    },
                    "mobile": {
                        required: true
                    },
                    "industry_id": {
                        required: true
                    },
                    /*"gst_no" : {
                        required: true
                    },
                    "tds" : {
                        required: true
                    },*/
                    "coordinator_name" : {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Name is required."
                    },
                    "mail": {
                        required: "Email is required."
                    },
                    "mobile": {
                        required: "Mobile is required."
                    },
                    "industry_id": {
                        required: "Industry is required."
                    },
                   /* "gst_no": {
                        required: "GST Number is required."
                    },
                    "tds": {
                        required: "TDS is required."
                    },*/
                    "coordinator_name" :{
                        required: "HR / Coordinator name is required."
                    }
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
            billing_street1: 'short_name',
            billing_street2: 'long_name',
            billing_city: 'long_name',
            billing_state: 'long_name',
            billing_country: 'long_name',
            billing_code: 'short_name',
            shipping_street1: 'short_name',
            shipping_street2: 'long_name',
            shipping_city: 'long_name',
            shipping_state: 'long_name',
            shipping_country: 'long_name',
            shipping_code: 'short_name'
        };
        $("#description").wysihtml5();
        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */
                    (document.getElementById('client_address')),
                    {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);

            shipping_autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */
                    (document.getElementById('shipping_address')),
                    {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            shipping_autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            var shipping_place = shipping_autocomplete.getPlace();
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
                        document.getElementById('billing_city').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='country'){
                        document.getElementById('billing_country').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='administrative_area_level_1'){
                        document.getElementById('billing_state').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='street_number'){
                        document.getElementById('billing_street1').value = place.address_components[i]['short_name'];
                    }
                    if(addressType=='route'){
                        document.getElementById('billing_street2').value = place.address_components[i]['long_name'];
                    }
                    if(addressType=='postal_code'){
                        document.getElementById('billing_code').value = place.address_components[i]['long_name'];
                    }
                }
            }
            catch (exception){

            }
            // popuplate shipping address
            try{
                for (var i = 0; i < shipping_place.address_components.length; i++) {
                    var addressType = shipping_place.address_components[i].types[0];
                    if(addressType=='locality'){
                        document.getElementById('shipping_city').value = shipping_place.address_components[i]['long_name'];
                    }
                    if(addressType=='country'){
                        document.getElementById('shipping_country').value = shipping_place.address_components[i]['long_name'];
                    }
                    if(addressType=='administrative_area_level_1'){
                        document.getElementById('shipping_state').value = shipping_place.address_components[i]['long_name'];
                    }
                    if(addressType=='street_number'){
                        document.getElementById('shipping_street1').value = shipping_place.address_components[i]['short_name'];
                    }
                    if(addressType=='route'){
                        document.getElementById('shipping_street2').value = shipping_place.address_components[i]['long_name'];
                    }
                    if(addressType=='postal_code'){
                        document.getElementById('shipping_code').value = shipping_place.address_components[i]['long_name'];
                    }
                }
            }catch (exception){

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

        function copyAddress(type){
            if(type=='toshipping'){
                var billing_street1 = document.getElementById('billing_street1').value;
                document.getElementById('shipping_street1').value = billing_street1;

                var billing_street2 = document.getElementById('billing_street2').value;
                document.getElementById('shipping_street2').value = billing_street2;

                var billing_city = document.getElementById('billing_city').value;
                document.getElementById('shipping_city').value = billing_city;

                var billing_state = document.getElementById('billing_state').value;
                document.getElementById('shipping_state').value = billing_state;

                var billing_code = document.getElementById('billing_code').value;
                document.getElementById('shipping_code').value = billing_code;

                var billing_country = document.getElementById('billing_country').value;
                document.getElementById('shipping_country').value = billing_country;

            }
            else if(type=='tobilling'){
                var shipping_street1 = document.getElementById('shipping_street1').value;
                document.getElementById('billing_street1').value = shipping_street1;

                var shipping_street2 = document.getElementById('shipping_street2').value;
                document.getElementById('billing_street2').value = shipping_street2;

                var shipping_city = document.getElementById('shipping_city').value;
                document.getElementById('billing_city').value = shipping_city;

                var shipping_state = document.getElementById('shipping_state').value;
                document.getElementById('billing_state').value = shipping_state;

                var shipping_code = document.getElementById('shipping_code').value;
                document.getElementById('billing_code').value = shipping_code;

                var shipping_country = document.getElementById('shipping_country').value;
                document.getElementById('billing_country').value = shipping_country;
            }
        }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete"
            async defer></script>

@endsection


