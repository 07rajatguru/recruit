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
    {!! Form::model($training,['method' => 'PATCH','files' => true, 'id' => 'training_form', 'route' => ['training.update', $training->id]] ) !!}
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
                        <strong>Title: <span class = "required_fields">*</span></strong>
                        {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Title','class' => 'form-control','required' )) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                                </span>
                        @endif
                    </div>
                   
                </div>
            </div>
            @if($action == "add")
                <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Upload Documents:</strong>
                            <input type="file" name="upload_documents[]" multiple class="form-control" />

                        </div>
                </div>
            @endif
            </div>

    @if($action == "edit")
     <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::training.upload', ['name' => 'trainingattachments' , 'data' => $training])
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Size</th>
                            
                        </tr>

                        @if(isset($trainingdetails['files']) && sizeof($trainingdetails['files']) > 0)
                            @foreach($trainingdetails['files'] as $key => $value)
                                <tr>
                                    <td>
                                        {{--<a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>--}}
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=>$training['id'], 'name' => 'trainingattachments' ,'display_name'=> 'Attachments'])
                                          </td>

                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
                                    <td>{{ $value['size'] }}</td>
                                   </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

            </div>
        </div>

    </div>
    @endif           
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
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

