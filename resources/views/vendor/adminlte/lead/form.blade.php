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
               <a class="btn btn-primary" href="{{ route('lead.index') }}"> Back</a>
            </div>
        </div>
    </div>
@if(isset($action))
    @if($action == 'edit')
        {!! Form::model($lead,['method' => 'PUT', 'files' => true, 'route' => ['lead.update', $lead['id']],'id'=>'lead_form', 'novalidate'=>'novalidate','autocomplete' => 'off']) !!}
        {!! Form::hidden('leadId', $lead['id'], array('id'=>'leadId')) !!}
    @elseif($action == 'copy')
        {!! Form::model($lead,['method' => 'POST', 'files' => true, 'route' => ['lead.clonestore'],'id'=>'lead_form','autocomplete' => 'off']) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'lead.store','id'=>'lead_form','autocomplete' => 'off', 'novalidate'=>'novalidate']) !!}
    @endif
     {!! Form::hidden('action', $action, array('id'=>'action')) !!}


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(array('route' => 'lead.store','method'=>'POST')) !!}

<input type="hidden" id="generatelead" name="generatelead" value="{{$generate_lead}}">

    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
             <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>Company Name:<span class = "required_fields">*</span></strong>
                                    {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Company Name','class' => 'form-control')) !!}
                                     @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Hr/coordinator name:<span class = "required_fields">*</span></strong>
                                    {!! Form::text('coordinator_name', null, array('id'=>'hr_name','placeholder' => 'Hr/coordinator Name','class' => 'form-control')) !!}
                           </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Email:<span class = "required_fields">*</span></strong>
                                   {!! Form::text('mail', null, array('id'=>'mail','placeholder' => 'E-mail','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Secondary Email:</strong>
                                     {!! Form::text('s_email', null, array('placeholder' => 'Secondary Email','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Mobile number:<span class = "required_fields">*</span></strong>
                                     {!! Form::text('mobile', null, array('id'=>'mobile','placeholder' => 'Mobile Number','class' => 'form-control')) !!}
                           </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Other number:</strong>
                                     {!! Form::text('other_number', null, array('placeholder' => 'Other Number','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Display Name:</strong>
                                     {!! Form::text('display_name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                            </div>
                        </div>
 @section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
 @endsection  

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Select Leads: :</strong>
                                    {!! Form::select('leads',$leadservices_status,$service, array('id'=>'leads','class' => 'form-control')) !!}
                                    @if ($errors->has('leads'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job_opening_status') }}</strong>
                                    </span>
                                    @endif
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Remarks:</strong>
                                     {!! Form::textarea('remarks', null, array('placeholder' => 'Remark','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Website:</strong>
                                    {!! Form::text('website', null, array('id'=>'website', 'placeholder' => 'Website','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Source:</strong>
                                    {!! Form::text('source', null, array('placeholder' => 'Source','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Designation:</strong>
                                    {!! Form::text('designation', null, array('placeholder' => 'Designation','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Referred By:</strong>
                                    {!! Form::select('referredby_id',$users, null, array('id'=>'referredby_id','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address</strong>
                                    {!! Form::text('address', null, array('id'=>'address','placeholder' => 'Search Address','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>City:</strong>
                                    {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control')) !!}
                            </div>
                        </div>
                        
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>State:</strong>
                                    {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State ','class' => 'form-control')) !!}
                            </div>
                        </div>
                        
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Country</strong>
                                    {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country ','class' => 'form-control')) !!}
                            </div>
                        </div>
                 </div>
             </div>
         </div>
     </div>

        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: center;">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
</div>
    {!! Form::close() !!}
    @endif

@endsection

@section('customscripts')
    <script type="text/javascript">

     $(document).ready(function() {

        

       $("#lead_form").validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "coordinator_name": {
                        required: true
                    },
                    "mail": {
                        required: true
                    },
                    "mobile": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Company Name is required."
                    },
                    "coordinator_name": {
                        required: "Hr/Coodinator Name is required."
                    },
                    "mail": {
                        required: "mail is required."
                    },
                    "mobile": {
                        required: "Mobile Number is required."
                    }
                }
            });
            $("#referredby_id").select2();
            });


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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection