@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
                <a class="btn btn-primary" href="{{ route('client.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div  class="pull-left">
                <h2> {{ $source }} Clients ({{ $count }}) </h2>
            </div>
            <div class="col-md-2">
                <a href="{{ route('client.active') }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Active Clients ({{ $active }})</div></a>
            </div>
            &nbsp;
            <div class="col-md-2">
                <a href="{{ route('client.passive') }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Passive Clients ({{ $passive }}) </div></a>
            </div>
        </div>
    </div>

    @if($isSuperAdmin || $isStrategy )
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="col-md-2">
                    <a href="{{ route('client.paramount') }}" style="text-decoration: none;color: black;"><div style="height:30px;background-color:#E9967A;font-weight: 600;border-radius: 20px;padding:6px 0px 0px 6px;text-align: center;">Paramount ({{ $para_cat }})</div></a>
                </div>
                &nbsp;
                <div class="col-md-2">
                    <a href="{{ route('client.moderate') }}" style="text-decoration: none;color: black;"><div style="height:30px;background-color:#D3D3D3;font-weight: 600;border-radius: 20px;padding:6px 0px 0px 6px;text-align: center;">Moderate ({{ $mode_cat }})</div></a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('client.standard') }}" style="text-decoration: none;color: black;"><div style="height:30px;background-color:#00CED1;font-weight: 600;border-radius: 20px;padding:6px 0px 0px 6px;text-align: center;">Standard ({{ $std_cat }})</div></a>
                </div>
            </div>
        </div>
    @endif
    <br/>
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
                <th>HR/Coordinator Name</th>
                <?php if($isSuperAdmin || $isStrategy ) { ?>
                <th>Client Category</th>
                <?php }?>
                <th>Status</th>
                <th>Client Address</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($client_array as $key => $client)
            <tr>
                <td>{{ Form::checkbox('client',$client['id'],null,array('class'=>'others_client' ,'id'=>$client['id'] )) }}</td>
                <td>

                    @if($isSuperAdmin || $isAdmin || $isStrategy || $client['client_visibility'])
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

                </td>

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
                "pageLength": 100,
                stateSave : true,
            });
        });

    </script>
@endsection