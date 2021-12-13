@if(isset($list) && sizeof($list) > 0)
	<?php
	    $year_display = substr($year, -2);
	    $month_display = date('F', mktime(0, 0, 0, $month, 10));

	    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	?>
	<!DOCTYPE html>
	<html>
		<body>
			<table style="width: 100%;border: 5px solid #000000;">
				@if($days == '31')
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="36" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="34" style="text-align:center;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@elseif($days == '30')
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="35" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="33" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@else
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="33" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="31" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@endif
				<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
					<td style="border: 5px solid #000000;"></td>
					<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">NAME OF PERSON</th>
	            	<td style="width:17;text-align: center;border: 5px solid #000000;"><b>Department</b></td>
	            	<td style="width:17;text-align: center;border: 5px solid #000000;"><b>Working Hours</b></td>
	            	<td style="width:17;text-align: center;border: 5px solid #000000;"><b>Date of Joining</b></td>
	            
		            @foreach($list as $key => $value)
		                @foreach($value as $key1=>$value1)
		                    <?php
		                        $con_dt = date("j", mktime(0, 0, 0, 0, $key1, 0));
		                    ?>
		                    <td style="width: 5;border: 5px solid #000000;text-align: center;">
		                    <b>{{ $con_dt }}</b></td>
		                @endforeach
		                <?php break; ?>
		            @endforeach
				</tr>
				<?php $i=1; ?>
                @foreach($list as $key=>$value)
                    <tr style="font-family:Calibri;font-size: 12;text-align: center;border: 5px solid #000000;">
                       	<?php
                            $values_array = explode(",", $key);

                            $user_name = $values_array[0];
                            $new_user_name = str_replace("-"," ", $user_name);

                            $department = $values_array[1];
                            $joining_date = $values_array[3];

                            $working_hours = $values_array[2];
                            $working_hours = explode(':', $working_hours);
                        ?>
                        <td style="border: 5px solid #000000;">{{ $i }}</td>
                        <td style="border: 5px solid #000000;">{{ $new_user_name }}</td>

                        @if($department == 'Recruitment')
                         	<td style="background-color: #B0E0E6;border: 5px solid #000000;">{{ $department }}</td>
                        @elseif($department == 'HR Advisory')
                            <td style="background-color: #F08080;border: 5px solid #000000;">{{ $department }}</td>
                        @elseif($department == 'Operations')
                            <td style="background-color: #fff59a;border: 5px solid #000000;">{{ $department }}</td>
                        @else
                            <td style="background-color: #B1A0C7;border: 5px solid #000000;">{{ $department }}</td>
                        @endif

                        @if($working_hours[0] != '')
                            <td style="border: 5px solid #000000;">{{ $working_hours[0] }} Hours</td>
                        @else
                            <td></td>
                        @endif

                        @if(strpos($joining_date,'1970') !== false)
                            <td style="border: 5px solid #000000;"></td>
                        @else
                          	<td style="border: 5px solid #000000;">{{ $joining_date }}</td>
                        @endif

                        @foreach($value as $key1=>$value1)
                        	<?php
                            	$get_cur_dt = date('d');
                                $get_cur_month = date('m');
                                $get_cur_yr = date('Y');

                                if(in_array($key1, $sundays)) {
                                    $attendance = 'H';
                                }
                                else if(isset($value1['holiday']) && $value1['holiday'] == 'Y') {
                                    $attendance = 'H';
                                }
                                else if(($key1 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                    $attendance = 'N';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == '') {
                                    $attendance = 'A';
                                }
                                else {

                                    if(isset($value1['attendance'])) {
                                        $attendance = $value1['attendance'];
                                    }
                                    else {
                                        $attendance = '';
                                    }
                                }
                            ?>
                                            
                            @if(isset($value1['remarks']) && $value1['remarks'] != '')
                                @if($attendance == 'N')
                                    <td style="border: 5px solid #000000;background-color:#92D050;"></td>
                                @else
                                    <td style="border: 5px solid #000000;background-color:#92D050;">{{ $attendance }}</td>
                                @endif
                           @else
                                @if($attendance == 'H')
                                   <td style="border: 5px solid #000000;background-color:#ffc000;">{{ $attendance }}</td>
                                @elseif($attendance == 'F')
                                    <td style="border: 5px solid #000000;background-color:#d8d8d8;">P</td>
                                @elseif($attendance == 'N')
                                    <td style="border: 5px solid #000000;"></td>
                                @elseif($attendance == 'A')
                                    <td style="border: 5px solid #000000;background-color:#ff0000;">{{ $attendance }}</td>
                                @elseif($attendance == 'HD')
                                    <td style="border: 5px solid #000000;background-color:#d99594;">{{ $attendance }}</td>
                                @else
                                    <td style="border: 5px solid #000000;background-color:#ff0000;">A</td>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                <?php $i++; ?>
               	@endforeach
			</table>
		</body>
	</html>
@endif