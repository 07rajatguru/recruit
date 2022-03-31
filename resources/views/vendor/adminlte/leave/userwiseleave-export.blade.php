<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="leavebalance_table" style="border: 2px solid #000000;">
	<thead>
		<tr style="background-color: #7598d9">
			<th style="border: 2px solid #000000;text-align: center;width: 10;">Sr.No</th>
			<th style="border: 2px solid #000000;text-align: center;width: 30;">User Name</th>
			<th style="border: 2px solid #000000;text-align: center;width: 12;">Total PL</th>
			<th style="border: 2px solid #000000;text-align: center;width: 12;">Opted PL</th>
			<th style="border: 2px solid #000000;text-align: center;width: 11;">PL Balance</th>
			<th style="border: 2px solid #000000;text-align: center;width: 12;">Total SL</th>
			<th style="border: 2px solid #000000;text-align: center;width: 12;">Opted SL</th>
			<th style="border: 2px solid #000000;text-align: center;width: 11;">SL Balance</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0;?>
		@if(isset($balance_array) && sizeof($balance_array) > 0)
			@foreach($balance_array as $key => $value)
				<tr>
					<td style="border: 1px solid #000000;text-align: center;">{{ ++$i }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['user_name'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['pl_total'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['pl_taken'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['pl_remaining'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['sl_total'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['sl_taken'] }}</td>
					<td style="border: 1px solid #000000;text-align: center;">{{ $value['sl_remaining'] }}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>