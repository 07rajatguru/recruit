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

                <a class="btn btn-success" href="{{ route('client.create') }}" title="Add New Client">Add New Client</a>

                @permission(('display-client'))
                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#accountmanagermodal" onclick="client_account_manager()" title="Change Account Manager">Change Account Manager</button>
                @endpermission

                @permission(('display-account-manager-wise-client'))

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changestatus" onclick="change_status()" title="Change Status">Change Status</button> <br/><br/>

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchmodal" onclick="client_emails_notification()" title="Send Mail">Send Mail</button>

                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#secondlineammodal" onclick="second_line_client_am()" title="Change 2nd Line AM">Change 2nd Line AM</button>
                @endpermission

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mastersearchmodal" onclick="" title="Master Search">Master Search</button>
            </div>
            
            <div class="pull-left">
                <h2>Clients List <span id="count">({{ $count or 0 }})</span></h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2" style="width: 12%;">
                <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Active Clients">Active <span id="active_count">({{ $active }})</span></div></a>
            </div>

            <div class="col-md-2" style="width: 12%;">
                <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Passive Clients">Passive <span id="passive_count">({{ $passive }})</span></div></a>
            </div>

            <div class="col-md-2" style="width: 12%;">
                <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#337ab7;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Leaders Clients">Leaders <span id="leaders_count">({{ $leaders }})</span></div></a>
            </div>

            <div class="col-md-2" style="width: 12%;">
                <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Left Clients">Left <span id="left_count">({{ $left }})</span></div></a>
            </div>
        </div>
    </div><br/>
    
    <div class="row">
        <div class="col-md-12">
            @permission(('display-paramount-client-list'))
                <div class="col-md-2 col-sm-4">
                    <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#E9967A;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Paramount Clients">Paramount <span id="paramount_count">({{ $para_cat }})</span></div></a>
                </div>
            @endpermission

            @permission(('display-moderate-client-list'))
                <div class="col-md-2 col-sm-4">
                    <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#D3D3D3;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Moderate Clients">Moderate <span id="moderate_count">({{ $mode_cat }})</span></div></a>
                </div>
            @endpermission

            @permission(('display-standard-client-list'))
                <div class="col-md-2 col-sm-4">
                    <a style="text-decoration: none;color: black;cursor: pointer;"><div style="margin:5px;height:35px;background-color:#00CED1;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Standard Clients">Standard <span id="standard_count">({{ $std_cat }})</span></div></a>
                </div>
            @endpermission
        </div>
    </div><br>
    
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

    <!-- For client others information popup-->

    <input type="hidden" name="client_name_string" id="client_name_string" value="{{ $client_name_string }}">

    <div id="clientModal" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Information</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Please fill up remaining details of following clients : <br/><br/>
                        @if(isset($client_name_string) && $client_name_string != '')
                            <?php
                                $client_name_array = explode(",", $client_name_string);
                            ?>

                            @if(isset($client_name_array) && sizeof($client_name_array) > 0)
                                <?php $i=1; ?>
                                @foreach($client_name_array as $key => $value)
                                    <?php
                                        echo $i;
                                        echo ".    ";
                                        echo $value;
                                    ?>
                                <br/><?php $i++ ?>
                                @endforeach
                            @endif
                        @endif
                    </p><br/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End client others information popup-->
 
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_table">
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

                        @if($user_id == $superadmin || $user_id == $manager)
                            <button type="button" class="btn btn-primary" id="email_temp_submit_id" onclick="saveTemplate();">Save as New Template</button>
                        @endif
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

                    <input type="hidden" name="am_source" id="am_source" value="">

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

                    <input type="hidden" name="second_line_am_source" id="second_line_am_source" value="">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="second_line_am_submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    <!-- Client Status Modal Popup -->

    <div id="changestatus" class="modal text-left fade status_modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Change Client Status</h4>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'client.status']) !!}
                    <div class="modal-body">
                        <div class="status_cls">
                            <div class="form-group">
                                <strong>Select Client Status : <span class = "required_fields">*</span></strong><br/><br/>
                                {!! Form::select('status_id',$status,$status_id, array('id'=>'status_id','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="status_error"></div>
                    </div>

                    <input type="hidden" name="status_client_ids" id="status_client_ids" value="">

                    <input type="hidden" name="status_source" id="status_source" value="">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="status_submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Client Master Search Modal Popup -->

    <div class="modal fade mastersearchmodal" id="mastersearchmodal" aria-labelledby="mastersearchmodal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Search Options</h4>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Select filed Which you want to search : </strong>
                            {!! Form::select('selected_field', $field_list,null, array('id'=>'selected_field', 'class' => 'form-control','tabindex' => '1','onchange' => 'displaySelectedField()')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_owner_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Owner : </strong>
                            {!! Form::text('client_owner', null, array('id'=>'client_owner','placeholder' => 'Client Owner','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_company_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Company Name : </strong>
                            {!! Form::text('client_company', null, array('id'=>'client_company','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_contact_point_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Contact Point : </strong>
                            {!! Form::text('client_contact_point', null, array('id'=>'client_contact_point','placeholder' => 'Contact Point','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_cat_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Category : </strong>
                            {!! Form::text('client_cat', null, array('id'=>'client_cat','placeholder' => 'Client Category','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_status_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Status : </strong>
                            {!! Form::text('client_status', null, array('id'=>'client_status','placeholder' => 'Client Status','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_city_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client City : </strong>
                            {!! Form::text('client_city', null, array('id'=>'client_city','placeholder' => 'Client City','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>
         
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="displayresults();">Search
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="superadmin" id="superadmin" value="{{ $superadmin }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
@stop

@section('customscripts')

<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function() {

        $("#account_manager_id").select2({width : '567px'});
        $("#second_line_am_id").select2({width : '567px'});
        $("#email_template_id").select2({width : '567px'});
        $("#selected_field").select2({width : '567px'});
        
        var numCols = $('#client_table thead th').length;

        var client_owner = $("#client_owner").val();
        var client_company = $("#client_company").val();
        var client_contact_point = $("#client_contact_point").val();
        var client_cat = $("#client_cat").val();
        var client_status = $("#client_status").val();
        var client_city = $("#client_city").val();

        $("#client_table").DataTable({

            'bProcessing' : true,
            'serverSide' : true,
            "order" : [0,'desc'],
            "columnDefs": [ {orderable: false, targets: [1]},{orderable: false, targets: [2]} ],
            "ajax" : {
                'url' : 'client-search/all',
                'type' : 'get',
                "data" : {
                    "client_owner": client_owner,
                    "client_company"  : client_company,
                    "client_contact_point"  : client_contact_point,
                    "client_cat"  : client_cat,
                    "client_status"  : client_status,
                    "client_city"  : client_city,
                },
                error: function() {
                }
            },
            initComplete:function( settings, json) {

                var count = json.recordsTotal;
                $("#count").html("(" + count + ")");

                var active_count = json.active_count;
                $("#active_count").html("(" + active_count + ")");

                var passive_count = json.passive_count;
                $("#passive_count").html("(" + passive_count + ")");

                var leaders_count = json.leaders_count;
                $("#leaders_count").html("(" + leaders_count + ")");

                var left_count = json.left_count;
                $("#left_count").html("(" + left_count + ")");

                var paramount_count = json.paramount_count;
                $("#paramount_count").html("(" + paramount_count + ")");

                var moderate_count = json.moderate_count;
                $("#moderate_count").html("(" + moderate_count + ")");

                var standard_count = json.standard_count;
                $("#standard_count").html("(" + standard_count + ")");
                
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
                    if ( Data[8] != "0" ) {
                        $('td', Row).css('background-color', '#E8E8E8');
                    }
                    else {
                        $('td', Row).css('background-color', 'white');
                    }
                }
            }
        });

        var table = $('#client_table').DataTable();

        if(numCols == 10) {
            table.columns( [9] ).visible( false );
        }
        else {
            table.columns( [8] ).visible( false );
        }

        $('#allcb').change(function() {

            if($(this).prop('checked')) {
                $('tbody tr td input[type="checkbox"]').each(function() {
                    $(this).prop('checked', true);
                });
            }
            else {
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

        var client_name_string = $("#client_name_string").val();

        if(client_name_string != '') {

            var superadmin = $("#superadmin").val();
            var user_id = $("#user_id").val();

            if(superadmin == user_id) {

                var event = new Date();
                var options = { weekday: 'long' };
                var day = event.toLocaleDateString('en-US', options);

                var hours = event.getHours();
                var minutes = event.getMinutes();

                if((day == 'Saturday' && hours == '11') || (day == 'Saturday' && hours == '12' && minutes == '0')) {
                    jQuery("#clientModal").modal('show');
                }
            }
            else {
                jQuery("#clientModal").modal('show');
            }
        }
    });

    function client_emails_notification() {

        var token = $('input[name="csrf_token"]').val();
        var app_url = "{!! env('APP_URL'); !!}";
        var client_ids = new Array();

        var table = $("#client_table").dataTable();
        
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

        var table = $("#client_table").dataTable();

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

        var table = $("#client_table").dataTable();

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

    function change_status() {

        var token = $('input[name="csrf_token"]').val();
        var app_url = "{!! env('APP_URL'); !!}";
        var status_client_ids = new Array();

        var table = $("#client_table").dataTable();

        table.$("input:checkbox[name=client]:checked").each(function(){
            status_client_ids.push($(this).val());
        });

        $("#status_client_ids").val(status_client_ids);

        $.ajax({

            type : 'POST',
            url : app_url+'/client/checkClientId',
            data : {client_ids : status_client_ids, '_token':token},
            dataType : 'json',
            success: function(msg) {

                if (msg.success == 'Success') {

                    $(".status_modal").show();
                    $(".status_cls").show();
                    $(".status_error").empty();
                    $('#status_submit').show();
                }
                else if (msg.success == 'Leaders Clients') {

                    $(".status_modal").show();
                    $(".status_cls").show();
                    $(".status_error").empty();
                    $('#status_submit').show();
                }
                else {

                    $(".status_modal").show();
                    $(".status_cls").hide();
                    $(".status_error").empty();
                    $('#status_submit').hide();
                    $(".status_error").append(msg.err);
                }
            }
        });
    }

    function displaySelectedField() {

        var selected_field = $("#selected_field").val();

        if(selected_field == 'Client Owner') {

            $(".client_owner_cls").show();
            $(".client_company_cls").hide();
            $(".client_contact_point_cls").hide();
            $(".client_cat_cls").hide();
            $(".client_status_cls").hide();
            $(".client_city_cls").hide();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }

        if(selected_field == 'Company Name') {

            $(".client_owner_cls").hide();
            $(".client_company_cls").show();
            $(".client_contact_point_cls").hide();
            $(".client_cat_cls").hide();
            $(".client_status_cls").hide();
            $(".client_city_cls").hide();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }

        if(selected_field == 'Contact Point') {

            $(".client_owner_cls").hide();
            $(".client_company_cls").hide();
            $(".client_contact_point_cls").show();
            $(".client_cat_cls").hide();
            $(".client_status_cls").hide();
            $(".client_city_cls").hide();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }

        if(selected_field == 'Client Category') {

            $(".client_owner_cls").hide();
            $(".client_company_cls").hide();
            $(".client_contact_point_cls").hide();
            $(".client_cat_cls").show();
            $(".client_status_cls").hide();
            $(".client_city_cls").hide();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }

        if(selected_field == 'Client Status') {

            $(".client_owner_cls").hide();
            $(".client_company_cls").hide();
            $(".client_contact_point_cls").hide();
            $(".client_cat_cls").hide();
            $(".client_status_cls").show();
            $(".client_city_cls").hide();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }

        if(selected_field == 'Client City') {

            $(".client_owner_cls").hide();
            $(".client_company_cls").hide();
            $(".client_contact_point_cls").hide();
            $(".client_cat_cls").hide();
            $(".client_status_cls").hide();
            $(".client_city_cls").show();

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");
        }
    }

    function displayresults() {

        var client_owner = $("#client_owner").val();
        var client_company = $("#client_company").val();
        var client_contact_point = $("#client_contact_point").val();
        var client_cat = $("#client_cat").val();
        var client_status = $("#client_status").val();
        var client_city = $("#client_city").val();

        if(client_owner == '' && client_company == '' && client_contact_point == '' && client_cat == '' && client_status == '' && client_city == '') {

            alert("Please enter field value.");
            return false;
        }
        else {

            $("#client_owner").val("");
            $("#client_company").val("");
            $("#client_contact_point").val("");
            $("#client_cat").val("");
            $("#client_status").val("");
            $("#client_city").val("");

            var url = '/client-search';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="text" name="client_owner" value="'+client_owner+'" />' +
            '<input type="text" name="client_company" value="'+client_company+'" />' +
            '<input type="text" name="client_contact_point" value="'+client_contact_point+'" />' +
            '<input type="text" name="client_cat" value="'+client_cat+'" />' +
            '<input type="text" name="client_status" value="'+client_status+'" />' +
            '<input type="text" name="client_city" value="'+client_city+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }
    }
</script>
@endsection