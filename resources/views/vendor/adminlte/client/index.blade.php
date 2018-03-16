@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Client List ({{ $count }})</h2>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
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
                <th>Client Phone No.</th>
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
                <td>{{ $client['mobile'] }}</td>

                <td>

                    <?php if($isSuperAdmin || $isAdmin || $client['client_visibility']) { ?>
                        <a title="Show" class="fa fa-circle"  href="{{ route('client.show',$client['id']) }}"></a>
                     <?php } ?>

                    {{-- Only Client Owner, Admin and Super admin have access to edit rights--}}
                    <?php if($isSuperAdmin || $isAdmin || $client['client_owner']) { ?>
                        <a title="Edit" class="fa fa-edit" href="{{ route('client.edit',$client['id']) }}"></a>
                    <?php  }?>

                    <?php if($isSuperAdmin) { ?>
                    @include('adminlte::partials.deleteModalNew', ['data' => $client, 'name' => 'client','display_name'=>'Client'])
                        @if(isset($client['url']) && $client['url']!='')
                            <a target="_blank" href="{{$client['url']}}"><i  class="fa fa-fw fa-download"></i></a>
                        @endif
                    <?php  }?>

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
                responsive: true,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
