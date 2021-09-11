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
            </tr>
        </thead>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery( document ).ready(function() {

            $("#client_table").DataTable({
                
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

            $("body").delegate(".from_date_class", "focusin", function () {

                $(this).datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    orientation: "bottom left",
                });
            });
       
            $("body").delegate(".to_date_class", "focusin", function () {

                $(this).datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    orientation: "bottom left",
                });
            });
        });

        function generateLastestHiringReport(client_id) {

            var from_date = '';
            var to_date = '';
            var page_nm = $("#page_nm").val();
            var source = $("#source").val();
            
            var url = '/send-hiring-report';

            var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="text" name="from_date" value="'+from_date+'" />' +
            '<input type="text" name="to_date" value="'+to_date+'" />' +
            '<input type="text" name="client_id" value="'+client_id+'" />' +
            '<input type="text" name="page_nm" value="'+page_nm+'" />' +
            '<input type="text" name="source" value="'+source+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }

        function generateHiringReport(client_id) {

            var from_date = $("#from_date_"+client_id).val();
            var to_date = $("#to_date_"+client_id).val();
            var page_nm = $("#page_nm").val();
            var source = $("#source").val();
            
            if(from_date == '' || to_date == '') {

                alert("Please enter date.");
                return false;
            }
            else {

                $("#from_date").val("");
                $("#to_date").val("");

                var url = '/send-hiring-report';

                var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '<input type="text" name="client_id" value="'+client_id+'" />' +
                '<input type="text" name="page_nm" value="'+page_nm+'" />' +
                '<input type="text" name="source" value="'+source+'" />' +
                '</form>');

                $('body').append(form);
                form.submit();
            }
        }
    </script>
@endsection