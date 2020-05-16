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
                <h2>Edit Module Visibility</h2>
            @else
                <h2>Create New Module Visibility</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('modulevisible.index') }}"> Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($module_user, ['method' => 'PATCH','route' => ['modulevisible.update', $module_user->id], 'id' => 'module_visible_form']) !!}
@else
    {!! Form::open(array('route' => 'modulevisible.store','method'=>'POST', 'id' => 'module_visible_form')) !!}
@endif


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                            <strong>Users: <span class = "required_fields">*</span></strong>
                            {!! Form::select('user_id', $users, $userid, array('id' => 'user_id','class' => 'form-control','tabindex' => '1')) !!}
                            @if ($errors->has('user_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-body col-xs-12 col-sm-12 col-md-12">
                <div class="form-group {{ $errors->has('module_ids') ? 'has-error' : '' }}">
                    <strong>Select Modules : <span class = "required_fields">*</span></strong>
                    <input type="checkbox" id="all"/> <strong>Select All</strong><br/>
                    @foreach($modules as $k=>$v) &nbsp;&nbsp;
                    @if($v['status'] == '1')
                        {!! Form::checkbox('module_ids[]', $k, in_array($k,$selected_modules), array('id'=>'module_ids','size'=>'10','class' => 'module_ids', 'checked')) !!}
                        {!! Form::label ($v['name']) !!}
                    @else
                        {!! Form::checkbox('module_ids[]', $k, in_array($k,$selected_modules), array('id'=>'module_ids','size'=>'10','class' => 'module_ids')) !!}
                        {!! Form::label ($v['name']) !!}
                    @endif
                    @endforeach
                    @if ($errors->has('module_ids'))
                        <span class="help-block">
                            <strong>{{ $errors->first('module_ids') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit(isset($module_user) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

@section('customscripts')
    <script>
        $(document).ready(function(){
            
            $("#module_visible_form").validate({
                rules: {
                    "user_id": {
                        required: true
                    },
                    "module_ids[]": {
                        required: true
                    }
                },
                messages: {
                    "user_id": {
                        required: "User is required field."
                    },
                    "module_ids[]": {
                        required: "Module is required field."
                    }
                }
            });

            $('#user_id').select2();

            $("#all").click(function () {
                $('.module_ids').prop('checked', this.checked);
            });

            $(".module_ids").click(function () {
                $("#all").prop('checked', ($('.module_ids:checked').length == $('.module_ids').length) ? true : false);
            });

            $("#all").prop('checked', ($('.module_ids:checked').length == $('.module_ids').length) ? true : false);
        });
    </script>
@endsection