

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report" style="border: 3 solid #000000;">
	<thead>
		<tr style="background-color: #7598d9">
			<th style="border: 3 solid #000000;text-align: center;width: 10;">Sr.No</th>
			<th style="border: 3 solid #000000;text-align: center;width: 25;">Candidate Name</th>
			<th style="border: 3 solid #000000;text-align: center;width: 30;">Company Name</th>
			<th style="border: 3 solid #000000;text-align: center;width: 20;">Position / Dept</th>
			<th style="border: 3 solid #000000;text-align: center;width: 15;">Salary offered <br/> (fixed)</th>
			<th style="border: 3 solid #000000;text-align: center;width: 10;">Billing</th>
			<th style="border: 3 solid #000000;text-align: center;width: 11;">GST @18%</th>
			<th style="border: 3 solid #000000;text-align: center;width: 15;">Invoice Raised</th>
			<th style="border: 3 solid #000000;text-align: center;width: 15;">Payment Expected <br/>(Incl. GST)</th>
			<th style="border: 3 solid #000000;text-align: center;width: 13;">Joining Date</th>
			<th style="border: 3 solid #000000;text-align: center;width: 20;">Contact Person</th>
			<th style="border: 3 solid #000000;text-align: center;width: 15;">Location</th>
			<th style="border: 3 solid #000000;text-align: center;width: 30;">Efforts</th>
		</tr>
	</thead>
	@foreach($monthwise_data as $key => $value)
	<?php $i = 0;?>
		<tbody>
			<tr>
				<td colspan="13" style="text-align: center;background-color: #FFFF00;border: 3 solid #000000;"><b>{{$key}}</b></td>
			</tr>
			@if(isset($value) && sizeof($value) >0)
				@foreach($value as $k => $v)
					<tr>
						<td style="border: 1 solid #000000;text-align: center;">{{ ++$i }}</td>
						<td style="border: 1 solid #000000;">{{ $v['candidate_name'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['company_name'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['position'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['salary_offered'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['billing'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['gst'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['invoice_raised'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['payment'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['joining_date'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['client_name'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['location'] }}</td>
						<td style="border: 1 solid #000000;">{{ $v['efforts'] }}</td>
					</tr>
				@endforeach
				<tr>
					<td style="border: 1px solid black;text-align: center;"></td>
					<td style="border: 1px solid black;"></td>
					<td style="border: 1px solid black;"></td>
					<td style="border: 1px solid black;text-align: center;"><b>Total</b></td>
					<td style="border: 1px solid black;">{{ $v['total_salary_offered'] }}</td>
					<td style="border: 1px solid black;">{{ $v['total_monthwise_billing'] }}</td>
					<td style="border: 1px solid black;">{{ $v['total_monthwise_gst'] }}</td>
					<td style="border: 1px solid black;">{{  $v['total_monthwise_invoice_raised'] }}</td>
					<td style="border: 1px solid black;">{{ $v['total_monthwise_payment'] }}</td>
					<td style="border: 1px solid black;"></td>
					<td style="border: 1px solid black;"></td>
					<td style="border: 1px solid black;"></td>
					<td style="border: 1px solid black;"></td>
				</tr>
			@else
			<tr>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> <td style="border: 1 solid #000000;"></td>
				<td style="border: 1 solid #000000;"></td> 
			</tr>
			@endif
		</tbody>
	@endforeach
</table>