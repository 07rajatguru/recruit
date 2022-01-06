@extends('adminlte::page')

@section('title', 'Monthwise Client List')

@section('content_header')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Client List ({{ $count or 0 }}) </h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}">Add New Client</a>
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
                <th>Client Owner</th>
                <th>Company Name</th>
                <th>Contact Point</th>
               
                @permission(('display-client-category-in-client-list'))
                    <th>Client Category</th>
                @endpermission
                    
                <th>Status</th>
                <th>City</th>
                <th>Industry</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ ++$i }}</td>

                @if(isset($client['second_line_am_name']) && $client['second_line_am_name'] != '')

                    <td>{{ $client['client_owner'] }} | {{ $client['second_line_am_name'] }}</td>
                @else

                    <td>{{ $client['client_owner'] }}</td>
                @endif

                <td>{{ $client['company_name'] or ''}}</td>
                <td>{{ $client['coordinator_name'] or ''}}</td>
                
                @permission(('display-client-category-in-client-list'))
                <td>{{ $client['client_category'] or ''}}</td>
                @endpermission

                @if($client['status'] == 'Active')
                    <td><span class="label label-sm label-success">{{ $client['status'] }}</span></td>
                @elseif($client['status'] == 'Passive')
                    <td><span class="label label-sm label-danger">{{ $client['status'] }}</span></td>
                @elseif($client['status'] == 'Leaders')
                    <td><span class="label label-sm label-primary">{{ $client['status'] }}</span></td>
                @elseif($client['status'] == 'Forbid')
                    <td><span class="label label-sm label-default">{{ $client['status'] }}</span></td>
                @elseif($client['status'] == 'Left')
                    <td><span class="label label-sm label-info">{{ $client['status'] }}</span></td>
                @endif

                <td>{{ $client['client_address'] or ''}}</td>
                <td>{{ $client['industry_name'] or ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('customscripts')
    <script type="text/javascript">

        jQuery(document).ready(function() {

            var table = jQuery('#client_table').DataTable({

                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0 },
                ],

                "autoWidth": false,
                "pageLength": 100
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection