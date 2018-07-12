@extends('adminlte::page')

@section('title', 'Weekly Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Weekly Report</h2>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
        	<div class="box-body col-xs-4 col-sm-4 col-md-4">
        		<div class="form-group">
		        	{{Form::select('users_id',$users,$users_id, array('id'=>'users_id','class'=>'form-control'))}}
	        	</div>
    		</div>

    		<div class="box-body col-xs-4 col-sm-4 col-md-4">
    			<div class="form-group">
		        	{{Form::text('date',$date , array('id'=>'date', 'placeholder' => 'Date', 'class'=>'form-control','autocomplete'=>'off'))}}
	        	</div>
    		</div>

    		<div class="box-body col-xs-2 col-sm-2 col-md-2">
    			<div class="form-group">
    				{!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
    			</div>
    		</div>
    	</div>
    </div>

    <table width="100%" cellspacing="0">
    	<tr>
            <td colspan="7">
                <u><b><h1>No of CVs Associated in this week: {{ '0'}}</h1></b></u>
            </td>
        </tr>
    </table>
    <div class = "table-responsive">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="weekly_report_cv_table">
        	<thead>
        		<tr>
        			<th>Sr. No.</th>
        			<th>Day(Date)</th>
        			<th>No of resumes associated</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php $i=0;?>
        		<tr>
        			<td>{{++$i}}</td>
        			<td></td>
        			<td></td>
        		</tr>
        	</tbody>
        		<tr>
        			<td></td>
        			<td>Total Associated</td>
        			<td>{{ '0' }}</td>
        		</tr>
        		<tr>
        			<td></td>
        			<td>Benchmark</td>
        			<td>40</td>
        		</tr>
        		<tr>
        			<td></td>
        			<td>No. of resumes not achieved</td>
        			<td>{{ '0' }}</td>
        		</tr>
        </table>
    </div>

    <table width="100%" cellspacing="0">
    	<tr>
            <td colspan="7">
                <u><b><h1>No of Interview Scheduled : {{ $interview_count or '0'}}</h1></b></u>
            </td>
        </tr>
    </table>
    <div class = "table-responsive">
    	<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="weekly_report_interview_table">
        	<thead>
        		<tr>
        			<th>Sr. No.</th>
        			<th>Day(Date)</th>
        			<th>No of Interviews</th>
        		</tr>
        	</thead>
        	<?php $i=0;?>
        	<tbody>
        		<tr>
        			<td>{{ ++$i }}</td>
        			<td></td>
        			<td></td>
        		</tr>
        	</tbody>
        	<tr>
        		<td></td>
        		<td>Total</td>
        		<td>{{'0'}}</td>
        	</tr>
        </table>
    </div>

    <table width="100%" cellspacing="0">
    	<tr>
            <td colspan="7">
                <u><b><h1>No of Leads added : {{'0'}}</h1></b></u>
            </td>
        </tr>
    </table>

@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function(){
			var table = jQuery("#weekly_report_cv_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
			var table = jQuery("#weekly_report_interview_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
			new jQuery.fn.dataTable.FixedHeader( table );

			$("#users_id").select2();

			$("#date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
            //$('#date').datepicker().datepicker('setDate', 'today');
		});
	</script>
@endsection