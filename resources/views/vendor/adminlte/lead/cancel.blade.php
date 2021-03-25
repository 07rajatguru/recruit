@extends('adminlte::page')

@section('title', 'Cancel Lead')

@section('content_header')

@stop

@section('content')
    
   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Cancel Lead ({{ $count or '0' }})</h2>
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

    <div class = "table-responsive">
       <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="lead_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Action</th>
                    <th>Company Name</th>
                    <th>Contact Point</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>City</th>
                    <th>Referred By</th>
                    <th>Website</th>
                    <th>Lead Status</th>
                </tr>
            </thead>
        </table>
   </div>
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $("#lead_table").DataTable({

                "bProcessing": true,
                "serverSide": true,
                "order" : [0,'desc'],
                "columnDefs": [{orderable: false, targets: [1]}],
                "ajax": {

                    url :"../lead/cancel/all",
                    type: "get",
                    error: function() {
                    }
                },
                "pageLength": 50,
                "responsive": true,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
        });
    </script>
@endsection