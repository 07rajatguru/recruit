<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="leavebalance_table" style="border: 2px solid #000000;">
	<thead>
		<tr style="background-color: #7598d9;border: 2px solid #000000;text-align: center;">
			<th style="width: 10;">Sr.No</th>
			<th style="width: 30;">User Name</th>
			<th style="width: 12;">Total PL</th>
			<th style="width: 12;">Opted PL</th>
			<th style="width: 12;">PL Balance</th>
			<th style="width: 12;">Total SL</th>
			<th style="width: 12;">Opted SL</th>
			<th style="width: 12;">SL Balance</th>
			<th style="width: 30;">Edited By</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0;?>
		@if(isset($balance_array) && sizeof($balance_array) > 0)
			@foreach($balance_array as $key => $value)
				@if($value['edited_by'] != '')
					<tr style="background-color:#ACACAC;border: 1px solid #000000;text-align: center;">
						<td>{{ ++$i }}</td>
						<td>{{ $value['user_name'] }}</td>
						<td>{{ $value['pl_total'] }}</td>
						<td>{{ $value['pl_taken'] }}</td>
						<td>{{ $value['pl_remaining'] }}</td>
						<td>{{ $value['sl_total'] }}</td>
						<td>{{ $value['sl_taken'] }}</td>
						<td>{{ $value['sl_remaining'] }}</td>
						<td>{{ $value['edited_by'] }}</td>
					</tr>
				@else
					<tr style="border: 1px solid #000000;text-align: center;">
						<td>{{ ++$i }}</td>
						<td>{{ $value['user_name'] }}</td>
						<td>{{ $value['pl_total'] }}</td>
						<td>{{ $value['pl_taken'] }}</td>
						<td>{{ $value['pl_remaining'] }}</td>
						<td>{{ $value['sl_total'] }}</td>
						<td>{{ $value['sl_taken'] }}</td>
						<td>{{ $value['sl_remaining'] }}</td>
						<td>{{ $value['edited_by'] }}</td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>