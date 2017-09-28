<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Bill</h2>
            @else
                <h2>Create New Bill</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('client.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($client,['method' => 'PATCH','files' => true, 'route' => ['client.update', $client->id]] ) !!}
@else
    {!! Form::open(array('route' => 'bills.store','files' => true,'method'=>'POST')) !!}

@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="">
                            <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
                                <strong>Company Name:</strong>
                                {!! Form::text('company_name', null, array('id'=>'company_name','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('company_name'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_id') ? 'has-error' : '' }}">
                                <strong>Candidate:</strong>
                                {!! Form::select('candidate_id', $candidate,null, array('id'=>'candidate_id','class' => 'form-control', 'tabindex' => '3','onchange'=>'autofill_candidateinfo()' )) !!}
                                @if ($errors->has('candidate_id'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate_id') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_mobile') ? 'has-error' : '' }}">
                                <strong>Candidate Mobile:</strong>
                                {!! Form::text('candidate_mobile', null, array('id'=>'candidate_mobile','placeholder' => 'Candidate Mobile','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('candidate_mobile'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate_mobile') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('candidate_phone') ? 'has-error' : '' }}">
                                <strong>Candidate Phone:</strong>
                                {!! Form::text('candidate_phone', null, array('id'=>'candidate_phone','placeholder' => 'Candidate Phone','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('candidate_phone'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate_phone') }}</strong>
                            </span>
                                @endif
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {


                }
        );

        function autofill_candidateinfo() {
            var candidate_id = jQuery("#candidate_id > option:selected").val();

            if (candidate_id > 0) {
                var token = $("#token").val();
                jQuery.ajax({
                    type: "GET",
                    url: "/candidateinfo/" + candidate_id,
                    dataType: "json"
                }).done(function (response) {
                    if (response.returnvalue == 'valid') {
                        jQuery("#candidate_mobile").val(response.mobile);

                    }
                });
            }

        }
    </script>
@endsection
