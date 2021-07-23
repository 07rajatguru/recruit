<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="clientwise-report" style="border: 2 solid #000000;">
	<thead>
		<tr style="background-color: #7598d9">
			<th style="border: 2px solid #000000;text-align: center;width: 7;">Sr.No</th>
			<th style="border: 2px solid #000000;text-align: center;width: 18;">Candidate Name</th>
			<th style="border: 2px solid #000000;text-align: center;width: 15;">Client Owner</th>
			<th style="border: 2px solid #000000;text-align: center;width: 20;">Position / Dept</th>
			<th style="border: 2px solid #000000;text-align: center;width: 20;">Salary offered <br/> (fixed)</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Billing</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">GST <br/> @ 18%</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Invoice</th>
			<th style="border: 2px solid #000000;text-align: center;width: 15;">Joining Date</th>
			<th style="border: 2px solid #000000;text-align: center;width: 50;">Efforts With</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Contact Person</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Status</th>
		</tr>
	</thead>

	@if(isset($clientwise_data) && sizeof($clientwise_data) > 0)
		@foreach($clientwise_data as $key => $value)
		<?php $i = 0;?>
			<tbody>
				<tr>
					<td colspan="12" style="text-align: center;background-color: #F7D358;border: 2px solid #000000;"><b>{{$key}}</b></td>
				</tr>
				@if(isset($value) && sizeof($value) >0)
					@foreach($value as $k => $v)
						<tr>
							<td style="border: 1px solid #000000;text-align: center;">{{ ++$i }}</td>
							<td style="border: 1px solid #000000;">{{ $v['candidate_name'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['owner_name'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['position'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['salary_offered'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['billing'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['gst'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['invoice'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['joining_date'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['efforts'] }}</td>
							<td style="border: 1px solid #000000;">{{ $v['coordinator_name'] }}</td>
							<td style="border: 1px solid #000000;"></td>
						</tr>
					@endforeach
				@else
				<tr>
					<td style="border: 1px solid #000000;"></td> <td style="border: 1px solid #000000;"></td>
					<td style="border: 1px solid #000000;"></td> <td style="border: 1px solid #000000;"></td>
					<td style="border: 1px solid #000000;"></td> <td style="border: 1px solid #000000;"></td>
					<td style="border: 1px solid #000000;"></td> <td style="border: 1px solid #000000;"></td>
					<td style="border: 1px solid #000000;"></td> <td style="border: 1px solid #000000;"></td>
					<td style="border: 1px solid #000000;"></td>
				</tr>
				@endif
			</tbody>
		@endforeach
	@else
		<tbody>
			<tr>
				<td colspan="12" style="text-align: center;border: 2px solid black;" class="button">No Data Found.</td>
			</tr>
		</tbody>
	@endif
</table>