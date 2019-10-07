@extends('adminlte::page')

@section('title', 'Forbid Clients')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
                
            </div>
            <div  class="pull-left">
                <h2> Forbid Clients ({{ $count }}) </h2>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="clienttype_table">
        <thead>
            <tr>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <!-- <th>HR/Coordinator Name</th> -->
                <th>Contact Point</th>
                <?php if($isSuperAdmin || $isStrategy || $isAccountManager ) { ?>
                <th>Client Category</th>
                <?php }?>
                <th>Status</th>
                <!-- <th>Client Address</th> -->
                <th>City</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($client_array as $key => $client)
            <tr>
                <td>{{ Form::checkbox('client',$client['id'],null,array('class'=>'others_client' ,'id'=>$client['id'] )) }}</td>
                <td>

                    @if($isSuperAdmin || $isAdmin || $isStrategy || $client['client_visibility'] || $isAccountant)
                        <a title="Show" class="fa fa-circle"  href="{{ route('client.show',$client['id']) }}"></a>
                    @endif

                    {{-- Only Client Owner, Admin and Super admin have access to edit rights --}}
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

                    @if($isSuperAdmin || $client['client_owner'] )
                        <a title="Remarks" class="fa fa-plus"  href="{{ route('client.remarks',$client['id']) }}" style="margin:2px;"></a>
                    @endif

                    @if($isSuperAdmin)
                        <?php
                            $days_array = App\ClientTimeline::getDetailsByClientId($client['id']);
                        ?>
                        @include('adminlte::partials.client_timeline_view', ['data' => $client,'days_array' => $days_array])
                    @endif

                </td>

                <td>{{ $client['am_name'] }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['name'] }}</td>

                <td>{{ $client['hr_name'] }}</td>

                @if($isSuperAdmin || $isStrategy || $isAccountManager)
                    <td>{{ $client['category']}}</td>
                @endif
                
                @if($client['status']=='Forbid')
                    <td><span class="label label-sm label-default">{{$client['status']}} </span></td>
                @endif

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['address'] }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">

        jQuery( document ).ready(function() {
            var table = jQuery('#clienttype_table').DataTable( {
                responsive: true,
                "pageLength": 50,
                stateSave : true,
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

    </script>
@endsection