@extends('adminlte::page')

@section('title', 'Clients')

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
                <h2>Clients List ({{ $count or 0 }}) </h2>
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
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <th>Contact Point</th>

                @permission(('display-client-category-in-client-list'))
                    <th>Client Category</th>
                @endpermission

                <th>Status</th>
                <th>City</th>
                <th>Remarks</th>
            </tr>
        </thead>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">

        jQuery( document ).ready(function() {

            $("#client_table").dataTable({
                
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]}],
                "ajax" : {
                    'url' : '/allbyam',
                    'type' : 'get',
                    error: function() {
                    }
                },
                responsive: true,
                "pageLength": 25,
                "pagingType": "full_numbers",
            });
        });
    </script>
@endsection