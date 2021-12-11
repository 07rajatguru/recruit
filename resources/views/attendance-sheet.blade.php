@if(isset($list) && sizeof($list) > 0)
	<?php
	    $year_display = substr($year, -2);
	    $month_display = date('F', mktime(0, 0, 0, $month, 10));
	?>
	<!DOCTYPE html>
	<html>
		<body>
			<table style="width: 100%;">
				<tr>
	                <td colspan="38" style="border: 1px solid black;text-align:center;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
				</tr>
				<tr>
					<td rowspan="2" style="border: 1px solid black;text-align:center;width:10;">
						<b>Sr.No</b></td>
                	<th colspan="3" style="border:1px solid black;background-color:#d6e3bc;text-align:center;">ADLER EMPLOYEES</td>
                	<td colspan="34" style="border: 1px solid black;text-align:center;"><b>DATE</b></td>
				</tr>
				<tr>
					<td></td>
					<th colspan="3" style="border:1px solid black;background-color:#d6e3bc;text-align:center;">NAME OF PERSON</th>
	            	<td style="border: 1px solid black;width:17;text-align: center;"><b>Department</b></td>
	            	<td style="border: 1px solid black;width:17;text-align: center;"><b>Working Hours</b></td>
	            	<td style="border: 1px solid black;width:17;text-align: center;"><b>Date of Joining</b></td>
	            
		            @foreach($list as $key => $value)
		                @foreach($value as $key1=>$value1)
		                    <?php
		                        $con_dt = date("j", mktime(0, 0, 0, 0, $key1, 0));
		                    ?>
		                    <td style="border: 1px solid black;width: 1px;width: 4;"><b>{{ $con_dt }}</b></td>
		                @endforeach
		            @endforeach
				</tr>
			</table>
		</body>
	</html>
@endif