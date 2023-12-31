@extends('adminlte::page')

@section('title', 'Forbidden Clients')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}">Add New Client</a>
            </div>
            <div  class="pull-left">
                <h2>Forbidden Clients ({{ $count or 0 }}) </h2>
            </div>
        </div>
    </div>

    <input type="hidden" name="source" id="source" value="Forbid">

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
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <th>Contact Point</th>

                @permission(('display-client-category-in-client-list'))
                    <th>Client Category</th>
                @endpermission

                <th>Status</th>
                <th>City</th>
                <th>Industry</th>
                <th>New AM</th>
            </tr>
        </thead>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">

        jQuery( document ).ready(function() {

            var source = $("#source").val();
            var numCols = $('#clienttype_table thead th').length;

            var client_owner = '';
            var client_company = '';
            var client_contact_point = '';
            var client_cat = '';
            var client_status = '';
            var client_city = '';
            var client_industry = '';

            $("#clienttype_table").dataTable({
                
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1] },{orderable: false, targets: [6]}],
                "ajax" : {
                    'url' : '/client/allbytype',
                    "data" : {
                        "source" : source,
                        "client_owner": client_owner,
                        "client_company"  : client_company,
                        "client_contact_point"  : client_contact_point,
                        "client_cat"  : client_cat,
                        "client_status"  : client_status,
                        "client_city"  : client_city,
                        "client_industry"  : client_industry,
                    },
                    'type' : 'get',
                    error: function() {
                    }
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

            var table = $('#clienttype_table').DataTable();
            
            if(numCols == 10) {
                table.columns( [9] ).visible( false );
            }
            else {
                table.columns( [8] ).visible( false );
            }
        });
    </script>
@endsection