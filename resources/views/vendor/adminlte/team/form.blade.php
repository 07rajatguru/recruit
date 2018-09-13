<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Team</h2>
            @else
                <h2>Create New Team</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('team.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($team,['method' => 'PATCH','files' => true, 'id' => 'team_form', 'route' => ['team.update', $team->id]] ) !!}
@else
    {!! Form::open(array('route' => 'team.store','files' => true,'method'=>'POST', 'id' => 'team_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('team_name') ? 'has-error' : '' }}">
                        <strong>Team Name: <span class = "required_fields">*</span></strong>
                        {!! Form::text('team_name', null, array('id'=>'name','placeholder' => 'Team Name','class' => 'form-control','tabindex' => '1')) !!}
                        @if ($errors->has('team_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('team_name') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                        <strong>Select Users <span class = "required_fields">*</span></strong>
                        @if( $action == 'edit')
                            {!! Form::select('user_ids[]', $users,$team_mates, array('id'=>'user_ids','class' => 'form-control', 'multiple')) !!}
                        @else
                            {!! Form::select('user_ids[]', $users,null, array('id'=>'user_ids','class' => 'form-control', 'multiple')) !!}
                        @endif

                        @if ($errors->has('user_ids'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_ids') }}</strong>
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

