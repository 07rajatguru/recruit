@extends('adminlte::page')

@section('title', 'Monthly Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            	<h2>Monthly Report</h2>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
        	<div class="box-body col-xs-4 col-sm-4 col-md-4">
        		<div class="form-group">
		        	{{Form::select('users_id',$users,$user_id, array('id'=>'users_id','class'=>'form-control'))}}
	        	</div>
    		</div>
    	
        	<div class="box-body col-xs-3 col-sm-3 col-md-3">
        		<div class="form-group">
		        	{{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
	        	</div>
    		</div>

        	<div class="box-body col-xs-3 col-sm-3 col-md-3">
        		<div class="form-group">
		        	{{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
	        	</div>
    		</div>

    		<div class="box-body col-xs-2 col-sm-2 col-md-2">
    			<div class="form-group">
    				{!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
    			</div>
    		</div>

    	</div>
    </div>

    <div style="padding: 10px;">
        <table width="100%" cellspacing="0">
        	<tr>
                <td colspan="7">
                    <u><b><h3>No of CVs Associated in this month: {{ $associate_count or '0'}}</h3></b></u>
                </td>
            </tr>
        </table>
        <div class = "table-responsive">
        	<table class="table table-striped table-bordered nowrap" cellspacing="0" style="width:50%;" id="weekly_report_cv_table">
            	<thead>
            		<tr style="background-color: #C4D79B;">
            			<th style="text-align: center;">Sr. No.</th>
            			<th style="text-align: center;">Day(Date)</th>
            			<th style="text-align: center;">No of resumes associated</th>
            		</tr>
            	</thead>
            	<?php $i=0;?>
            	<tbody>
            		@foreach($associate_monthly as $key => $value)
            		<tr style="text-align: center;">
            			<td>{{ ++$i }}</td>
            			<td>{{ date('l (jS F,y) ',strtotime($value['associate_date'])) }}</td>
            			<td>{{ $value['associate_candidate_count'] }}</td>
            		</tr>
            		@endforeach
            	</tbody>
            	<tr style="text-align: center;">
            			<td></td>
            			<td>Total Associated</td>
            			<td>{{ $associate_count or '0' }}</td>
            		</tr>
            		<tr style="text-align: center;">
            			<td></td>
            			<td>Benchmark</td>
            			<td>40</td>
            		</tr>
            		<tr style="text-align: center;">
            			<td></td>
            			<td>No. of resumes not achieved</td>
            			<td></td>
            		</tr>
            </table>
        </div>

        <table width="100%" cellspacing="0">
        	<tr>
                <td colspan="7">
                    <u><b><h3>No of Interview Attended : {{ $interview_count or '0'}}</h3></b></u>
                </td>
            </tr>
        </table>
        <div class = "table-responsive">
        	<table  style="width:50%;" class="table table-striped table-bordered nowrap" cellspacing="0"  id="weekly_report_interview_table">
            	<thead>
            		<tr style="background-color: #C4D79B;">
            			<th style="text-align: center;">Sr. No.</th>
            			<th style="text-align: center;">Day(Date)</th>
            			<th style="text-align: center;">No of Interviews</th>
            		</tr>
            	</thead>
            	<?php $i=0;?>
            	<tbody>
            		@foreach($interview_monthly as $key => $value)
            		<tr style="text-align: center;">
            			<td>{{ ++$i }}</td>
            			<td>{{ date('l (jS F,y) ',strtotime($value['interview_date'])) }}</td>
            			<td>{{ $value['interview_daily_count'] }}</td>
            		</tr>
            		@endforeach
            	</tbody>
            	<tr style="text-align: center;">
            		<td></td>
            		<td>Total</td>
            		<td>{{ $interview_count or '0' }}</td>
            	</tr>
            </table>
        </div>

        <table width="100%" cellspacing="0">
        	<tr>
                <td colspan="7">
                    <u><b><h3>No of Leads added : {{ $lead_count or '0'}}</h3></b></u>
                </td>
            </tr>
        </table>
    </div>

@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#users_id").select2();
		});

		function select_data(){
			var users_id = $("#users_id").val();
			var month = $("#month").val();
			var year = $("#year").val();
            var app_url = "{!! env('APP_URL'); !!}";

			var url = app_url+'/monthly-report';

			var form = $('<form action="'+url+ '" method="post">' +
					'<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' + 
					'<input type="text" name="users_id" value="'+users_id+'" />' + 
					'<input type="text" name="month" value="'+month+'" />' + 
					'<input type="text" name="year" value="'+year+'" />' + 
					'</form>'); 

			$('body').append(form);
            form.submit();
		}
	</script>
@endsection