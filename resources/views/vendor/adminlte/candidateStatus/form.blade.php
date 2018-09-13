@section('style')
    <style>
        .error{
            color:#f56954;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Candidate Status</h2>
            @else
                <h2>Create Candidate Status</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('candidateStatus.index') }}"> Back</a>
        </div>

    </div>

</div>

@if(isset($action))
    @if($action == 'edit')
        {!! Form::model($candidateStatus,['method' => 'PUT', 'files' => true, 'route' => ['candidateStatus.update', $candidateStatus['id']],'class'=>'form-horizontal','id'=>'candidate_status_form', 'novalidate'=>'novalidate']) !!}
        {!! Form::hidden('candidateStatusId', $candidateStatus['id'], array('id'=>'candidateStatusId')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'candidateStatus.store','class'=>'form-horizontal','id'=>'candidate_status_form', 'novalidate'=>'novalidate']) !!}
    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">

                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Name: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required'=>'required', 'tabindex' => '1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2">&nbsp;</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($candidateStatus) ? 'Update' : 'Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
            </div>
        </div>

    </div>

    {!! Form::close() !!}
@else

    <div class="error-page">
        <h2 class="headline text-info"> 403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Whoops, looks like something went wrong.</h3>

        </div><!-- /.error-content -->
    </div>

@endif

@section('customscripts')
    <script>
        $(document).ready(function() {
            $("#candidate_status_form").validate({
                rules: {
                    "name": {
                        required: true
                    }
                },
                messages: {
                    "name": {
                        required: "Name is required."
                    }
                }
            });
        });
    </script>
@endsection