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
                <h2>Edit Job Openings</h2>
            @else
                <h2>Create Job Openings</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('jobopen.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($job_open,['method' => 'PATCH','files' => true, 'id' => 'jobsForm', 'route' => ['jobopen.update', $job_open->id]] ) !!}
@else
    {!! Form::open(array('route' => 'jobopen.store','files' => true,'method'=>'POST', 'id' => 'jobsForm')) !!}

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

                        <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                            <strong>Posting Title: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control' )) !!}
                            @if ($errors->has('posting_title'))
                                <span class="help-block">
                                <strong>{{ $errors->first('posting_title') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('hiring_manager_id') ? 'has-error' : '' }}">
                            <strong>Select Hiring Manager:</strong>
                            {!! Form::select('hiring_manager_id', $users,null, array('id'=>'hiring_manager_id','class' => 'form-control')) !!}
                            @if ($errors->has('hiring_manager_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('hiring_manager_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('target_date') ? 'has-error' : '' }}">
                            <strong>Target Date:</strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('target_date', isset($target_date) ? $target_date : null, array('id'=>'target_date','placeholder' => 'Target Date','class' => 'form-control' )) !!}
                            </div>
                            @if ($errors->has('target_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('target_date') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_opening_status') ? 'has-error' : '' }}">
                            <strong>Select Job Opening Status :</strong>
                            {!! Form::select('job_opening_status', $job_open_status,null, array('id'=>'job_opening_status','class' => 'form-control')) !!}
                            @if ($errors->has('job_opening_status'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_opening_status') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('industry_id') ? 'has-error' : '' }}">
                            <strong>Select Industry:  <span class = "required_fields">*</span>  </strong>
                            {!! Form::select('industry_id', $industry,null, array('id'=>'industry_id','class' => 'form-control')) !!}
                            @if ($errors->has('industry_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('industry_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_priority') ? 'has-error' : '' }}">
                            <strong>Select Job Priority :</strong>
                            {!! Form::select('job_priority', $job_priorities,(isset($job_open->priority) ? $job_open->priority : null), array('id'=>'job_priority','class' => 'form-control')) !!}
                            @if ($errors->has('job_priority'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_priority') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                        <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                            <strong>Select Client: <span class = "required_fields">*</span></strong>
                            {!! Form::select('client_id', $client,null, array('id'=>'client_id','class' => 'form-control')) !!}
                            @if ($errors->has('client_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('client_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('no_of_positions') ? 'has-error' : '' }}">
                            <strong>Number of Positions:</strong>
                            {!! Form::text('no_of_positions', 1, array('id'=>'no_of_positions','placeholder' => 'Posting Title','class' => 'form-control' )) !!}
                            @if ($errors->has('no_of_positions'))
                                <span class="help-block">
                                <strong>{{ $errors->first('no_of_positions') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('date_opened') ? 'has-error' : '' }}">
                            <strong>Date Opened: <span class = "required_fields">*</span> </strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('date_opened', isset($date_opened) ? $date_opened : null, array('id'=>'date_opened','placeholder' => 'Date Opened','class' => 'form-control' )) !!}
                            </div>
                            @if ($errors->has('date_opened'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date_opened') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_type') ? 'has-error' : '' }}">
                            <strong>Select Job Type :</strong>
                            {!! Form::select('job_type', $job_type,null, array('id'=>'job_type','class' => 'form-control')) !!}
                            @if ($errors->has('job_type'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_type') }}</strong>
                                </span>
                            @endif
                        </div>

                        {{--<div class="form-group {{ $errors->has('job_type') ? 'has-error' : '' }}">
                            <strong>View To All :</strong>
                            {{ Form::radio('job_show', 1, false, ['class' => 'field']) }}
                            <strong>Within Team :</strong>
                            {{ Form::radio('job_show', 0, true, ['class' => 'field']) }}
                        </div>--}}

                    <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                        <strong>Select Users who can access the job <span class = "required_fields">*</span></strong>
                        @if( $action == 'edit')
                            {!! Form::select('user_ids[]', $users,$team_mates, array('id'=>'user_ids','class' => 'form-control', 'multiple')) !!}
                        @else
                            {!! Form::select('user_ids[]', $users,$team_mates, array('id'=>'user_ids','class' => 'form-control', 'multiple')) !!}
                        @endif

                        @if ($errors->has('user_ids'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_ids') }}</strong>
                            </span>
                        @endif
                    </div>

                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group {{ $errors->has('job_description') ? 'has-error' : '' }}">
                            <strong>Job Description:</strong>
                            {!! Form::textarea('job_description', null, array('id'=>'job_description','placeholder' => 'Job description','class' => 'form-control' )) !!}
                            @if ($errors->has('no_of_positions'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('job_description') }}</strong>
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
                <h3 class="box-title">Additional Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('work_experience_from') ? 'has-error' : '' }}">
                            <strong>Work Experience from :</strong>
                            {!! Form::text('work_experience_from', null, array('id'=>'work_experience_from','placeholder' => 'Work Experience from','class' => 'form-control' )) !!}
                            @if ($errors->has('work_experience_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('work_experience_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('work_experience_to') ? 'has-error' : '' }}">
                            <strong>Work Experience to :</strong>
                            {!! Form::text('work_experience_to', null, array('id'=>'work_experience_to','placeholder' => 'Work Experience to','class' => 'form-control' )) !!}
                            @if ($errors->has('work_experience_to'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('work_experience_to') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('salary_from') ? 'has-error' : '' }}">
                            <strong>Salary from :</strong>
                            {!! Form::text('salary_from', null, array('id'=>'salary_from','placeholder' => 'Salary From','class' => 'form-control' )) !!}
                            @if ($errors->has('salary_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('salary_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('salary_to') ? 'has-error' : '' }}">
                            <strong>Salary to :</strong>
                            {!! Form::text('salary_to', null, array('id'=>'salary_to','placeholder' => 'Salary To','class' => 'form-control' )) !!}
                            @if ($errors->has('salary_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('salary_to') }}</strong>
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
                <h3 class="box-title">Job Location</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group">
                        <strong>Enter your location:</strong>
                        {!! Form::text('job_location', null, array('id'=>'job_location','placeholder' => 'Enter your location','class' => 'form-control' , 'onFocus'=>"geolocate()")) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>City:</strong>
                            {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <strong>Country:</strong>
                        {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country','class' => 'form-control')) !!}
                    </div>

                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group">
                            <strong>State:</strong>
                            {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State','class' => 'form-control')) !!}
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
                        <strong>Job Summary:</strong>
                        {!! Form::file('job_summary', null, array('id'=>'job_summary','class' => 'form-control')) !!}
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
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

</div>

{!! Form::close() !!}

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#jobsForm').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $("#jobsForm").validate({
                rules: {
                    "posting_title": {
                        required: true
                    },
                    "client_id": {
                        required: true
                    },
                    "date_opened": {
                        required: true
                    },
                    "industry_id": {
                        required: true
                    },
                },
                messages: {
                    "posting_title": {
                        required: "Posting Title is required field."
                    },
                    "client_id": {
                        required: "Client is required field."
                    },
                    "date_opened": {
                        required: "Date Open is required field."
                    },
                    "industry_id": {
                        required: "Industry is required field."
                    }
                }
            });

        });

        $(function () {
            $("#target_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $("#date_opened").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $("#hiring_manager_id").select2();
            $("#client_id").select2();
            $("#job_description").wysihtml5();
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
                    (document.getElementById('job_location')),
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