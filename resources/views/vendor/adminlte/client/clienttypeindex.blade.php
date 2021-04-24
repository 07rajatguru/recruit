@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 margin-tb">
            <div class="pull-right">

                @if($source == 'Left')

                @else
                    @permission(('display-account-manager-wise-client'))
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchmodal" onclick="client_emails_notification()">Send Mail</button>
                    @endpermission
                @endif

                @permission(('display-client'))
                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#accountmanagermodal" onclick="client_account_manager()">Change Account Manager
                    </button>

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#secondlineammodal" onclick="second_line_client_am()">Change 2nd Line AM</button>
                @endpermission
                <a class="btn btn-primary" href="{{ route('client.index') }}">Back</a>
            </div>

            <div class="pull-left">
                <h2> {{ $source }} Clients <span id="count">({{ $count or 0 }})</span></h2>
            </div>
        </div>
    </div>

    <input type="hidden" name="source" id="source" value="{{ $source }}">

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.list','Active') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Active Clients">Active({{ $active }})</div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.list','Passive') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Passive Clients">Passive({{ $passive }}) </div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.list','Leaders') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#337ab7;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Leaders Clients">Leaders({{ $leaders }})</div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.list','Left') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Left Clients">Left({{ $left }}) </div>
                </a>
            </div>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-md-12">
            @permission(('display-paramount-client-list'))
                <div class="col-md-2">
                    <a href="{{ route('client.list','Paramount') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#E9967A;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Paramount Clients">Paramount ({{ $para_cat }})</div></a>
                </div>
            @endpermission

            @permission(('display-standard-client-list'))
                <div class="col-md-2">
                    <a href="{{ route('client.list','Moderate') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#D3D3D3;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Moderate Clients">Moderate ({{ $mode_cat }})</div>
                    </a>
                </div>
            @endpermission

            @permission(('display-moderate-client-list'))
                <div class="col-md-2">
                    <a href="{{ route('client.list','Standard') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#00CED1;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Standard Clients">Standard ({{ $std_cat }})</div>
                    </a>
                </div>
            @endpermission
        </div>
    </div><br/>
    
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="clienttype_table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>
                <th>Contact Point</th>

                @permission(('display-client-category-in-client-list'))
                    <th>Client Category</th>
                @endpermission

                <th>Status</th>
                <th>City</th>
                <th>Remarks</th>
                <th>New AM</th>
            </tr>
        </thead>
    </table>

     <div id="searchmodal" class="modal text-left fade email_modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Send Mail To Clients</h4>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'client.emailnotification']) !!}
                    <div class="modal-body">

                        <div class="email_temp_class" style="display: none;">
                            <div class="form-group">
                                <strong>Select Saved Email Template : </strong><br/><br/>
                                {!! Form::select('email_template_id',$email_template_names,null, array('id'=> 'email_template_id','class' => 'form-control','onchange' => 'setExistEmailTemplate()')) !!}
                            </div>
                        </div>

                        <div class="body_class" style="display: none;">

                            <div class="form-group">
                                <strong>Template Name : <span class = "required_fields">*</span>
                                </strong>
                                {!! Form::text('template_nm', null, array('id'=>'template_nm','placeholder' => 'Template Name','tabindex' => '1','class' => 'form-control','required')) !!}
                            </div>

                            <label id="template_nm_error" style="color:#f56954;display:none;"></label>

                            <div class="form-group">
                                <strong>Subject : <span class = "required_fields">*</span> </strong>
                                {!! Form::text('email_subject', null, array('id'=>'email_subject','placeholder' => 'Subject','tabindex' => '2','class' => 'form-control','required')) !!}
                            </div>

                            <label id="email_subject_error" style="color:#f56954;display:none;"></label>

                            <div class="form-group">
                                <strong>Message : <span class = "required_fields">*</span> </strong>
                                {!! Form::textarea('email_body', null, array('id'=>'email_body','placeholder' => 'Message','class' => 'form-control', 'tabindex' => '3','required')) !!}
                            </div>

                            <label id="email_body_error" style="color:#f56954;display:none;"></label>
                        </div>
                        <div class="email_error"></div>
                    </div>

                    <input type="hidden" name="email_client_ids" id="email_client_ids" value="">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="email_temp_submit_id" onclick="saveTemplate();">Save as New Template</button>
                        <button type="submit" class="btn btn-primary" id="email_submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Account Manager Modal Popup -->

    <div id="accountmanagermodal" class="modal text-left fade acc_mngr_modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Change Account Manager</h4>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'client.accountmanager']) !!}
                    <div class="modal-body">
                        <div class="ac_mngr_cls">
                            <div class="form-group">
                                <strong>Select Account Manager : <span class = "required_fields">*</span></strong><br/><br/>
                                {!! Form::select('account_manager_id',$all_account_manager,null, array('id'=>'account_manager_id','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="act_mngr_error"></div>
                    </div>

                    <input type="hidden" name="client_ids" id="client_ids" value="">

                    <input type="hidden" name="am_source" id="am_source" value="{{ $source }}">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- 2nd Line Account Manager Modal Popup -->

    <div id="secondlineammodal" class="modal text-left fade second_line_am_modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Change 2nd Line Account Manager</h4>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'client.secondlineam']) !!}
                    <div class="modal-body">
                        <div class="second_line_ac_mngr_cls">
                            <div class="form-group">
                                <strong>Select Account Manager : <span class = "required_fields">*</span></strong><br/><br/>
                                {!! Form::select('second_line_am_id',$all_account_manager,null, array('id'=>'second_line_am_id','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="second_line_am_error"></div>
                    </div>

                    <input type="hidden" name="second_line_am_client_ids" id="second_line_am_client_ids" value="">

                    <input type="hidden" name="second_line_am_source" id="second_line_am_source" value="{{ $source }}">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="second_line_am_submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">

        jQuery( document ).ready(function() {

            $("#account_manager_id").select2({width : '567px'});
            $("#second_line_am_id").select2({width : '567px'});
            $("#email_template_id").select2({width : '567px'});

            var source = $("#source").val();
            var numCols = $('#clienttype_table thead th').length;

            $("#clienttype_table").DataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},{orderable: false, targets: [2]} ],
                "ajax" : {
                    'url' : '/client/allbytype',
                    data : {"source" : source},
                    'type' : 'get',
                    error: function() {
                    }
                },
                initComplete:function( settings, json) {
                    var count = json.recordsTotal;
                    $("#count").html("(" + count + ")");
                },
                responsive: true,
                "pageLength": 25,
                "pagingType": "full_numbers",
                "fnRowCallback": function( Row, Data ) {
                    
                    if(numCols == 10) {

                        if ( Data[9] != "0" ) {
                            $('td', Row).css('background-color', '#E8E8E8');
                        }
                        else {
                            $('td', Row).css('background-color', 'white');
                        }
                    }
                    else {
                        if ( Data[10] != "0" ) {
                            $('td', Row).css('background-color', '#E8E8E8');
                        }
                        else {
                            $('td', Row).css('background-color', 'white');
                        }
                    }
                }
            });

            var table = $('#clienttype_table').DataTable();

            if(numCols == 10) {
                table.columns( [9] ).visible( false );
            }
            else {
                table.columns( [10] ).visible( false );
            }

            $('#allcb').change(function() {

                if($(this).prop('checked')) {
                    $('tbody tr td input[type="checkbox"]').each(function() {
                        $(this).prop('checked', true);
                    });
                }else {
                    $('tbody tr td input[type="checkbox"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });
            $('.others_client').change(function() {

                if ($(this).prop('checked')) {
                    if ($('.others_client:checked').length == $('.others_client').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else {
                    $("#allcb").prop('checked', false);
                }
            });
        });

        function client_emails_notification() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var client_ids = new Array();

            var table = $("#clienttype_table").dataTable();
            
            table.$("input:checkbox[name=client]:checked").each(function(){
                client_ids.push($(this).val());
            });

            $("#email_client_ids").val(client_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/client/checkClientId',
                data : {client_ids : client_ids, '_token':token},
                dataType : 'json',
                success: function(msg) {

                    if (msg.success == 'Success') {

                        $(".email_modal").show();
                        $(".email_error").empty();
                        $('#email_submit').show();
                        $('#email_temp_submit_id').show();
                        $('.email_temp_class').show();
                        setEmailTemplate();
                    }
                    else if (msg.success == 'Leaders Clients') {

                        if(confirm("The selected client is in the Leaders list. Do you still wish to continue?")) {

                            $(".email_error").empty();
                            $('#email_submit').show();
                            $('#email_temp_submit_id').show();
                            $('.email_temp_class').show();
                            $(".email_modal").show();
                            setEmailTemplate();
                        }
                        else {

                            $("#searchmodal").modal('hide');
                        }
                    }
                    else {

                        $(".email_modal").show();
                        $(".email_error").empty();
                        $('#email_submit').hide();
                        $('#email_temp_submit_id').hide();
                        $(".email_error").append(msg.err);
                        $('.email_temp_class').hide();
                        $('.body_class').hide();
                    }
                }
            });
        }

        function client_account_manager() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var client_ids = new Array();

            var table = $("#clienttype_table").dataTable();

            table.$("input:checkbox[name=client]:checked").each(function(){
                client_ids.push($(this).val());
            });

            $("#client_ids").val(client_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/client/checkClientId',
                data : {client_ids : client_ids, '_token':token},
                dataType : 'json',
                success: function(msg) {

                    if (msg.success == 'Success') {

                        $(".acc_mngr_modal").show();
                        $(".ac_mngr_cls").show();
                        $(".act_mngr_error").empty();
                        $('#submit').show();
                    }
                    else if (msg.success == 'Leaders Clients') {

                        $(".acc_mngr_modal").show();
                        $(".ac_mngr_cls").show();
                        $(".act_mngr_error").empty();
                        $('#submit').show();
                    }
                    else {

                        $(".acc_mngr_modal").show();
                        $(".ac_mngr_cls").hide();
                        $(".act_mngr_error").empty();
                        $('#submit').hide();
                        $(".act_mngr_error").append(msg.err);
                    }
                }
            });
        }

        function setEmailTemplate() {

            $(".body_class").show();

            CKEDITOR.replace( 'email_body', {

                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js',
                height: '100px',
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
        }

        function saveTemplate() {
        
            var token = $('input[name="csrf_token"]').val();

            var template_nm = $("#template_nm").val();

            if(template_nm == '') {
                $("#template_nm_error").show();
                $("#template_nm_error").text("Template Name is Required Field.");
            }
            else {
                $("#template_nm_error").hide();
            }

            var email_subject = $("#email_subject").val();

            if(email_subject == '') {
                $("#email_subject_error").show();
                $("#email_subject_error").text("Subject is Required Field.");
            }
            else {
                $("#email_subject_error").hide();
            }

            var email_body = CKEDITOR.instances.email_body.getData();

            if(email_body == '') {
                $("#email_body_error").show();
                $("#email_body_error").text("Message is Required Field.");
            }
            else {
                $("#email_body_error").hide();
            }

            if(template_nm != '' && email_subject != '' && email_body != '') {

                $.ajax({

                    type: 'POST',
                    url: '/email-template/store',
                    data:{'template_nm': template_nm,'email_subject': email_subject,'email_body': email_body,'_token':token},
                    dataType: 'json',
                    success: function (data) {

                        alert("Email Template Saved Successfully.");
                    },
                });
            }
        }

        function setExistEmailTemplate() {
       
            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var email_template_id = $("#email_template_id").val();

            $.ajax({

                type : 'GET',
                url : app_url+'/email-template/getDetailsById',
                data : {email_template_id : email_template_id, '_token':token},
                dataType : 'json',

                success: function(data) {

                    if(email_template_id == 0) {

                        $("#template_nm").val("");
                        $("#email_subject").val("");
                        CKEDITOR.instances.email_body.setData("");
                    }
                    else {

                        $("#template_nm").val(data.name);
                        $("#email_subject").val(data.subject);
                        CKEDITOR.instances.email_body.setData(data.email_body);
                    }
                }
            });
        }

        function second_line_client_am() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var client_ids = new Array();

            var table = $("#clienttype_table").dataTable();

            table.$("input:checkbox[name=client]:checked").each(function(){
                client_ids.push($(this).val());
            });

            $("#second_line_am_client_ids").val(client_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/client/checkClientId',
                data : {client_ids : client_ids, '_token':token},
                dataType : 'json',
                success: function(msg) {
                    
                    if (msg.success == 'Success') {

                        $(".second_line_am_modal").show();
                        $(".second_line_ac_mngr_cls").show();
                        $(".second_line_am_error").empty();
                        $('#second_line_am_submit').show();
                    }
                    else if (msg.success == 'Leaders Clients') {

                        $(".second_line_am_modal").show();
                        $(".second_line_ac_mngr_cls").show();
                        $(".second_line_am_error").empty();
                        $('#second_line_am_submit').show();
                    }
                    else {

                        $(".second_line_am_modal").show();
                        $(".second_line_ac_mngr_cls").hide();
                        $(".second_line_am_error").empty();
                        $('#second_line_am_submit').hide();
                        $(".second_line_am_error").append(msg.err);
                    }
                }
            });
        }
    </script>
@endsection