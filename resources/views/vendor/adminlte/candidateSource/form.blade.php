<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Candidate Source</h2>
            @else
                <h2>Create Candidate Source</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('candidateSource.index') }}"> Back</a>
        </div>
    </div>
</div>


@if($action == 'edit')
    {!! Form::model($candidateSource,['method' => 'PUT', 'files' => true, 'route' => ['candidateSource.update', $candidateSource['id']],'class'=>'form-horizontal','id'=>'candidate_source_form', 'novalidate'=>'novalidate']) !!}
    {!! Form::hidden('candidateSourceId', $candidateSource['id'], array('id'=>'candidateSourceId')) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'candidateSource.store','class'=>'form-horizontal','id'=>'candidate_source_form', 'novalidate'=>'novalidate']) !!}
@endif

{!! Form::hidden('action', $action, array('id'=>'action')) !!}


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <strong>Name: <span class = "required_fields">*</span></strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'tabindex' => '1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </div>
    </div>

@section('script')
    <script>
        $(document).ready(function() {

            $("#candidate_source_form").validate({
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