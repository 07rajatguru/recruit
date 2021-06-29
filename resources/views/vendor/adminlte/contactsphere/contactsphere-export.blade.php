<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="contactsphere_table" style="border: 2px solid #000000;">
	<thead>
		<tr style="background-color: #7598d9">
			<th style="border: 2px solid #000000;text-align: center;width: 7;">Sr.No</th>
			<th style="border: 2px solid #000000;text-align: center;width: 18;">Name</th>
			<th style="border: 2px solid #000000;text-align: center;width: 18;">Designation</th>
			<th style="border: 2px solid #000000;text-align: center;width: 18;">Company</th>
			<th style="border: 2px solid #000000;text-align: center;width: 20;">Contact Number</th>
			<th style="border: 2px solid #000000;text-align: center;width: 15;">City</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Referred By</th>
			<th style="border: 2px solid #000000;text-align: center;width: 50;">Official Email ID
			</th>
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Personal ID</th>
			
		</tr>
	</thead>
	@foreach($contacts_array as $key => $value)
	<?php $i = 0;?>
		<tbody>
			<tr>
				<td style="border: 1px solid #000000;text-align: center;">{{ ++$i }}</td>
				<td style="border: 1px solid #000000;">{{ $value['name'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['designation'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['company'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['contact_number'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['city'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['referred_by'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['official_email_id'] }}</td>
				<td style="border: 1px solid #000000;">{{ $value['personal_id'] }}</td>
			</tr>
		</tbody>
	@endforeach
</table>