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
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <strong>Title : <span class = "required_fields">*</span></strong>
                        {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Title','class' => 'form-control','tabindex' => '1')) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Department : <span class = "required_fields">*</span> </strong>
                        {!! Form::select('department', $all_departments,$department_id, array('id'=>'department','class' => 'form-control', 'tabindex' => '3')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Select Users who can see this Material : <span class = "required_fields">*</span></strong><br/>&nbsp;&nbsp;
                        <input type="checkbox" id="departments_all"/><strong>Select All</strong><br/>

                        @foreach($departments as $k=>$v)&nbsp;&nbsp; 
                            {!! Form::checkbox('department_ids[]', $k,in_array($k,$selected_departments), array('id'=>'department_ids','class' => 'department_ids','onclick' => 'displayUsers("'.$k.'")')) !!}{!! Form::label ($v) !!}
                        @endforeach

                        <br/><br/>

                        <?php $id = ''; ?>

                        @foreach($departments as $k=>$v)
                            <div class="div_{{ $k }}" style="margin-left: 12px;display:none;"></div>
                            <?php $id = $id . "," . $k; ?>
                        @endforeach

                        <input type="hidden" name="id_string" id="id_string" value="{{ $id }}">
                    </div>
                </div>
            </div>
            
            @if($action == "add")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Documents : <span class = "required_fields">*</span></strong>
                        <input type="file" name="upload_documents[]" id="upload_documents" multiple class="form-control"/>
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

        <input type="hidden" name="action" id="action" value="{{ $action }}">
        <input type="hidden" name="training_id" id="training_id" value="{{ $training_id }}">
    </div>
</div>


@section('customscripts')
    <script>
        $(document).ready(function() {

            $("#department").select2();

            $("#training_form").validate({
                rules: {
                    "title": {
                        required: true
                    },
                    "department_ids[]": {
                        required: true
                    },
                    "upload_documents[]": {
                        required: true
                    },
                    "department": {
                        required: true
                    },
                },
                messages: {
                    "title": {
                        required: "Title is Required Field."
                    },
                    "department_ids[]": {
                        required: "Please Select Department."
                    },
                    "upload_documents[]": {
                        required: "Please Select File."
                    },
                    "department": {
                        required: "Please Select Department."
                    }
                }
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

            var training_id = $("#training_id").val();

            $.ajax({

                url:'/getUsersByTrainingID',
                method:'GET',
                data:{'training_id':training_id,'department_selected_items':department_selected_items},
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