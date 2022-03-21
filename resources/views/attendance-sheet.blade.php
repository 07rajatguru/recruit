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
		                <td colspan="48" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="46" style="text-align:center;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@elseif($days == '30')
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="47" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="45" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@elseif($days == '29')
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="46" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="42" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@else
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
		                <td colspan="45" style="text-align:center;border: 5px solid #000000;"><b>Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</b></td>
					</tr>
					<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">
						<td rowspan="2" style="text-align:center;width:10;border: 5px solid #000000;">
							<b>Sr.No</b></td>
	                	<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">ADLER EMPLOYEES</td>
	                	<td colspan="42" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;"><b>DATE</b></td>
					</tr>
				@endif

				<tr style="font-family:Calibri;font-size: 12;border: 5px solid #000000;">

					<td style="border: 5px solid #000000;"></td>
					<th style="background-color:#d6e3bc;text-align:center;width:35;border: 5px solid #000000;">NAME OF PERSON</th>
	            	<td style="width:17;text-align: center;border: 5px solid #000000;background-color:#d6e3bc;"><b>Department</b></td>
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

		            <th style="border: 5px solid #000000;text-align: center;width: 10;">
		            Present</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">H</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">PH</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">SL</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">PL</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">HD</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">HD</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">UL</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 6;">AB</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 10;">Days</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 12;">Total Leave</th>
                    <th style="border: 5px solid #000000;text-align: center;width: 12;">Total Days</th>
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

                            $present = 0;$week_off = 0;$ph = 0;
                            $pl = 0;$sl = 0;$ul = 0;
                            $half_day = 0;$half_day_actual = 0;$absent = 0;
                            $days =0;$total_leaves =0;$total_days = 0;
                        ?>

                        <td style="border: 5px solid #000000;">{{ $i }}</td>
                        <td style="border: 5px solid #000000;">{{ $new_user_name }}</td>

                        @if($department == 'Recruitment')
                         	<td style="background-color: #F2DBDB;border: 5px solid #000000;">{{ $department }}</td>
                        @elseif($department == 'HR Advisory')
                            <td style="background-color: #DBE5F1;border: 5px solid #000000;">{{ $department }}</td>
                        @elseif($department == 'Operations')
                            <td style="background-color: #EAF1DD;border: 5px solid #000000;">{{ $department }}</td>
                        @else
                            <td style="background-color: #B1A0C7;border: 5px solid #000000;">{{ $department }}</td>
                        @endif

                        @if($working_hours[0] != '')
                            <td style="border: 5px solid #000000;">{{ $working_hours[0] }} Hours</td>
                        @else
                            <td style="border: 5px solid #000000;"></td>
                        @endif

                        @if(strpos($joining_date,'1970') !== false)
                            <td style="border: 5px solid #000000;"></td>
                        @else
                          	<td style="border: 5px solid #000000;">{{ $joining_date }}</td>
                        @endif

                        <?php $jj=0; $kk=0; ?>

                        @foreach($value as $key1=>$value1)
                        	<?php

                                $kk++;
                            	$get_cur_dt = date('d');
                                $get_cur_month = date('m');
                                $get_cur_yr = date('Y');

                                $joining_date_array = explode('/', $joining_date);

                                if($key1 < $joining_date_array[0] && $joining_date_array[1] == $month && $year <= $joining_date_array[2]) {
                                    $attendance = 'O';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WPP') {
                                    $attendance = 'WPP';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'HD') {
                                    $attendance = 'HD';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHHD') {
                                    $attendance = 'WFHHD';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'HDR') {
                                    $attendance = 'HDR';
                                }
                                else if(isset($value1['privilege_leave']) && $value1['privilege_leave'] == 'Y') {
                                    $attendance = 'PL';
                                }
                                else if(isset($value1['sick_leave']) && $value1['sick_leave'] == 'Y') {
                                    $attendance = 'SL';
                                }
                                else if(isset($value1['unapproved_leave']) && $value1['unapproved_leave'] == 'Y') {
                                    $attendance = 'UL';
                                }
                                else if(isset($value1['fixed_holiday']) && $value1['fixed_holiday'] == 'Y') {
                                    $attendance = 'PH';
                                }
                                else if(isset($value1['optional_holiday']) && $value1['optional_holiday'] == 'Y') {
                                    $attendance = 'OH';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'H') {

                                    $kk = $kk-1;

                                    if($kk==$jj) {
                                        $attendance = 'A';
                                        $jj++;
                                        $kk++;
                                    }
                                    else {
                                        $attendance = 'H';
                                        $jj=0;
                                        $kk=0;
                                    }
                                }
                                else if(($key1 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                    $attendance = 'N';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'A') {

                                    $attendance = 'A';
                                    $jj++;
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == '') {
                                    
                                    $attendance = 'A';
                                    $jj++;
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'P') {
                                    $attendance = 'P';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHP') {
                                    $attendance = 'WFHP';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'FR') {
                                    $attendance = 'FR';
                                }
                                else if(isset($value1['attendance']) && $value1['attendance'] == 'WFHR') {
                                    $attendance = 'WFHR';
                                }
                                else {
                                    $attendance = 'N';
                                }
                            ?>
                            
                            @if($attendance == 'N' || $attendance == 'O')
                                <td style="border: 5px solid #000000;text-align: center;"></td>
                            @elseif($attendance == 'WPP')
                                <td style="border: 5px solid #000000;background-color:#8FB1D5;" title="Pending Work Planning"></td>
                            @elseif($attendance == 'HD')
                                <?php 
                                    $half_day++; 
                                    $half_day_actual = $half_day / 2;
                                ?>
                                <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Day">HD</td>
                            @elseif($attendance == 'HDR')
                                <?php 
                                    $half_day++; 
                                    $half_day_actual = $half_day / 2;
                                ?>
                                <td style="border: 5px solid #000000;background-color:#d99594;color: #FFFF00;" title="Half Day Rejection">HD</td>
                            @elseif($attendance == 'WFHHD')
                                <?php 
                                    $half_day++; 
                                    $half_day_actual = $half_day / 2;
                                ?>
                                <td style="border: 5px solid #000000;background-color:#d99594;color: #0000FF;" title="Half Day Work From Home">HD</td>
                            @elseif($attendance == 'PL')
                                <?php $pl++; ?>
                                <td style="border: 5px solid #000000;background-color:#8db3e2;" title="Privilege Leave">PL</td>
                            @elseif($attendance == 'SL')
                                <?php $sl++; ?>
                                <td style="border: 5px solid #000000;background-color:#c075f8;" title="Sick Leave">SL</td>
                            @elseif($attendance == 'UL')
                                <?php $ul++; ?>
                                <td style="border: 5px solid #000000;background-color:#fac090;" title="Unapproved Leave">UL</td>
                            @elseif($attendance == 'PH')
                                <?php $ph++; ?>
                                <td style="border: 5px solid #000000;background-color:#76933C;" title="Paid Holiday">PH</td>
                            @elseif($attendance == 'OH')
                                <?php $ph++; ?>
                                <td style="border: 5px solid #000000;background-color:#76933C;" title="Paid Holiday">OH</td>
                            @elseif($attendance == 'H')
                                <?php $week_off++; ?>
                                <td style="border: 5px solid #000000;background-color:#ffc000;"  title="Sunday">H</td>
                            @elseif($attendance == 'P')
                            	<?php $present++; ?>
                                <td style="border: 5px solid #000000;background-color:#d8d8d8;" title="Present">P</td>
                            @elseif($attendance == 'WFHP')
                                <?php $present++; ?>
                                <td style="border: 5px solid #000000;background-color:#d8d8d8;color: #0000FF;" title="Work From Home">P</td>
                            @elseif($attendance == 'A')
                            	<?php $absent++; ?>
                                <td style="border: 5px solid #000000;background-color:#ff0000;" title="Absent">A</td>
                            @elseif($attendance == 'FR')
                                <?php $absent++; ?>
                                <td style="border: 5px solid #000000;background-color:#ff0000;color: #FFFF00;" title="Full Day Rejection">A</td>
                            @elseif($attendance == 'WFHR')
                                <?php $present++; ?>
                                <td style="border: 5px solid #000000;background-color:#ff0000;color: #0000FF;" title="Work From Home Request Reject">P</td>
                            @endif
                        @endforeach

                        <?php

                            $days = $present + $week_off + $ph + $half_day_actual - $ul;
                            $total_leaves = $sl + $pl;
                            $total_days = $sl + $days + $total_leaves;
                        ?>

                        <td style="border: 5px solid #000000;text-align:center;">{{ $present }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $week_off }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $ph }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $sl }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $pl }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $half_day }}</td>
                        <td style="border: 1px solid black;text-align:center;"> 
                        {{ $half_day_actual }}</td>
                        <td style="border: 5px solid #000000;text-align:center;"> {{ $ul }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $absent }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $days }}</td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $total_leaves }}
                        </td>
                        <td style="border: 5px solid #000000;text-align:center;">{{ $total_days }}</td>
                    </tr>
                <?php $i++; ?>
               	@endforeach
			</table>
		</body>
	</html>
@endif