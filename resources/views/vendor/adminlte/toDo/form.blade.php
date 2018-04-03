@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/prettify.css') }}" />
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
        {!! Form::model($toDos,['method' => 'PUT', 'files' => true, 'route' => ['todos.update', $toDos['id']],'id'=>'toDo_form', 'novalidate'=>'novalidate','autocomplete' => 'off']) !!}
        {!! Form::hidden('toDoId', $toDos['id'], array('id'=>'toDoId')) !!}
    @else
        {!! Form::open(['files' => true, 'route' => 'todos.store','id'=>'toDo_form', 'novalidate'=>'novalidate','autocomplete' => 'off']) !!}
    @endif

    {!! Form::hidden('action', $action, array('id'=>'action')) !!}
    {!! Form::hidden('type_list', $type_list, array('id'=>'type_list')) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                    <div class="form-group {{ $errors->has('owner') ? 'has-error' : '' }}">
                        <strong>Task Owner: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('owner', \Auth::user()->name, array('id'=>'owner','placeholder' => 'Owner','class' => 'form-control', 'tabindex' => '0', 'disabled' )) !!}
                        @if ($errors->has('owner'))
                            <span class="help-block">
                                <strong>{{ $errors->first('owner') }}</strong>
                                </span>
                        @endif
                    </div>

                     <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        <strong>Status:</strong>
                        {!! Form::select('status', $status,$status_id, array('id'=>'status','class' => 'form-control' )) !!}
                        @if ($errors->has('status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                                </span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                <div class="">
                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <strong>Subject: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('subject', null, array('id'=>'subject','placeholder' => 'Subject','class' => 'form-control', 'tabindex' => '1' )) !!}
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div  class="form-group {{ $errors->has('due_date') ? 'has-error' : '' }}">
                        <strong>Due Date: <span class = "required_fields">*</span></strong>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            {!! Form::text('due_date',  isset($due_date) ? $due_date : null, array('id'=>'due_date','placeholder' => 'Due Date','class' => 'form-control', 'tabindex' => '3'  )) !!}
                        </div>
                        @if ($errors->has('due_date'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('due_date') }}</strong>
                                    </span>
                        @endif
                    </div>

                </div>
            </div>
                <div style="width:50%;" class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <strong>Type:</strong>
                        @if($action == 'edit')
                        {!! Form::select('type', $type, null, array('id'=>'type','class' => 'form-control', 'tabindex' => '4', 'onchange' => 'getType()' )) !!}
                        @else
                        {!! Form::select('type', $type, 5, array('id'=>'type','class' => 'form-control', 'tabindex' => '4', 'onchange' => 'getType()' )) !!}
                        @endif
                        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group type_list" >

                        <div class="col-sm-5">
                            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                            <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                            <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                            <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                        </div>
                        <div class="col-sm-5">
                            <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <span style="padding: 10%;"></span>

                   

                    <div  style="width:50%;"  class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <strong>Remarks:</strong>
                        {!! Form::textarea('description', null, array('id'=>'description','rows'=>'5','placeholder' => 'Remarks','class' => 'form-control', 'tabindex' => '8' )) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
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
                     <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
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


            $("#users_all").click(function () {
                $('.users_ids').prop('checked', this.checked);
            });

            $(".users_ids").click(function () {
                $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
            });

            $('#search').multiselect({
                search: {
                    left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                },
                fireSearch: function(value) {
                    return value.length > 1;
                }
            });

            getType();
            getselectedtypelist();
        });
        function getType(){
            var selectedType = $("#type").val();

            if(selectedType!=5){
                $(".type_list").show();
                var typelist = $("#type_list").val();
                $.ajax({
                  url:'/ajax/todotype',
                  data:'selectedType='+selectedType,
                  dataType:'json',
                  success: function(data){
                      $("#search").empty();
                      $("#search_to").empty();
                      for(var i=0;i<data.length;i++){
                          //$('#typeList').append($('<option></option>').val(data[i].id).html(data[i].value));
                          //$('#typeList').select2('val', typelist)
                          $('#search').append($('<option data-position="'+(i+1)+'"></option>').val(data[i].id).html(data[i].value))
                      }
                  }
                });
            }

            else{
                $("#search").empty();
                $("#search_to").empty();
                $(".type_list").hide();
            }

        }

        function getselectedtypelist() {
            var action = $("#action").val();
            var toDoId = $("#toDoId").val();
            var selectedType = $("#type").val();

            if(action=='edit' && selectedType!=5){
                $(".type_list").show();
                $.ajax({
                    url:'/todo/getselectedtypelist',
                    data:'selectedType='+selectedType+'&toDoId='+toDoId,
                    dataType:'json',
                    success: function(data){
                        $("#search_to").empty();
                        for(var i=0;i<data.length;i++){
                            $('#search_to').append($('<option data-position="'+(i+1)+'"></option>').val(data[i].id).html(data[i].value))
                            $('#search option[value="'+data[i].id+'"]').remove();
                        }
                    }
                });
            }
        }

        //$("#typeList").select2();

    </script>
@endsection