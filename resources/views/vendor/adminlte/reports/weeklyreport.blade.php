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
		        	{{Form::select('users_id',$users,$user_id, array('id'=>'users_id','class'=>'form-control'))}}
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

    <div style="padding: 10px;">
        @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
            <table width="100%" cellspacing="0">
            	<tr>
                    <td colspan="7">
                        <u><b><h3>No of CVs Associated in this week: {{ $associate_count or '0'}}</h3></b></u>
                    </td>
                </tr>
            </table>
            <div class = "table-responsive">
            	<table class="table table-striped table-bordered nowrap" cellspacing="0" style="width:50%;" id="weekly_report_cv_table">
                	<thead>
                		<tr style="background-color: #f39c12;">
                			<th style="text-align: center;">Sr. No.</th>
                			<th style="text-align: center;">Day(Date)</th>
                			<th style="text-align: center;">No of resumes associated</th>
                		</tr>
                	</thead>
                	<?php $i=0;?>
                	<tbody>
                	@foreach($associate_weekly as $key => $value)
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
                		<td><?php if($associate_count<40):?>{{	$associate_count-40 }}<?php endif ?></td>
                	</tr>
                </table>
            </div>
        @endif

        @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')
            <table width="100%" cellspacing="0">
            	<tr>
                    <td colspan="7">
                        <u><b><h3>No of Interview Scheduled : {{ $interview_count or '0'}}</h3></b>
                        </u>
                    </td>
                </tr>
            </table>
            <div class = "table-responsive">
            	<table style="width:50%;" class="table table-striped table-bordered nowrap" cellspacing="0" id="weekly_report_interview_table">
                	<thead>
                		<tr style="background-color: #7598d9;">
                			<th style="text-align: center;">Sr. No.</th>
                			<th style="text-align: center;">Day(Date)</th>
                			<th style="text-align: center;">No of Interviews</th>
                		</tr>
                	</thead>
                	<?php $i=0;?>
                	<tbody>
                		@foreach($interview_weekly as $key => $value)
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
                		<td>{{ $interview_count or '0'}}</td>
                	</tr>
                </table>
            </div>
        @endif

        @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
            <table width="100%" cellspacing="0">
            	<tr>
                    <td colspan="7">
                        <u><b><h3>No of Leads Added : {{ $lead_count or '0'}}</h3></b></u>
                    </td>
                </tr>
            </table>

            <div class = "table-responsive">
                <table style="width:50%;" class="table table-striped table-bordered nowrap" cellspacing="0" id="weekly_report_leads_count_table">
                    <thead>
                        <tr style="background-color: #C4D79B;">
                            <th style="text-align: center;">Sr. No.</th>
                            <th style="text-align: center;">Day(Date)</th>
                            <th style="text-align: center;">No of Leads</th>
                        </tr>
                    </thead>
                    <?php $i=0;?>
                    <tbody>
                        @foreach($leads_weekly as $key => $value)
                        <tr style="text-align: center;">
                            <td>{{ ++$i }}</td>
                            <td>{{ date('l (jS F,y) ',strtotime($value['lead_date'])) }}</td>
                            <td>{{ $value['lead_count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tr style="text-align: center;">
                        <td></td>
                        <td>Total</td>
                        <td>{{ $lead_count or '0'}}</td>
                    </tr>
                </table>
            </div>

            <table width="100%" cellspacing="0">
                <tr>
                    <td colspan="7">
                        <u><b><h3>Lead Details : {{ $lead_count or '0'}}</h3></b></u>
                    </td>
                </tr>
            </table>

            <div class = "table-responsive">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" id="weekly_report_leads_table">
                    <thead>
                        <tr style="background-color: #C4D79B;">
                            <th style="text-align: center;">Sr. No.</th>
                            <th style="text-align: center;">Company Name</th>
                            <th style="text-align: center;">Contact Point</th>
                            <th style="text-align: center;">Designation</th>
                            <th style="text-align: center;">Email ID</th>
                            <th style="text-align: center;">Mobile No.</th>
                            <th style="text-align: center;">City</th>
                            <th style="text-align: center;">Website</th>
                            <th style="text-align: center;">Service</th>
                            <th style="text-align: center;">Lead Status</th>
                            <th style="text-align: center;">Source</th>
                        </tr>
                    </thead>
                    <?php $i=0;?>
                    <tbody>
                        @foreach($leads_weekly as $key => $value)
                            <tr style="text-align: center;">
                                <td>{{ ++$i }}</td>
                                <td>{{ $value['company_name'] }}</td>
                                <td>{{ $value['contact_point'] }}</td>
                                <td>{{ $value['designation'] }}</td>
                                <td>{{ $value['email'] }}</td>
                                <td>{{ $value['mobile'] }}</td>
                                <td>{{ $value['city'] }}</td>
                                <td>{{ $value['website'] }}</td>
                                <td>{{ $value['service'] }}</td>
                                <td>{{ $value['lead_status'] }}</td>
                                <td>{{ $value['source'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function(){

            $("#users_id").select2();

            $("#from_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
        
            $("#to_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
            
			/*var table = jQuery("#weekly_report_cv_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});*/
			var table = jQuery("#weekly_report_leads_table").DataTable({
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

		function select_data(){
			var users_id = $("#users_id").val();
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var date1 = new Date(from_date);
            var date2 = new Date(to_date);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            var total_days= diffDays+1;

            if(total_days<=7){
                var url = app_url+'/weekly-report';

                var form = $('<form action="' + url + '" method="post">' +
                    '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                    '<input type="text" name="users_id" value="'+users_id+'" />' +
                    '<input type="text" name="from_date" value="'+from_date+'" />' +
                    '<input type="text" name="to_date" value="'+to_date+'" />' +
                    '</form>');

                $('body').append(form);
                form.submit();
			}
			else if (total_days>7) {
                alert("Select 7 days date range");
			}
		}
	</script>
@endsection