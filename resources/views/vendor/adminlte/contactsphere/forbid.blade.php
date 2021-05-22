@extends('adminlte::page')

@section('title', 'Forbid Contacts')

@section('content_header')

@stop

@section('content')
    
   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Forbid Contacts ({{ $count or '0' }})</h2>
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
       <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="contactsphere_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Company</th>
                    <th>Contact Number</th>
                    <th>City</th>
                    <th>Referred By</th>
                    <th>Official Email ID</th>
                    <th>Personal ID</th>
                    <th>Convert Lead</th>
                </tr>
            </thead>
        </table>
   </div>
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $("#contactsphere_table").DataTable({

                "bProcessing": true,
                "serverSide": true,
                "order" : [0,'desc'],
                "columnDefs": [{orderable: false, targets: [1]}],
                "ajax": {

                    url :"../contactsphere/forbid/all",
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