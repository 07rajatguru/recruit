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
                <h2>Edit Holiday</h2>
            @else
                <h2>Create New Holiday</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>

    </div>
</div>

@if(isset($action))

    @if($action == 'edit')
        {!! Form::model($holidays,['method' => 'PATCH','route' => ['holidays.update',$holidays['id']],'id' => 'holiday_form']) !!}
    @else
        {!! Form::open(['route' => 'holidays.store','id' => 'holiday_form']) !!}
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <strong>Title: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('title',null, array('id'=>'title','class' => 'form-control','tabindex' => '1' )) !!}
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group {{ $errors->has('from_date') ? 'has-error' : '' }}">
                                <strong>From Date: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('from_date',null, array('id'=>'from_date','class' => 'form-control','tabindex' => '3' )) !!}
                                @if ($errors->has('from_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('from_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                <strong>Remarks: </strong>
                                {!! Form::textarea('remarks',null, array('id'=>'remarks','class' => 'form-control','tabindex' => '5', 'rows' => '5' )) !!}
                                @if ($errors->has('remarks'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('remarks') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong>Type: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('type',$type,null, array('id'=>'type','class' => 'form-control','tabindex' => '2' )) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <div class="form-group {{ $errors->has('to_date') ? 'has-error' : '' }}">
                                <strong>To Date: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('to_date',null, array('id'=>'to_date','class' => 'form-control','tabindex' => '4' )) !!}
                                @if ($errors->has('to_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('to_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                            <strong>Select Users : <span class = "required_fields">*</span></strong>
                            <input type="checkbox" id="users_all"/> <strong>Select All</strong><br/>
                            @foreach($users as $k=>$v) &nbsp;&nbsp;
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
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($holidays) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>

@endif

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#from_date").datetimepicker({
                format: "DD-MM-YYYY HH:mm:ss",
            });

            $("#to_date").datetimepicker({
                format: "DD-MM-YYYY HH:mm:ss"
            });
            $("#users_all").click(function () {
                $('.users_ids').prop('checked', this.checked);
            });

            $(".users_ids").click(function () {
                $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
            });

            $("#holiday_form").validate({
                rules: {
                    "title" : {
                        required: true,
                    },
                    "type" : {
                        required: true,
                    },
                    "from_date" : {
                        required: true,
                    },
                    "to_date" : {
                        required: true,
                    },
                    "user_ids" : {
                        required: true,
                    },
                },
                messages: {
                    "title": {
                        required: "Title is required field.",
                    },
                    "type" : {
                        required: "Type is required field.",
                    },
                    "from_date" : {
                        required: "From Date is required field.",
                    },
                    "to_date" : {
                        required: "To Date is required field.",
                    },
                    "user_ids" : {
                        required: "Users is required field",
                    },
                }
            });
        });
    </script>
@endsection