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
                <h2>Edit Training Material</h2>
            @else
                <h2>Add New Training Material</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('training.index') }}"> Back</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

@if( $action == 'edit')
    {!! Form::model($training,['method' => 'PATCH','files' => true, 'id' => 'training_form', 'route' => ['training.update', $training->id]] ) !!}
@else
    {!! Form::open(array('route' => 'training.store','files' => true,'method'=>'POST', 'id' => 'training_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <strong>Title : <span class = "required_fields">*</span></strong>
                        {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Title','class' => 'form-control','required','tabindex' => '1')) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                        <strong>Select Users who can see this Material : <span class = "required_fields">*</span></strong>
                        <input type="checkbox" id="users_all"/> <strong>Select All</strong><br/>
                        @foreach($users as $k=>$v)&nbsp;&nbsp; 
                        {!! Form::checkbox('user_ids[]', $k, in_array($k,$selected_users), array('id'=>'user_ids','size'=>'10','class' => 'users_ids')) !!}
                        {!! Form::label ($v) !!}
                        @endforeach

                        @if ($errors->has('user_ids'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_ids') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @if($action == "add")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Documents : <span class = "required_fields">*</span></strong>
                        <input type="file" name="upload_documents[]" id="upload_documents" multiple class="form-control" />
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
                                                {{--<a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>--}}&nbsp;
                                                @include('adminlte::partials.confirm', ['data' => $value,'id'=>$training['id'], 'name' => 'trainingattachments' ,'display_name'=> 'Attachments','type' => 'Edit'])
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
            {!! Form::submit(isset($training) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>


@section('customscripts')
    <script>
        $(document).ready(function() {

            $("#training_form").validate({
                rules: {
                    "title": {
                        required: true
                    },
                    "user_ids[]": {
                        required: true
                    },
                    "upload_documents[]": {
                        required: true
                    }
                },
                messages: {
                    "title": {
                        required: "Title is Required Field."
                    },
                    "user_ids[]": {
                        required: "Please Select Users."
                    },
                    "upload_documents[]": {
                        required: "Please Select File."
                    }
                }
            });

            $("#users_all").click(function () {
                
                $('.users_ids').prop('checked', this.checked);
            });

            $(".users_ids").click(function () {
                $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
            });

            // Edit form > if all user select then select all selected
            $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
        });
    </script>
@endsection

