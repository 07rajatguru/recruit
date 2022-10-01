@if(isset($new_list) && sizeof($new_list) > 0)
    <?php
        $year_display = substr($year, -2);
        $month_display = date('F', mktime(0, 0, 0, $month, 10));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    ?>
    <!DOCTYPE html>
    <html>
    	<body>
            <table style="width: 100%;border: 5px solid #000000;">
                <tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
                        
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#d8d8d8;">"P" - Present</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#ff0000;">"A" - Absent</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#ffc000;">"H" - Holiday</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#8db3e2;">"PL" - Paid Leave</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#c075f8;">"SL" - Sick Leave</td>
                    
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    
                    @if($days == '31')
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                    @endif

                    @if($days == '30')
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                    @endif

                    @if($days == '29')
                        <td style="border: 5px solid #000000;"></td>
                    @endif
                </tr>
                <tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">

                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#d99594;">"HD" - Half Day</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="4" style="text-align:center;border: 5px solid #000000;background-color:#76933C;">"PH" - Paid Holiday</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="6" style="text-align:center;border: 5px solid #000000;background-color:#fac090;">"UL" - Unapproved Leave</td>
                    <td style="border: 5px solid #000000;"></td>

                    <td colspan="6" style="text-align:center;border: 5px solid #000000;background-color:#eedc82;">"CO+" - Compensatory Off</td>

                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>
                    <td style="border: 5px solid #000000;"></td>

                    @if($days == '31')
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                    @endif

                    @if($days == '30')
                        <td style="border: 5px solid #000000;"></td>
                        <td style="border: 5px solid #000000;"></td>
                    @endif

                    @if($days == '29')
                        <td style="border: 5px solid #000000;"></td>
                    @endif
                </tr>
            </table>
            @foreach($new_list as $key => $value)
    			<table style="width: 100%;border: 5px solid #000000;">
    				@if($days == '31')
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    		                <th colspan="48" style="text-align:center;border: 5px solid #000000;">{{ $key }} - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
    					</tr>
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    						<th rowspan="2" style="text-align:center;width:7;border: 5px solid #000000;">
    						Sr. No.</th>
    	                	<th style="background-color:#d6e3bc;text-align:center;width:33;border: 5px solid #000000;">ADLER EMPLOYEES</th>
    	                	<th colspan="46" style="text-align:center;border: 5px solid #000000;">DATE</th>
    					</tr>
    				@elseif($days == '30')
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    		                <th colspan="47" style="text-align:center;border: 5px solid #000000;">{{ $key }} - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
    					</tr>
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    						<th rowspan="2" style="text-align:center;width:7;border: 5px solid #000000;">
    						Sr.No</th>
    	                	<th style="background-color:#d6e3bc;text-align:center;width:33;border: 5px solid #000000;">ADLER EMPLOYEES</th>
    	                	<th colspan="45" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;">DATE</th>
    					</tr>
    				@elseif($days == '29')
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    		                <th colspan="46" style="text-align:center;border: 5px solid #000000;">{{ $key }} - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
    					</tr>
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    						<th rowspan="2" style="text-align:center;width:7;border: 5px solid #000000;">
    						Sr.No</th>
    	                	<th style="background-color:#d6e3bc;text-align:center;width:33;border: 5px solid #000000;">ADLER EMPLOYEES</th>
    	                	<th colspan="44" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;">DATE</th>
    					</tr>
    				@else
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    		                <th colspan="45" style="text-align:center;border: 5px solid #000000;">{{ $key }} - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
    					</tr>
    					<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">
    						<th rowspan="2" style="text-align:center;width:7;border: 5px solid #000000;">
    						Sr.No</th>
    	                	<th style="background-color:#d6e3bc;text-align:center;width:33;border: 5px solid #000000;">ADLER EMPLOYEES</th>
    	                	<th colspan="43" style="text-align:center;border: 5px solid #000000;border: 5px solid #000000;">DATE</th>
    					</tr>
    				@endif

    				<tr style="font-family:Calibri;font-size: 11;border: 5px solid #000000;">

    					<td style="border: 5px solid #000000;"></td>
    					<th style="background-color:#d6e3bc;text-align:center;width:33;border: 5px solid #000000;">NAME OF PERSON</th>
    	            	<th style="width:17;text-align: center;border: 5px solid #000000;background-color:#d6e3bc;">Department</th>
                        <th style="width:20;text-align: center;border: 5px solid #000000;">Employment Type</th>
    	            	<th style="width:17;text-align: center;border: 5px solid #000000;">Working Hours
                        </th>
    	            	<th style="width:17;text-align: center;border: 5px solid #000000;">Date of Joining
                        </th>
    	            
                        @if(isset($list) && sizeof($list)>0)
        		            @foreach($list as $list_key => $list_value)
        		                @foreach($list_value as $list_key1 => $list_value1)
        		                    <?php
        		                        $con_dt = date("j", mktime(0, 0, 0, 0, $list_key1, 0));
        		                    ?>
        		                    <td style="width: 5;border: 5px solid #000000;text-align: center;">
        		                    <b>{{ $con_dt }}</b></td>
        		                @endforeach
        		                <?php break; ?>
        		            @endforeach
                        @endif

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
                        <th style="border: 5px solid #000000;text-align: center;width: 12;">Total Leave
                        </th>
                        <th style="border: 5px solid #000000;text-align: center;width: 12;">Total Days
                        </th>
    				</tr>
    				<?php $i=1; ?>
                    @foreach($value as $key1 => $value1)
                        <tr style="font-family:Calibri;font-size: 11;text-align: center;border: 5px solid #000000;">
                           	<?php
                                $values_array = explode(",", $key1);

                                $user_name = $values_array[0];
                                $new_user_name = str_replace("-"," ", $user_name);

                                $department = $values_array[1];
                                $joining_date = $values_array[4];

                                if($values_array[3] != '') {

                                    $working_hours = $values_array[3];
                                    $working_hours = explode(':', $working_hours);
                                }
                                else {
                                    $working_hours = '';
                                }

                                if($values_array[2] != '') {
                                    $employment_type = $values_array[2];
                                }
                                else {
                                    $employment_type = '';
                                }

                                $present = 0;$week_off = 0;$ph = 0;
                                $pl = 0;$sl = 0;$ul = 0;
                                $half_day = 0;$half_day_actual = 0;$absent = 0;
                                $days =0;$total_leaves =0;$total_days = 0;
                            ?>

                            <td style="border: 5px solid #000000;background-color: #fac090;">{{ $i }}</td>
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

                            <td style="border: 5px solid #000000;">{{ $employment_type }}</td>

                            @if($working_hours != '')
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

                            @foreach($value1 as $key2 => $value2)
                            	<?php

                                    $kk++;
                                	$get_cur_dt = date('d');
                                    $get_cur_month = date('m');
                                    $get_cur_yr = date('Y');
                                    
                                    $sunday_date = $key2."-".$month."-".$year;   
                                    $today_day = date('l',strtotime($sunday_date));

                                    // September Fixed Date
                                    $fixed_date = '10-9-2022';

                                    $joining_date_array = explode('/', $joining_date);

                                    if($key2 < $joining_date_array[0] && $joining_date_array[1] == $month && $year <= $joining_date_array[2]) {
                                        $attendance = 'O';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'CO') {
                                        $attendance = 'CO';
                                    }
                                    else if($working_hours == '') {
                                        $attendance = 'B';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'A') {

                                        $attendance = 'A';
                                        $jj++;
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WPP') {
                                        $attendance = 'WPP';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                        $attendance = 'HDPL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                        $attendance = 'HDPL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                        $attendance = 'HDSL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                        $attendance = 'HDSL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HD') {
                                        $attendance = 'HD';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD' && isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                        $attendance = 'WFHHDPL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD' && isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                        $attendance = 'WFHHDSL';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHHD') {
                                        $attendance = 'WFHHD';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'HDR') {
                                        $attendance = 'HDR';
                                    }
                                    else if(isset($value2['privilege_leave']) && $value2['privilege_leave'] == 'Y') {
                                        $attendance = 'PL';
                                    }
                                    else if(isset($value2['sick_leave']) && $value2['sick_leave'] == 'Y') {
                                        $attendance = 'SL';
                                    }
                                    else if(isset($value2['unapproved_leave']) && $value2['unapproved_leave'] == 'Y') {
                                        $attendance = 'UL';
                                    }
                                    else if((isset($value2['fixed_holiday']) && $value2['fixed_holiday'] == 'Y') || $fixed_date == $sunday_date) {
                                        $attendance = 'PH';
                                    }
                                    else if(isset($value2['optional_holiday']) && $value2['optional_holiday'] == 'Y') {
                                        $attendance = 'OH';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'H') {

                                        $kk = $kk-1;

                                        if($kk == $jj) {

                                            if($today_day == 'Sunday') {
                                                $attendance = 'H';
                                                $jj=0;
                                                $kk=0;
                                            }
                                            else {
                                                $attendance = 'A';
                                                $jj++;
                                                $kk++;
                                            }
                                        }
                                        else {
                                            $attendance = 'H';
                                            $jj=0;
                                            $kk=0;
                                        }
                                    } // For third saturday
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'TS') {

                                        $attendance = 'TS';
                                    }
                                    else if($key2 == $get_cur_dt && $month == $get_cur_month) {

                                        $attendance = 'WPP';
                                    }
                                    else if(($key2 > $get_cur_dt && $get_cur_month == $month && $get_cur_yr == $year) || ($year > $get_cur_yr) || ($month > $get_cur_month && $get_cur_yr == $year)) {
                                        $attendance = 'N';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == '') {
                                        
                                        $attendance = 'A';
                                        $jj++;
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'P') {
                                        $attendance = 'P';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHP') {
                                        $attendance = 'WFHP';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'FR') {
                                        $attendance = 'FR';
                                    }
                                    else if(isset($value2['attendance']) && $value2['attendance'] == 'WFHR') {
                                        $attendance = 'WFHR';
                                    }
                                    else {
                                        $attendance = 'N';
                                    }
                                ?>
                                
                                @if($attendance == 'N' || $attendance == 'O' || $attendance == 'B')
                                    <td style="border: 5px solid #000000;text-align: center;"></td>

                                @elseif($attendance == 'WPP')
                                    <td style="border: 5px solid #000000;background-color:#8FB1D5;" title="Pending Work Planning"></td>

                                @elseif($attendance == 'HDPL')
                                    <?php 
                                        $half_day++;
                                        $half_day_actual = $half_day / 2;
                                        $pl = $pl + 0.5;
                                    ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Day Privilege Leave">HD</td>

                                @elseif($attendance == 'WFHHDPL')
                                    <?php 
                                        $half_day++;
                                        $half_day_actual = $half_day / 2;
                                        $pl = $pl + 0.5;
                                    ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;color: #0000FF;" title="Half Day Privilege Leave (Work From Home)">HD</td>

                                @elseif($attendance == 'HDSL')
                                    <?php 
                                        $half_day++;
                                        $half_day_actual = $half_day / 2;
                                        $sl = $sl + 0.5;
                                    ?>
                                <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Day Sick Leave">HD</td>

                                @elseif($attendance == 'WFHHDSL')
                                    <?php 
                                        $half_day++;
                                        $half_day_actual = $half_day / 2;
                                        $sl = $sl + 0.5;
                                    ?>
                                <td style="border: 5px solid #000000;background-color:#d99594;color: #0000FF;" title="Half Day Sick Leave (Work From Home)">HD</td>

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

                                @elseif($attendance == 'PH' && $working_hours[0] == '04')
                                    <?php $ph = $ph + 0.5; ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Paid Holiday">PH</td>

                                @elseif($attendance == 'PH')
                                    <?php $ph++; ?>
                                    @if($fixed_date == $sunday_date)
                                        <td style="border: 5px solid #000000;background-color:#E6812F;" title="Paid Holiday">PH</td>
                                    @else
                                        <td style="border: 5px solid #000000;background-color:#76933C;" title="Paid Holiday">PH</td>
                                    @endif

                                @elseif($attendance == 'OH' && $working_hours[0] == '04')
                                    <?php $ph = $ph + 0.5; ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;color:#FFFFFF;" title="Half Optional Holiday">PH</td>

                                @elseif($attendance == 'OH')
                                    <?php $ph++; ?>
                                    <td style="border: 5px solid #000000;background-color:#76933C;color:#FFFFFF;" title="Optional Holiday">PH</td>

                                @elseif($attendance == 'H' && $working_hours[0] == '04')
                                    <?php $week_off = $week_off + 0.5; ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Sunday">H</td>

                                @elseif($attendance == 'H')
                                    <?php $week_off++; ?>
                                    <td style="border: 5px solid #000000;background-color:#ffc000;"  title="Sunday">H</td>

                                @elseif($attendance == 'TS' && $working_hours[0] == '04')
                                    <?php $week_off = $week_off + 0.5; ?>
                                    <td style="border: 5px solid #000000;background-color:#d99594;" title="Half Third Saturday">H</td>

                                @elseif($attendance == 'TS')
                                    <?php $week_off++; ?>
                                    <td style="border: 5px solid #000000;background-color:#ffc000;"  title="Third Saturday">H</td>

                                @elseif($attendance == 'P')
                                	<?php $present++; ?>
                                    <td style="border: 5px solid #000000;background-color:#d8d8d8;" title="Present">P</td>

                                @elseif($attendance == 'WFHP')
                                    <?php $present++; ?>
                                    <td style="border: 5px solid #000000;background-color:#d8d8d8;color: #0000FF;" title="Work From Home">P</td>

                                @elseif($attendance == 'CO')
                                    <?php $present++; ?>
                                    <td style="border: 5px solid #000000;background-color:#eedc82;" title="Compensatory Off">CO</td>

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

                            @if($attendance == 'B')

                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                                <td style="border: 5px solid #000000;text-align:center;"></td>
                            @else

                                <?php

                                    $days = $present + $week_off + $ph + $half_day_actual - $ul;
                                    $total_leaves = $sl + $pl;
                                    $total_days = $sl + $days + $total_leaves;
                                ?>

                                <td style="border: 5px solid #000000;text-align:center;">{{ $present }}
                                </td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $week_off }}
                                </td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $ph }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $sl }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $pl }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $half_day }}
                                </td>
                                <td style="border: 5px solid #000000;text-align:center;"> 
                                {{ $half_day_actual }}</td>
                                <td style="border: 5px solid #000000;text-align:center;"> {{ $ul }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $absent }}
                                </td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $days }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $total_leaves }}</td>
                                <td style="border: 5px solid #000000;text-align:center;">{{ $total_days }}
                                </td>
                            @endif
                        </tr>
                    <?php $i++; ?>
                   	@endforeach
    			</table>
            @endforeach
        </body>
    </html>
@endif