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
                @elseif( $action == 'edit')
                    <h2>Edit Contact</h2>
                @else
                    <h2>Add New Contact</h2>
                @endif
            </div>
            <div class="pull-right">
                @if($hold_contact == 1)
                    <a class="btn btn-primary" href="{{ route('contactsphere.hold') }}">Back</a>
                @elseif($forbid_contact == 1)
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
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Secondary Email : </strong>
                                {!! Form::email('s_email', null, array('placeholder' => 'Secondary Email','class' => 'form-control','tabindex' => '4','id' => 's_email')) !!}
                            </div>
                        
                            <div class="form-group">
                                <strong>Other number : </strong>
                                {!! Form::text('other_number', null, array('id' => 'other_number','placeholder' => 'Other Number','class' => 'form-control','tabindex' => '6','minLength' => '10')) !!}
                            </div> 

                            <div class="form-group {{ $errors->has('designation') ? 'has-error' : '' }}">
                                <strong>Designation : </strong>
                                {!! Form::text('designation', null, array('id'=>'designation', 'placeholder' => 'Designation','class' => 'form-control','tabindex' => '13')) !!}
                            </div>
                        
                            <div class="form-group">
                                <strong>Referred By :</strong>
                                {!! Form::select('referredby_id',$users, $referredby_id, array('id'=>'referredby_id','class' => 'form-control','tabindex' => '14')) !!}
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
        {!! Form::submit(isset($contact) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('customscripts')

<script type="text/javascript">
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete" async defer>
</script>
@endsection