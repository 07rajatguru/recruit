@if(isset($bench_mark) && sizeof($bench_mark) > 0)
	<!DOCTYPE html>
	<html>
		<body>
			<table style="width: 40%;">
				<tr>
					<td colspan="11" style="border: 5px solid #000000;background: #46bdc6;font-weight: bold;font-size: 28;text-align: center;color: #000000;" height="30" width="50">
						<?php 
		                    $full_year =  $bench_mark['year'];
		                    $year_display = substr($full_year, -2);
		                    $month_display = date('F', mktime(0, 0, 0, $bench_mark['month'], 10));

		                    // For Set Limegreen & Red Color
		                    $date = date('l');
		                    $from_date_default = date('Y-m-d',strtotime("$date monday this week"));
		                    $to_date_default = date('Y-m-d',strtotime("$from_date_default +6days"));
		                 ?>
		                Productivity Report - {{ $month_display }}' {{ $year_display }}
					</td>
				</tr>
				<tr>
					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">Sr. No.</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">Particular</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Minimum % / Benchmark</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Standard Numbers / Monthly</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Standard Numbers / Weekly</th>

					<th rowspan="2" colspan="5" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Actual Weekly Numbers
					</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"></th>
				</tr>
				<tr>
					<td colspan="5" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"></td>

                    <td style="text-align: center;color: #990000;border: 5px solid #000000;background: #bfbfbf;font-weight: bold;">Monthly Achievement</td>
				</tr>
			</table>
		</body>
	</html>
@endif