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
                <h2>Edit Task</h2>
            @else
                <h2>Create New Task</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('todos.index') }}"> Back</a>
        </div>

    </div>
</div>

@if(isset($action))

    @if($action == 'edit')
        {!! Form::model($toDos,['method' => 'PUT', 'files' => true, 'route' => ['todos.update', $toDos['id']],'id'=>'toDo_form', 'novalidate'=>'novalidate']) !!}
        {!! Form::hidden('toDoId', $toDos['id'], array('id'=>'toDoId')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'todos.store','id'=>'toDo_form', 'novalidate'=>'novalidate']) !!}
    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}
    {!! Form::hidden('type_list', $type_list, array('id'=>'type_list')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">

                    <div class="box-body col-xs-12 col-sm-12 col-md-12">
                        <div class="box-body col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('owner') ? 'has-error' : '' }}">
                                <strong>Task Owner: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('owner', \Auth::user()->name, array('id'=>'owner','placeholder' => 'Owner','class' => 'form-control', 'tabindex' => '0', 'disabled' )) !!}
                                @if ($errors->has('owner'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('owner') }}</strong>
                                </span>
                                @endif
                            </div>

                            {{--<div class="form-group {{ $errors->has('candidate') ? 'has-error' : '' }}">
                                <strong>Users: <span class = "required_fields">*</span> </strong>
                                {!! Form::select('candidate', $users,null, array('id'=>'candidate','class' => 'form-control', 'tabindex' => '2' )) !!}
                                @if ($errors->has('candidate'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('candidate') }}</strong>
                                </span>
                                @endif
                            </div>--}}

                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <strong>Type:</strong>
                                {!! Form::select('type', $type, null, array('id'=>'type','class' => 'form-control', 'tabindex' => '4', 'onchange' => 'getType()' )) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                <strong>Status:</strong>
                                {!! Form::select('status', $status,null, array('id'=>'status','class' => 'form-control', 'tabindex' => '6' )) !!}
                                {{--                                {!! Form::text('status', null, array('id'=>'status','placeholder' => 'Status','class' => 'form-control', 'tabindex' => '10' )) !!}--}}
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <strong>Description:</strong>
                                {!! Form::textarea('description', null, array('id'=>'description','rows'=>'5','placeholder' => 'Description','class' => 'form-control', 'tabindex' => '8' )) !!}
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="box-body col-xs-6 col-sm-6 col-md-6">

                            <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                <strong>Subject: <span class = "required_fields">*</span> </strong>
                                {!! Form::text('subject', null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1' )) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('typeList') ? 'has-error' : '' }}">
                                <strong>Type List:</strong>
                                {{--                                {!! Form::select('typeList',array(''=>'Select Type List'), null,array('id'=>'typeList','class' => 'form-control', 'tabindex' => '5')) !!}--}}
                                {!! Form::select('typeList',$client, $type_list,array('id'=>'typeList','class' => 'form-control', 'tabindex' => '5')) !!}
                                @if ($errors->has('typeList'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('typeList') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('due_date') ? 'has-error' : '' }}">
                                <strong>Due Date: <span class = "required_fields">*</span></strong>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('due_date',  isset($dueDate) ? $dueDate : null, array('id'=>'due_date','placeholder' => 'Due Date','class' => 'form-control', 'tabindex' => '3'  )) !!}
                                </div>
                                @if ($errors->has('due_date'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('due_date') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('priority') ? 'has-error' : '' }}">
                                <strong>Priority:</strong>
                                {!! Form::select('priority', $priority,null, array('id'=>'priority','class' => 'form-control', 'tabindex' => '7' )) !!}
                                @if ($errors->has('priority'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('priority') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="box-body col-xs-12 col-sm-12 col-md-12">


                            <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                                <strong>Select Users : <span class = "required_fields">*</span></strong>
                                <input type="checkbox" id="users_all"/> <strong>Select All</strong>
                                @foreach($users as $k=>$v)<br/>
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
        </div>

        <div class="form-group">
            <div class="col-sm-2">&nbsp;</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {!! Form::submit(isset($toDos) ? 'Update' : 'Submit', ['class' => 'btn btn-primary', 'novalidate' => 'novalidate' ]) !!}
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
    <script type="text/javascript">
        $(document).ready(function(){
            $("#due_date").datetimepicker({
                format: "DD-MM-YYYY HH:mm:ss"
            });
//            $('#typeList').select2();
            getType();

            $("#users_all").click(function () {
                $('.users_ids').prop('checked', this.checked);
            });

            $(".users_ids").click(function () {
                $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
            });

        });
        function getType(){
            var selectedType = $("#type").val();
            var typelist = $("#type_list").val();
            console.log(selectedType);
//type_list
            //alert(typelist);
            $.ajax({
                url:'/ajax/todotype',
                data:'selectedType='+selectedType,
                dataType:'json',
                success: function(data){
                    $("#typeList").empty();
                    for(var i=0;i<data.length;i++){
                        $('#typeList').append($('<option></option>').val(data[i].id).html(data[i].value));
                        $('#typeList').select2('val', typelist)
                    }
                }
            });
        }
        $("#typeList").select2();
    </script>
@endsection