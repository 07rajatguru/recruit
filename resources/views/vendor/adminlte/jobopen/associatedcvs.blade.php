@extends('adminlte::page')

@section('title', 'Associated CV')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Number of cvs associated this month ({{ $short_month_name }} , {{ $year }}) - {{$count}}</h2>
            </div>
        </div>
    </div>
    <div class = "table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
            <thead>
            <tr>
                <th>No</th>
                <th>MB</th>
                <th>Company Name</th>
                <th>Position Title</th>
                <th>Location</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
            </tr>
            </thead>
            <?php $i=0; ?>
            <tbody>
            @foreach($response as $key=>$value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['hm_name'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['company_name'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['location'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['candidate_name'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['candidate_owner_name'] or '' }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['candidate_email'] or '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = jQuery('#jo_table').DataTable( {
                responsive: true,
                /*"columnDefs": [
                    { "width": "10px", "targets": 0 },
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 }
                ],*/
                "pageLength": 100,
                stateSave: true
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection