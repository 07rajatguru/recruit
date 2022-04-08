@section('customs_css')
    <style>
        .error {
            color:#f56954 !important;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Email Template</h2>
            @else
                <h2>Add Email Template</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('emailtemplate.index') }}">Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($email_template,['method' => 'PATCH', 'files' => true, 'route' => ['emailtemplate.update', $email_template['id']],'id'=>'email_template_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'emailtemplate.store', 'id'=>'email_template_form', 'autocomplete' => 'off']) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-8 col-sm-8 col-md-8">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <strong>Template Name: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('name', null, array('id'=>'name','class' => 'form-control','placeholder' => 'Template Name','tabindex' => '1' )) !!}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <strong>Subject: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('subject', null, array('id'=>'subject','class' => 'form-control','placeholder' => 'Subject','tabindex' => '2' )) !!}
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Select Users who can see this Template : <span class = "required_fields">*</span></strong><br/>&nbsp;&nbsp;
                        <input type="checkbox" id="departments_all"/><strong>Select All</strong>
                        <br/>

                        @foreach($departments as $k=>$v)&nbsp;&nbsp; 
                            {!! Form::checkbox('department_ids[]', $k,in_array($k,$selected_departments), array('id'=>'department_ids','class' => 'department_ids','onclick' => 'displayUsers("'.$k.'")')) !!}
                            {!! Form::label ($v) !!}
                        @endforeach<br/><br/>

                        <?php $id = ''; ?>
                        @foreach($departments as $k=>$v)
                            <div class="div_{{ $k }}" style="margin-left: 12px;display:none;"></div>
                            <?php $id = $id . "," . $k; ?>
                        @endforeach

                        <input type="hidden" name="id_string" id="id_string" value="{{ $id }}">
                    </div>

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <strong>Email Body : </strong>
                        {!! Form::textarea('email_body',null, array('id'=>'email_body','placeholder' => 'Email Body','class' => 'form-control','tabindex' => '3','rows' => '4')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <table id="variable_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                        <tr>
                            <th style="text-align: center;">Variable Name</th>
                            <th style="text-align: center;">Description</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{Clientname}</td>
                            <td style="text-align: center;">Name of the Client</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{Lusignature}</td>
                            <td style="text-align: center;">Logged in User Signature</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit(isset($email_template) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    <input type="hidden" name="action" id="action" value="{{ $action }}">
    <input type="hidden" name="email_template_id" id="email_template_id" value="{{ $email_template_id }}">
</div>

{!! Form::close() !!}

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script>
        $(document).ready(function() {

            CKEDITOR.replace( 'email_body', {
                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/email_template_ckeditor.js',
            });

            CKEDITOR.on('dialogDefinition', function( ev ) {
               var dialogName = ev.data.name;  
               var dialogDefinition = ev.data.definition;
                     
               switch (dialogName) {  
               case 'image': //Image Properties dialog      
               dialogDefinition.removeContents('Link');
               dialogDefinition.removeContents('advanced');
               break;      
               case 'link': //image Properties dialog          
               dialogDefinition.removeContents('advanced');   
               break;
               }
            });

            $("#email_template_form").validate({

                rules: {
                    "name": {
                        required: true
                    },
                    "subject": {
                        required: true
                    },
                    "department_ids[]": {
                        required: true
                    },
                },
                messages: {
                    "name": {
                        required: "Template Name is Required Field."
                    },
                    "subject": {
                        required: "Subject is Required Field."
                    },
                    "department_ids[]": {
                        required: "Please Select Users."
                    },
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

            var email_template_id = $("#email_template_id").val();

            $.ajax({

                url:'/getUsersByEmailTemplateID',
                method:'GET',
                data:{'email_template_id':email_template_id,'department_selected_items':department_selected_items},
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