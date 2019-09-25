@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div class="row">
        <div class="col-md-12 margin-tb">
            <div class="pull-right">
                @if($isSuperAdmin || $isStrategy )
                    <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#accountmanagermodal" onclick="client_account_manager()">Change Account Manager
                    </button>
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

<div class="modal fade searchmodal" id="searchmodal" aria-labelledby="searchmodal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Send Mail To Clients</h4>
            </div>
            <div class="modal-body">
                <p>
                    Are You Sure You Want to Send Mail To Clients ?
                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="client_emails_notification()">
                Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
         
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Account Manager Modal Popup -->

<div id="accountmanagermodal" class="modal text-left fade priority" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Account Manager</h4>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => 'client.accountmanager']) !!}
                <div class="modal-body">
                    <div class="ac_mngr_cls">
                        <strong>Select Account Manager :</strong> <br><br>
                        {!! Form::select('account_manager_id', $all_account_manager,null, array('id'=>'account_manager_id','class' => 'form-control')) !!}
                    </div>
                    <div class="error"></div>
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
    <script type="text/javascript">
      jQuery( document ).ready(function() {

        $("#account_manager_id").select2({width : "550px"});
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

            $('#allcb').change(function(){
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
            $('.others_client').change(function() {
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
    var client_ids = new Array();
    var token = $("#token").val();
    var table = $("#client_table").dataTable();

    table.$("input:checkbox[name=client]:checked").each(function(){
        client_ids.push($(this).val());
    });

    var url = '/client/emailnotification';

    if(client_ids.length > 0){
        var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="'+token+'" />' +
            '<input type="text" name="client_ids" value="'+client_ids+'" />' +
            '</form>');

        $('body').append(form);
        form.submit();
    }        
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
            $(".priority").show();
            if (msg.success == 'Success') {
                $(".ac_mngr_cls").show();
                $(".error").empty();
                $('#submit').removeAttr('disabled');
            }
            else{
                $(".ac_mngr_cls").hide();
                $(".error").empty();
                $('#submit').attr('disabled','disabled');
                $(".error").append(msg.err);
            }
        }
    });
}
</script>
@endsection
