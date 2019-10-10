@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div class="row">
        <div class="col-md-12 margin-tb">
            <div class="pull-right">
                @if($isSuperAdmin)
                    {{--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchmodal" onclick="client_emails_notification()">Send Mail</button>--}}

                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#accountmanagermodal" onclick="client_account_manager()">Change Account Manager</button>
                @endif
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
            </div>
            <div>

            </div>
            <div  class="pull-left">
                <h2>Client List ({{ $count }}) </h2>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="col-md-2 col-sm-4">
                <a href="{{ route('client.active') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Active({{ $active }})</div></a>
            </div>

            <div class="col-md-2 col-sm-4">
                <a href="{{ route('client.passive') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Passive({{ $passive }}) </div></a>
            </div>

            <div class="col-md-2 col-sm-4">
                <a href="{{ route('client.leaders') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#337ab7;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Leaders({{ $leaders }})</div></a>
            </div>

            <!-- <div class="col-md-2 col-sm-4">
                <a href="{{ route('client.forbid') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#777;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Forbid({{ $forbid }}) </div></a>
            </div> -->

            <div class="col-md-2 col-sm-4">
                <a href="{{ route('client.left') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Left({{ $left }}) </div>
                </a>
            </div>
        </div>
    </div>

    <br/>
    @if($isSuperAdmin || $isStrategy )
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2 col-sm-4">
                    <a href="{{ route('client.paramount') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#E9967A;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Paramount ({{ $para_cat }})</div></a>
                </div>
                <div class="col-md-2 col-sm-4">
                    <a href="{{ route('client.moderate') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#D3D3D3;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Moderate ({{ $mode_cat }})</div></a>
                </div>
                <div class="col-md-2 col-sm-4">
                    <a href="{{ route('client.standard') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#00CED1;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Standard ({{ $std_cat }})</div></a>
                </div>
            </div>
        </div>

    @endif

    <br>

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
 
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_table">
        <thead>
            <tr>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <!-- <th>HR/Coordinator Name</th> -->
                <th>Contact Point</th>
                
                <?php if($isSuperAdmin || $isStrategy || $isAccountManager) { ?>
                <th>Client Category</th>
                <?php }?>

                <th>Status</th>
                <!-- <th>Client Address</th> -->
                <th>City</th>
                <th>Remarks</th>
            </tr>
        </thead>
        {{--<tbody>
      
        @foreach ($client_array as $key => $client)
            <tr>
                <td>{{ Form::checkbox('client',$client['id'],null,array('class'=>'others_client' ,'id'=>$client['id'] )) }}</td>

                <td>{{ $client['am_name'] }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['name'] }}</td>

                <td>{{ $client['hr_name'] }}</td>

                @if($isSuperAdmin || $isStrategy )
                    <td>{{ $client['category']}}</td>
                @endif

                @if($client['status']=='Active')
                    <td ><span class="label label-sm label-success"> {{ $client['status'] }}</span></td>
                @else
                    <td ><span class="label label-sm label-danger">{{$client['status']}} </span></td>
                @endif

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['address'] }}</td>
                <td>

                    @if($isSuperAdmin || $isAdmin || $isStrategy || $client['client_visibility'])
                        <a title="Show" class="fa fa-circle"  href="{{ route('client.show',$client['id']) }}"></a>
                    @endif

                    {{-- Only Client Owner, Admin and Super admin have access to edit rights
                    @if($isSuperAdmin || $isAdmin || $client['client_owner'])
                        <a title="Edit" class="fa fa-edit" href="{{ route('client.edit',$client['id']) }}"></a>
                    @endif

                    @if($isSuperAdmin)
                    @include('adminlte::partials.deleteModalNew', ['data' => $client, 'name' => 'client','display_name'=>'Client'])
                        @if(isset($client['url']) && $client['url']!='')
                            <a target="_blank" href="{{$client['url']}}"><i  class="fa fa-fw fa-download"></i></a>
                        @endif
                    @endif

                    @if($isSuperAdmin || $isStrategy )
                        @include('adminlte::partials.client_account_manager', ['data' => $client, 'name' => 'client','display_name'=>'More Information'])
                    @endif

                </td>

            </tr>
        @endforeach
        </tbody>--}}
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
                    <div class="clt_email_cls">

                        <div class="form-group">
                            <strong>Select Email Template : <span class = "required_fields">*</span></strong><br/><br/>
                            {!! Form::select('email_template_id',$email_template_names,null, array('id'=> 'email_template_id','class' => 'form-control','onchange' => 'setEmailTemplate()')) !!}
                        </div>

                    </div>
                    <div class="email_error"></div>
                    <div class="body_class" style="display:none;"></div>
                </div>
                <input type="hidden" name="email_client_ids" id="email_client_ids" value="">
                

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="email_submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
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

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">

@stop

@section('customscripts')

<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">
    jQuery( document ).ready(function()
    {
        $("#account_manager_id").select2({width : '567px'});
        $("#email_template_id").select2({width : "567px"});

        /*var table = jQuery('#client_table').DataTable( {
                    responsive: true,
                    "pageLength": 100,
                    stateSave : true,
                    /*"aoColumnDefs": [
                        { "bSearchable": false, "aTargets": [ 7 ]}
                    ]
                     new jQuery.fn.dataTable.FixedHeader( table );
            } );*/

        $("#client_table").dataTable({
            'bProcessing' : true,
            'serverSide' : true,
            "order" : [1,'desc'],
            "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}
                        ],
            "ajax" : {
                'url' : 'client/all',
                'type' : 'get',
                error: function(){

                }
            },
            responsive: true,
            "pageLength": 25,
            "pagingType": "full_numbers",
            //stateSave : true,
        });

        $('#allcb').change(function()
        {
            if($(this).prop('checked')){
                $('tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', true);
                });
            }else{
                $('tbody tr td input[type="checkbox"]').each(function(){
                    $(this).prop('checked', false);
                });
            }
        });
        $('.others_client').change(function()
        {
            if ($(this).prop('checked')) {
                if ($('.others_client:checked').length == $('.others_client').length) {
                    $("#allcb").prop('checked', true);
                }
            }
            else{
                $("#allcb").prop('checked', false);
            }
        });
    });

