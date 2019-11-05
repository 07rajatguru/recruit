@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate List <span id="candidate_count">({{ $count or 0 }})</span></h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
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
                <th>Action</th>
                <th>Candidate Name</th>
                <!-- <th>Candidate Owner</th> -->
                <th>Candidate Email</th>
                <th>Mobile Number</th>
                <th>Current Employer</th>
                <th>Current Job Title</th>
                <th>Current Salary</th>
                <th>Expected Salary</th>
                <th>Functional Roles</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
       
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            $("#candidate_table").dataTable({
                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},
                                ],
                "ajax":{
                    url :"applicant-candidate/all",
                    type: "get", 
                    error: function(){
                    },
                },
                "pageLength": 50,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
        });
    </script>
@endsection