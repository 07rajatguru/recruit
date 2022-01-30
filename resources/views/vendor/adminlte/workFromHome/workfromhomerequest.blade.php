@extends('adminlte::page')

@section('title', 'Work From Home Request')

@section('content_header')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Work From Home Request ({{ $count }})</h2>
            </div>
        </div>
    </div>
    
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_from_home_table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">Action</th>
                <th width="11%">From Date</th>
                <th width="11%">To Date</th>
                <th width="13%">Username</th>
                <th>Subject</th>
                <th width="11%">Status</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @if(isset($work_from_home_res) && $work_from_home_res != '')
                @foreach ($work_from_home_res as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" href="{{ route('workfromhome.show',$value['id']) }}" title="Show"></a>
                        </td>

                        <td>{{ $value['from_date'] }}</td>
                        <td>{{ $value['to_date'] }}</td>
                        <td>{{ $value['user_name'] }}</td>
                        <td>{{ $value['subject'] }}</td>

                        @if($value['status'] == 0)
                            <td style="background-color:#8FB1D5;">Pending</td>
                        @elseif($value['status'] == 1)
                            <td style="background-color:#32CD32;">Approved</td>
                        @elseif($value['status'] == 2)
                            <td style="background-color:#F08080;">Rejected</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>        
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#work_from_home_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection