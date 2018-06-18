<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Accounting</h2>
            @else
                <h2>Create New Accounting</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('accounting.index') }}"> Back</a>
        </div>

    </div>

</div>

@if( $action == 'edit')
    {!! Form::model($accounting,['method' => 'PATCH','files' => true, 'id' => 'team_form', 'route' => ['accounting.update', $accounting->id]] ) !!}
@else
    {!! Form::open(array('route' => 'accounting.store','files' => true,'method'=>'POST', 'id' => 'accounting_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <strong>Accounting Name: <span class = "required_fields">*</span></strong>
                        {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Accounting Name','class' => 'form-control','required' )) !!}
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

