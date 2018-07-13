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

    		<div class="box-body col-xs-3 col-sm-3 col-md-3">
    			<div class="form-group">
		        	{{Form::text('from_date',$from_date , array('id'=>'from_date', 'placeholder' => 'From Date', 'class'=>'form-control','autocomplete'=>'off'))}}
	        	</div>
    		</div>

    		<div class="box-body col-xs-3 col-sm-3 col-md-3">
    			<div class="form-group">
		        	{{Form::text('to_date',$to_date , array('id'=>'to_date', 'placeholder' => 'To Date', 'class'=>'form-control','autocomplete'=>'off'))}}
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
                <u><b><h1>No of CVs Associated in this week: {{ $associate_count or '0'}}</h1></b></u>
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
        	<?php $i=0;?>
        	<tbody>
        	@foreach($associate_weekly as $key => $value)
        		<tr>
        			<td>{{ ++$i }}</td>
        			<td>{{ date('l (jS F,y) ',strtotime($value['associate_date'])) }}</td>
        			<td>{{ $value['associate_candidate_count'] }}</td>
        		</tr>
        	@endforeach
        	</tbody>
        		<tr>
        			<td></td>
        			<td>Total Associated</td>
        			<td>{{ $associate_count or '0' }}</td>
        		</tr>
        		<tr>
        			<td></td>
        			<td>Benchmark</td>
        			<td>40</td>
        		</tr>
        		<tr>
        			<td></td>
        			<td>No. of resumes not achieved</td>
        			<td><?php if($associate_count<40):?>{{	$associate_count-40 }}<?php endif ?></td>
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
        		@foreach($interview_weekly as $key => $value)
        		<tr>
        			<td>{{ ++$i }}</td>
        			<td>{{ date('l (jS F,y) ',strtotime($value['interview_date'])) }}</td>
        			<td>{{ $value['interview_daily_count'] }}</td>
        		</tr>
        		@endforeach
        	</tbody>
        	<tr>
        		<td></td>
        		<td>Total</td>
        		<td>{{ $interview_count or '0'}}</td>
        	</tr>
        </table>
    </div>

    <table width="100%" cellspacing="0">
    	<tr>
            <td colspan="7">
                <u><b><h1>No of Leads added : {{ $lead_count or '0'}}</h1></b></u>
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

			$("#from_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
            //$('#date').datepicker().datepicker('setDate', 'today');

            $("#to_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
		});

		function select_data(){
			var users_id = $("#users_id").val();
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();

			var url = '/weekly-report';

			var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="users_id" value="'+users_id+'" />' +
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
		}
	</script>
@endsection