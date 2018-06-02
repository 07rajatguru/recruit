<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Training</h2>
            @else
                <h2>Create New Training</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('training.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($training,['method' => 'PATCH','files' => true, 'id' => 'team_form', 'route' => ['training.update', $training->id]] ) !!}
@else
    {!! Form::open(array('route' => 'training.store','files' => true,'method'=>'POST', 'id' => 'training_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <strong>Training Title: <span class = "required_fields">*</span></strong>
                        {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Trainig Title','class' => 'form-control','required' )) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                                </span>
                        @endif
                    </div>
                   
                </div>
            </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload Documents:</strong>
                            <input type="file" name="upload_documents[]" multiple class="form-control" />

                        </div>

                    </div>
           
                      <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </div>
</div>


@section('customscripts')
    <script>
        $(document).ready(function(){
            $( "#user_ids" ).select2();


            $("#team_form").validate({
                rules: {
                    "team_name": {
                        required: true
                    },
                    "user_ids": {
                        required: true
                    }
                },
                messages: {
                    "team_name": {
                        required: "Team Name is required field."
                    },
                    "user_ids": {
                        required: "Select Users is required field."
                    }
                }
            });
        });
    </script>
@endsection

