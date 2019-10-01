@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                 <h2>Client List ({{ $count }}) </h2>
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


    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <!-- <th>HR/Coordinator Name</th> -->
                <th>Contact Point</th>
               
                <?php if($isSuperAdmin || $isStrategy) { ?>
                <th>Client Category</th>
                <?php }?>
                    
                <th>Status</th>
                <!-- <th>Client Address</th> -->
                <th>City</th>
            </tr>
        </thead>
         <?php $i=0; ?>
        <tbody>
        @foreach ($clients as $client)

            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $client['client_owner'] or ''}}</td>
                <td>{{ $client['company_name'] or ''}}</td>
                <td>{{ $client['coordinator_name'] or ''}}</td>
                
                <?php if($isSuperAdmin || $isStrategy) { ?>
                <td>{{ $client['client_category'] or ''}}</td>
                <?php }?>

                @if($client['status']=='Active')
                    <td><span class="label label-sm label-success">{{ $client['status'] }}</span></td>
                @elseif($client['status']=='Passive')
                    <td><span class="label label-sm label-danger">{{ $client['status'] }}</span></td>
                @elseif($client['status']=='Leaders')
                    <td><span class="label label-sm label-primary">{{ $client['status'] }}</span></td>
                @elseif($client['status']=='Forbid')
                    <td><span class="label label-sm label-default">{{ $client['status'] }}</span></td>
                @elseif($client['status']=='Left')
                    <td><span class="label label-sm label-info">{{ $client['status'] }}</span></td>
                @endif

                <td>{{ $client['client_address'] or ''}}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
@endsection
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#client_table').DataTable( {
                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0 },
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 }
                ],

                "autoWidth": false,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection