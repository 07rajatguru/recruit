@extends('adminlte::page')

@section('title', 'Recovery Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Recovery Report</h2>
            </div>

            @permission(('display-recovery-report'))
                <div class="pull-right col-md-2">
                    <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()"> Export</a>
                </div>
            @endpermission
        </div>
    </div>

    <div class = "table-responsive">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="recovery_report_table">
        	<thead>
        	<tr>
            	<th>No</th>
            	<th>Candidate <br/>Name</th>
            	<th>Company <br/>Name</th>
            	<th>Position <br/>/Dept</th>
            	<th>Salary <br/> Offered <br/> (fixed)</th>
            	<th>Billing</th>
            	<th>Expected <br/>Payment <br/> (Billing <br/>*90/100)+ <br/> (Billing<br/>*18/100)</th>
            	<th>Joining <br/> Date</th>
            	<th>Efforts</th>
            	<th>Contact <br/>Person</th>
            	{{--<th>Remarks</th>--}}
		    </tr>
        	</thead>
        	<?php $i=0; ?>
        	<tbody>
        		@foreach($recovery_report as $recovery)
        		<tr>
        			<td>{{ ++$i }}</td>
        			<td>{{ $recovery['candidate_name'] or '' }}</td>
        			<td>{{ $recovery['company_name'] or '' }}</td>
        			<td>{{ $recovery['position'] or '' }}</td>
        			<td>{{ $recovery['salary_offered'] or '' }}</td>
        			<td>{{ $recovery['billing'] or '' }}</td>
        			<td>{{ $recovery['expected_payment'] or '' }}</td>
        			<td>{{ $recovery['joining_date'] or '' }}</td>
        			<td>{{ $recovery['efforts'] or '' }}</td>
        			<td>{{ $recovery['contact_person'] or '' }}</td>
        			{{--<td>{{ '' }}</td>--}}
        		</tr>
        		@endforeach
        	</tbody>
        </table>
    </div>

@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var table = jQuery('#recovery_report_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });

        function export_data() {
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/recoveryreport/export';

             var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '</form>');

            $('body').append(form);
            form.submit();

        }
    </script>
@endsection