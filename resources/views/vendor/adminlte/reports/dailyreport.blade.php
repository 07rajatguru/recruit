@extends('adminlte::page')

@section('title', 'Daily Report')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daily Report</h2>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
        	@if($loggedin_userid == $superadmin || $loggedin_userid == $saloni_user_id)
		        <div class="box-body col-xs-2 col-sm-2 col-md-2">
		        	<div class="form-group">
			            {{Form::select('team_type',$team_type,$selected_team_type, array('id'=>'team_type','class'=>'form-control', 'onchange' => 'teamWiseUser();'))}}
			        </div>
		        </div>
		    @endif
        	<div class="box-body col-xs-2 col-sm-5 col-md-2">
        		<div class="form-group">
		        	{{Form::select('users_id',$users,$user_id, array('id'=>'users_id','class'=>'form-control users_append'))}}
	        	</div>
    		</div>

    		<div class="box-body col-xs-3 col-sm-5 col-md-3">
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

	<div style="padding: 10px;">

		@if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
			<table width="100%" cellspacing="0">
				<tr>
					<td colspan="7">
						<u><b><h3>No of CVs Associated : {{ $associate_count or '0'}}</h3></b></u>
					</td>
				</tr>
			</table>

			<div class = "table-responsive">
				<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_cv_table">
					<thead>
					<tr style="background-color: #f39c12;">
						<th>No</th>
						<th>Date</th>
						<th>Position Name</th>
						<th>Company Name</th>
						<th>Location</th>
						<th>No of Resumes <br/>associted</th>
						<th>Status</th>
					</tr>
					</thead>
		            <?php $i = 0;?>
					@foreach($associate_daily as $key => $value)
						<tr>
							<td>{{++$i}}</td>
							<td>{{ date('jS F,y',strtotime($value['date'])) }}</td>
							<td>{{ $value['posting_title'] }}</td>
							<td>{{ $value['company'] }}</td>
							<td>{{ $value['location'] }}</td>
							<td>{{ $value['associate_candidate_count'] }}</td>
							<td>{{ $value['status'] }}</td>
						</tr>
					@endforeach
				</table>
			</div>
		@endif

		@if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')
			<table width="100%" cellspacing="0">
				<tr>
					<td colspan="7">
						<u><b><h3>No of Interview Scheduled : {{ $interview_count or '0'}}</h3></b></u>
					</td>
				</tr>
			</table>

			<div class = "table-responsive">
				<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_interview_table">
					<thead>
					<tr style="background-color: #7598d9">
						<th>No</th>
						<th>Position</th>
						<th>Position <br/> Location</th>
						<th>Name of the <br/> candidate</th>
						<th>Interview Date</th>
						<th>Interview Time</th>
						<th>Candidate <br/> Location</th>
						<th>Mode of  <br/> Interview</th>
						<th>Video ID</th>
						<th>Contact No.</th>
						<th>Email ID</th>
						<th>Confirmed</th>
						<th>Source</th>
					</tr>
					</thead>
	                <?php $i=0;?>
					@foreach($interview_daily as $key=>$value)
						<tr>
							<td>{{ ++$i }}</td>
							<td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] }}</td>
							<td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['interview_location'] }}</td>
							<td>{{ $value['cname'] }}</td>
							<td>{{ date('d/m/Y',strtotime($value['interview_date'])) }}</td>
							<td>{{ date('h:i A',strtotime($value['interview_time'])) }}</td>
							<td>{{ $value['ccity'] }}</td>
							<td>{{ $value['interview_type'] }}</td>
							<td>{{ '' }}</td>
							<td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['cmobile'] }}</td>
							<td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['cemail'] }}</td>
							<td>{{ 'Yes' }}</td>
							<td>{{ 'Adler' }}</td>
						</tr>
					@endforeach
				</table>
			</div>
		@endif

		@if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
			<table width="100%" cellspacing="0">
				<tr>
					<td colspan="7">
						<u><b><h3>No of Leads Added : {{$lead_count or '0'}}</h3></b></u>
					</td>
				</tr>
			</table>

			<div class = "table-responsive">
				<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="daily_report_leads_table">
					<thead>
					<tr style="background-color: #C4D79B">
						<th>No</th>
						<th>Company Name</th>
						<th>Contact Point</th>
						<th>Designation</th>
						<th>Email ID</th>
						<th>Mobile No.</th>
						<th>City</th>
						<th>Website</th>
						<th>Service</th>
						<th>Lead Status</th>
						<th>Source</th>
					</tr>
					</thead>
	                <?php $i=0;?>
					@foreach($leads_daily as $key=>$value)
						<tr>
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
				</table>
			</div>
		@endif
	</div>

	<input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" name="selected_user_id" id="selected_user_id" value="{{ $user_id }}">
@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function(){

			$("#users_id").select2();

			$("#date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });

			var table1 = jQuery("#daily_report_cv_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
			var table2 = jQuery("#daily_report_interview_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
			var table3 = jQuery("#daily_report_leads_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});

			if ( ! table1.data().any() ) {
			}
			else{
				new jQuery.fn.dataTable.FixedHeader( table1 );
			}

			if ( ! table2.data().any() ) {
			}
			else{
				new jQuery.fn.dataTable.FixedHeader( table2 );
			}

			if ( ! table3.data().any() ) {
			}
			else{
				new jQuery.fn.dataTable.FixedHeader( table3 );
			}
			
            teamWiseUser();
		});

        function select_data(){

            var users_id = $("#users_id").val();
            var date = $("#date").val();
            var team_type = $("#team_type :selected").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/daily-report';

            if (users_id > 0) {
	            var form = $('<form action="' + url + '" method="post">' +
	                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
	                '<input type="hidden" name="users_id" value="'+users_id+'" />' +
	                '<input type="hidden" name="team_type" value="'+team_type+'" />' +
	                '<input type="hidden" name="date" value="'+date+'" />' +
	                '</form>');

	            $('body').append(form);
	            form.submit();
	        } 
	        else {
	        	alert("Please Select User");
	        }
        }

        function teamWiseUser() {
            
            var token = $('input[name="csrf_token"]').val();
            var team = $("#team_type").val();
            var app_url = "{!! env('APP_URL'); !!}";
            var selected_user_id = $("#selected_user_id").val();

            $.ajax({
                type: 'POST',
                url: app_url+'/team-wise-uses',
                data:{'team': team,'selected_user_id': selected_user_id,'_token':token},
                dataType: 'html',
                success: function (res) {
                    $(".users_append").html('');
                    $(".users_append").append(res);
                    $("#users_id").select2();
                },
            });
        }
	</script>
@endsection