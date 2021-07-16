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
                                {!! Form::text('from_date',isset($from_date) ? $from_date : null, array('id'=>'from_date','class' => 'form-control','tabindex' => '3' )) !!}
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
                                {!! Form::select('type',$type,$type_id, array('id'=>'type','class' => 'form-control','tabindex' => '2' )) !!}
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
                                {!! Form::text('to_date',isset($to_date) ? $to_date : null, array('id'=>'to_date','class' => 'form-control','tabindex' => '4' )) !!}
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
                            <strong>Select Users: <span class = "required_fields">*</span></strong><br/>&nbsp;&nbsp;
                            <input type="checkbox" id="departments_all" /><strong>Select All</strong><br/>

                            @foreach($departments as $k=>$v)&nbsp;&nbsp; 
                                {!! Form::checkbox('department_ids[]', $k,in_array($k,$selected_departments), array('id'=>'department_ids','class' => 'department_ids','onclick' => 'displayUsers("'.$k.'")')) !!}
                                {!! Form::label ($v) !!}
                            @endforeach <br/><br/>

                            <?php  $id = ''; ?>

                            @foreach($departments as $k=>$v)
                                <div class="div_{{ $k }}" style="margin-left: 12px;display:none;">
                                </div>
                                <?php $id = $id . "," . $k; ?>
                            @endforeach

                            <input type="hidden" name="id_string" id="id_string" value="{{ $id }}">
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

        <input type="hidden" id="action" name="action" value="{!! $action !!}">
        <input type="hidden" name="holiday_id" id="holiday_id" value="{{ $holiday_id }}">
    </div>
@endif

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#from_date").datetimepicker({
                format: "DD-MM-YYYY",
            });

            $("#to_date").datetimepicker({
                format: "DD-MM-YYYY"
            });
           
            $("#departments_all").click(function () {
                
                $('.department_ids').prop('checked', this.checked);

                var isChecked = $("#departments_all").is(":checked");
                var id_string = $("#id_string").val();
                var id_arr = id_string.split(",");;

                if(isChecked == true) {
                    $('.department_ids').prop('checked', this.checked);
                    for (var i = 1; i < id_arr.length; i++) {
                        displayUsers(id_arr[i]);
                    }
                }
                else {
                    $('.department_ids').prop('checked', false);
                    $('.department_class').prop('checked', false);

                    for (var i = 1; i < id_arr.length; i++) {
                        $(".div_"+id_arr[i]).hide();
                    }
                }
            });

            $(".department_ids").click(function () {
                $("#departments_all").prop('checked', ($('.department_ids:checked').length == $('.department_ids').length) ? true : false);

                displayUsers();
            });

            var action = $("#action").val();

            if(action == 'edit') {

                loadUsers();

                $("#departments_all").prop('checked', ($('.department_ids:checked').length == $('.department_ids').length) ? true : false);
            }

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
                    "user_ids[]" : {
                        required: true,
                    },
                    "department_ids[]": {
                        required: true
                    }
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
                    "user_ids[]" : {
                        required: "User is required field",
                    },
                    "department_ids[]": {
                        required: "Please Select Department."
                    }
                }
            });
        });

        function displayUsers(department_id) {

            $.ajax({

                url:'/getusers/bydepartment',
                data:'department_id='+department_id,
                dataType:'json',
                success: function(data) {

                    // for department_ids
                    var department_items = document.getElementsByName('department_ids[]');
                    var department_selected_items = "";

                    for(var i=0; i < department_items.length; i++) {

                        if(department_items[i].type == 'checkbox' && department_items[i].checked == true)
                            department_selected_items += department_items[i].value+",";
                    }

                    var search_str = ","+department_id+",";
                    var search_str_2 = department_id+",";

                    var bool_1 = department_selected_items.includes(department_id);
                    var bool_2 = department_selected_items.search(search_str) > -1;
                    var bool_3 = department_selected_items.search(search_str_2) > -1;

                    if(bool_1 == true || bool_2 == true || bool_3 == true) {

                        if(data.length > 0) {

                            $(".div_"+department_id).html('');

                            var html = '';
                           
                            for (var i = 0; i < data.length; i++) {

                                html += '<input type="checkbox" name="user_ids[]" value="'+data[i].id+'" class="department_class" checked>';
                                html += '&nbsp;&nbsp;';
                                html += '<b><span style="font-size:15px;">'+data[i].name+'</span>&nbsp;&nbsp;</b>';
                            }

                            html += '<br/>';

                            $(".div_"+department_id).append(html);
                            $(".div_"+department_id).show();

                            var isChecked = $("#departments_all").is(":checked");
                            if(isChecked == true) {
                                $('.department_class').prop('checked', true);
                            }
                        }
                    }
                    else {

                        $(".div_"+department_id).html('');
                        $(".div_"+department_id).hide();
                    }
                }
            });
        }

        function loadUsers() {

            // for department_ids
            var department_items = document.getElementsByName('department_ids[]');
            var department_selected_items = "";

            for(var i=0; i<department_items.length; i++) {

                if(department_items[i].type == 'checkbox' && department_items[i].checked == true)
                    department_selected_items += department_items[i].value+",";
            }

            var holiday_id = $("#holiday_id").val();

            $.ajax({

                url:'/getUsersByHolidayID',
                method:'GET',
                data:{'holiday_id':holiday_id,'department_selected_items':department_selected_items},
                dataType:'json',
                success: function(data) {

                    if(data.length > 0) {

                        for (var i = 0; i < data.length; i++) {

                            var html = '';

                            if(data[i].checked == '1') {

                                html += '<input type="checkbox" name="user_ids[]" value="'+data[i].id+'" class="department_class" checked>';
                                html += '&nbsp;&nbsp;';
                                html += '<b><span style="font-size:15px;">'+data[i].name+'</span></b>&nbsp;&nbsp;';
                            }

                            if(data[i].checked == '0') {

                                html += '<input type="checkbox" name="user_ids[]" value="'+data[i].id+'" class="department_class">';
                                html += '&nbsp;&nbsp;';
                                html += '<b><span style="font-size:15px;">'+data[i].name+'</span></b>&nbsp;&nbsp;';
                            }

                            $(".div_"+data[i].type).append(html);
                            $(".div_"+data[i].type).show();
                        }
                    }
                }
            });
        }
    </script>
@endsection