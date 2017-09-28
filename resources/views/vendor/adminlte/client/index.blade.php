@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Client List</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Client Name</th>
                <th>Client Owner</th>
                <th>Client Email</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
       <?php $i=0; ?>
        @foreach ($client_array as $key => $client)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $client['name'] }}</td>
                <td>{{ $client['am_name'] }}</td>
                <td>{{ $client['mail']}}</td>

                <td>

                    <?php if($isSuperAdmin || $isAdmin || $client['client_visibility']) { ?>
                        <a class="btn btn-info" href="{{ route('client.show',$client['id']) }}">Show</a>
                     <?php } ?>

                    {{-- Only Client Owner, Admin and Super admin have access to edit rights--}}
                    <?php if($isSuperAdmin || $isAdmin || $client['client_owner']) { ?>
                        <a class="btn btn-primary" href="{{ route('client.edit',$client['id']) }}">Edit</a>
                        @include('adminlte::partials.deleteModalNew', ['data' => $client, 'name' => 'client','display_name'=>'Client'])
                    <?php } ?>

                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#client_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
