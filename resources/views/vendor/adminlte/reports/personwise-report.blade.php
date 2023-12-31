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
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
            	<strong>Select Financial Year:</strong>
                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
            </div>
        </div>

        @if($user_id == $superadmin || $user_id == $saloni_user_id)
	        <div class="box-body col-xs-2 col-sm-2 col-md-2">
	        	<div class="form-group">
	        		<strong>Select Team:</strong>
		            <select class="form-control" name="team_type" id="team_type">
		                @foreach($team_type as $key=>$value)
		                    <option value={{ $key }} @if($key==$selected_team_type) selected="selected" @endif>{{ $value}}</option>
		                @endforeach
		            </select>
		        </div>
	        </div>
	    @endif

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group" style="margin-top: 19px;">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>

        @permission(('display-person-wise-report-of-all-users'))
	        <div class="pull-right col-md-2">
	            <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()">Export</a>
	        </div>
	    @endpermission
    </div>
    <br/>

    <div class="col-xs-12 col-sm-12 col-md-12">
		<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report" style="border: 2px solid black;">
			<thead>
				<tr style="background-color: #7598d9">
					<th style="border: 2px solid black;text-align: center;">Sr.No</th>
					<th style="border: 2px solid black;text-align: center;">Candidate Name</th>
					<th style="border: 2px solid black;text-align: center;">Company Name</th>
					<th style="border: 2px solid black;text-align: center;">Position / Dept</th>
					<th style="border: 2px solid black;text-align: center;">Salary offered <br/> (fixed)</th>
					<th style="border: 2px solid black;text-align: center;">Billing</th>
					<th style="border: 2px solid black;text-align: center;">Joining Date</th>
					<th style="border: 2px solid black;text-align: center;">Efforts With</th>
					<th style="border: 2px solid black;text-align: center;">Status</th>
				</tr>
			</thead>
			<?php $j = 0;?>
			@if(isset($personwise_data) && sizeof($personwise_data) >0)
				@foreach($personwise_data as $key => $value)
				<?php $i = 0;?>
					<tbody>
						<tr>
							<td colspan="9" style="text-align: center;background-color: #FFFF66;border: 2px solid black;" class="button" data-id="{{ $j }}"><b>{{$key}}</b></td>
						</tr>
					</tbody>
					<tbody id="data_{{$j}}" style="display: none;">
						@if(isset($value) && sizeof($value) >0)
							@foreach($value as $k => $v)
								<tr>
									<td style="border: 1px solid black;text-align: center;">{{ ++$i }}</td>
									<td style="border: 1px solid black;">{{ $v['candidate_name'] }}</td>
									<td style="border: 1px solid black;">{{ $v['company_name'] }}</td>
									<td style="border: 1px solid black;">{{ $v['position'] }}</td>
									<td style="border: 1px solid black;">{{ $v['salary_offered'] }}</td>
									<td style="border: 1px solid black;">{{ $v['person_billing'] }}</td>
									<td style="border: 1px solid black;">{{ $v['joining_date'] }}</td>
									<td style="border: 1px solid black;">{{ $v['efforts'] }}</td>
									<td style="border: 1px solid black;"></td>
								</tr>
							@endforeach
							<tr>
								<td style="border: 1px solid black;text-align: center;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;text-align: center;"><b>Total</b></td>
								<td style="border: 1px solid black;">{{ $v['total_salary_offered'] }}</td>
								<td style="border: 1px solid black;">{{ $v['total_billing'] }}</td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
							</tr>
						@else
						<tr>
							<td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
							<td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
							<td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
							<td style="border: 1px solid black;"></td> <td style="border: 1px solid black;"></td>
							<td style="border: 1px solid black;"></td>
						</tr>
						@endif
					</tbody>
				<?php $j++;?>
				@endforeach
			@else
				<tbody>
					<tr>
						<td colspan="9" style="text-align: center;border: 2px solid black;" class="button">No Data Found.</td>
					</tr>
				</tbody>
			@endif
		</table>
	</div>
@stop

@section('customscripts')
<script type="text/javascript">
	
	$(document).ready(function() {
		
		$(".button").click(function() {

			var $toggle = $(this);
			var id = "#data_" + $toggle.data('id');
			$(id).toggle();
		});
	});

	function select_data() {
        
        var year = $("#year").val();
        var team_type = $("#team_type :selected").val();
        var app_url = "{!! env('APP_URL'); !!}";
        var url = app_url+'/personwise-report';

        var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="team_type" value="'+team_type+'" />' +
            '</form>');

        $('body').append(form);
        form.submit();
    }

    function export_data() {
        
        var year = $("#year").val();
        var team_type = $("#team_type :selected").val();
        var app_url = "{!! env('APP_URL'); !!}";
        var url = app_url+'/personwise-report/export';

        var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '<input type="hidden" name="team_type" value="'+team_type+'" />' +
            '</form>');

        $('body').append(form);
        form.submit();
    }
</script>
@endsection