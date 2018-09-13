<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Daily Report</h2>
            @else
                <h2>Add Daily Report</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('dailyreport.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($dailyReport,['method' => 'PUT','files' => true, 'route' => ['dailyreport.update', $dailyReport->id]] ) !!}
@else
    {!! Form::open(array('route' => 'dailyreport.store','files' => true,'method'=>'POST')) !!}

@endif


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Report Date: <span class = "required_fields">*</span> </strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('report_date', null, array('id'=>'report_date','placeholder' => 'Select Report Date','class' => 'form-control', 'tabindex' => '1' )) !!}
                            </div>
                            @if ($errors->has('report_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('report_date') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('industry_id') ? 'has-error' : '' }}">
                            <strong>Client Name : <span class = "required_fields">*</span></strong>
                            {!! Form::select('client_id', $client,null, array('id'=>'client_id','class' => 'form-control','tabindex' => '3')) !!}
                            @if ($errors->has('client_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('client_id') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Number of CVs sent to TL / Supervisor</strong>
                            {!! Form::text('cvs_to_tl', null, array('id'=>'cvs_to_tl','placeholder' => 'Enter number of CVs sent to TL / Supervisor','class' => 'form-control','tabindex' => '5')) !!}
                            @if ($errors->has('cvs_to_tl'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cvs_to_tl') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('industry_id') ? 'has-error' : '' }}">
                            <strong>Candidate Status : <span class = "required_fields">*</span></strong>
                            {!! Form::select('candidate_status_id', $status,null, array('id'=>'candidate_status_id','class' => 'form-control','tabindex' => '7')) !!}
                            @if ($errors->has('candidate_status_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('candidate_status_id') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">

                        <div class="form-group {{ $errors->has('position_name') ? 'has-error' : '' }}">
                            <strong>Position Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('position_name', null, array('id'=>'name','placeholder' => 'Position Name','class' => 'form-control' ,'tabindex' => '2')) !!}
                            @if ($errors->has('position_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('position_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Location: <span class = "required_fields">*</span></strong>
                            {!! Form::text('location', null, array('id'=>'location','placeholder' => 'Enter Location','class' => 'form-control','tabindex' => '4')) !!}
                            @if ($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Number of Cvs reached to client after screening</strong>
                            {!! Form::text('cvs_to_client', null, array('id'=>'cvs_to_client','placeholder' => 'Enter number of Cvs reached to client after screening','class' => 'form-control','tabindex' => '6')) !!}
                            @if ($errors->has('cvs_to_tl'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cvs_to_tl') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>

                </div>
             </div>
         </div>
     </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>

{!! Form::close() !!}

@section('customscripts')
    <script>
        $(document).ready(function(){
            $("#report_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });


            $( "#client_id" ).select2();
            $( "#candidate_status_id" ).select2();
        });
    </script>
@endsection