@extends('adminlte::page')

@section('title', 'Person wise Report')

@section('content_header')
    <h1></h1>
@stop

@section('content')

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Person Wise Report</h2>
            </div>
        </div>
    </div>

    <div class = "table-responsive">
		<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report">
			<thead>
				<tr style="background-color: #7598d9">
					<th>Sr.No</th>
					<th>Candidate Name</th>
					<th>Company Name</th>
					<th>Position / Dept</th>
					<th>Salary offered <br/> (fixed)</th>
					<th>Billing</th>
					<th>Joining Date</th>
					<th>Efforts With</th>
					<th>Status</th>
				</tr>
			</thead>
		</table>
	</div>

@stop

@section('customscripts')
<script type="text/javascript">
	var table = jQuery("#personwise-report").DataTable({
		responsive: true,
		"pageLength": 100,
		stateSave: true
	});
	new jQuery.fn.dataTable.FixedHeader( table );
</script>
@endsection