function client_emails_notification()
{
    var token = $('input[name="csrf_token"]').val();
    var app_url = "{!! env('APP_URL'); !!}";
    var client_ids = new Array();

    var table = $("#client_table").dataTable();
    
    table.$("input:checkbox[name=client]:checked").each(function(){
        client_ids.push($(this).val());
    });

    $("#email_client_ids").val(client_ids);

    $.ajax(
    {
        type : 'POST',
        url : app_url+'/client/checkClientId',
        data : {client_ids : client_ids, '_token':token},
        dataType : 'json',
        success: function(msg)
        {
            $(".email_modal").show();
            if (msg.success == 'Success') {
                $(".clt_email_cls").show();
                $(".email_error").empty();
                $('#email_submit').removeAttr('disabled');
                setEmailTemplate();
            }
            else{
                $(".clt_email_cls").hide();
                $(".email_error").empty();
                $('#email_submit').attr('disabled','disabled');
                $(".email_error").append(msg.err);
            }
        }
    });
}

function client_account_manager()
{
    var token = $('input[name="csrf_token"]').val();
    var app_url = "{!! env('APP_URL'); !!}";
    var client_ids = new Array();

    var table = $("#client_table").dataTable();

    table.$("input:checkbox[name=client]:checked").each(function(){
        client_ids.push($(this).val());
    });

    $("#client_ids").val(client_ids);

    $.ajax(
    {
        type : 'POST',
        url : app_url+'/client/checkClientId',
        data : {client_ids : client_ids, '_token':token},
        dataType : 'json',
        success: function(msg)
        {
            $(".acc_mngr_modal").show();
            if (msg.success == 'Success') {
                $(".ac_mngr_cls").show();
                $(".act_mngr_error").empty();
                $('#submit').removeAttr('disabled');
            }
            else{
                $(".ac_mngr_cls").hide();
                $(".act_mngr_error").empty();
                $('#submit').attr('disabled','disabled');
                $(".act_mngr_error").append(msg.err);
            }
        }
    });
}

function setEmailTemplate()
{
    var token = $('input[name="csrf_token"]').val();
    var app_url = "{!! env('APP_URL'); !!}";
    var email_template_id = $("#email_template_id").val();

    $(".body_class").html('');

    $.ajax(
    {
        type : 'GET',
        url : app_url+'/email-template/getDetailsById',
        data : {email_template_id : email_template_id, '_token':token},
        dataType : 'json',

        success: function(data)
        {
           $(".body_class").html('');

            var html = '';
            html += '<br/><div>';
            html += '<table class="table table-bordered">';
            html += '<tr>';
            html += '<td><b>&nbsp;Template &nbsp;&nbsp; Name</b></td>';
            html += '<td><input type="text" class="form-control" name="template_nm" id="template_nm" value="'+data.name+'" style="width:460px;"/></td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td><b>&nbsp;Subject</b></td>';
            html += '<td><input type="text" class="form-control" name="email_subject" id="email_subject" value="'+data.subject+'" style="width:460px;"/></td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td><b>&nbsp;&nbsp; Body &nbsp; &nbsp; &nbsp; Message</b></td>';
            html += '<td><textarea name="email_body" id="email_body" style="width:460px;">'+data.email_body+'</textarea></td>';
            html += '</tr>';
            html += '</table>';
            html += '</div>';

            $(".body_class").show();
            $(".body_class").append(html);

            CKEDITOR.replace( 'email_body', 
            {
                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js',
                height: '100px',
            });

            CKEDITOR.on('dialogDefinition', function( ev )
            {
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
    });
}
</script>
@endsection
