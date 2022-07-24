@if(isset($bench_mark) && sizeof($bench_mark) > 0)
	<!DOCTYPE html>
	<html>
		<body>
			<table style="width: 40%;">
				<tr></tr>
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

					<th colspan="5" style="background: #BFBFBF;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Actual Weekly Numbers</th>

					<th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"></th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					@if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $i = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)
                            <?php
                                $from_date = date('d/m',strtotime($value['from_date']));
                                $to_date = date('d/m',strtotime($value['to_date']));
                            ?>

                            @if($i == 0)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="15">
                                    <b>Week1</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 1)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="15">
                                    <b>Week2</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 2)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="15">
                                    <b>Week3</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 3)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="15">
                                    <b>Week4</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 4)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="15">
                                    <b>Week5</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif
                            <?php $i = $i + 1; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30"></th>
                    @endif

                    <th style="background: #BFBFBF;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="25"><b>Monthly Achievement</b></th>
				</tr>

				<?php $week1_c=0; $week2_c=0; $week3_c=0; $week4_c=0; $week5_c=0; ?>
                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>1</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Number of Resumes delivered to client</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>NA</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['no_of_resumes_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['no_of_resumes_weekly'] }} </span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $a = 0; $a_cnt = 0;?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['ass_cnt'] >= $bench_mark['no_of_resumes_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['ass_cnt'] }}</span>
                                        </p>
                                    </td>
                                    @if($a==0) {{ $week1_c++ }} @elseif($a==1) {{ $week2_c++ }} @elseif($a==2) {{ $week3_c++ }} @elseif($a==3) {{ $week4_c++ }} @elseif($a==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['ass_cnt'] }}</span>
                                        </p>
                                    </td>
                                @endif
                            @endif
                            <?php $a = $a + 1; $a_cnt = $a_cnt + $value['ass_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;"> <span>{{ $a_cnt }}</span> </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>2</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Shortlist Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>50% (of Total CVs)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['shortlist_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['shortlist_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $b = 0; $b_cnt = 0;?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['shortlisted_cnt'] >= $bench_mark['shortlist_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['shortlisted_cnt'] }}</span>
                                        </p>
                                    </td>
                                    @if($b==0) {{ $week1_c++ }} @elseif($b==1) {{ $week2_c++ }} @elseif($b==2) {{ $week3_c++ }} @elseif($b==3) {{ $week4_c++ }} @elseif($b==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['shortlisted_cnt'] }}</span>
                                        </p>
                                    </td>
                                @endif
                            @endif
                            <?php $b = $b + 1; $b_cnt = $b_cnt + $value['shortlisted_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;"> <span>{{ $b_cnt }}</span> </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>3</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Interview Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>50% (Shortlist Ratio)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['interview_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['interview_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $c = 0; $c_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['interview_cnt'] >= $bench_mark['interview_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['interview_cnt'] }}</span>
                                        </p>
                                    </td>
                                    @if($c==0) {{ $week1_c++ }} @elseif($c==1) {{ $week2_c++ }} @elseif($c==2) {{ $week3_c++ }} @elseif($c==3) {{ $week4_c++ }} @elseif($c==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['interview_cnt'] }}</span>
                                        </p>
                                    </td>
                                @endif
                            @endif
                            <?php $c = $c + 1; $c_cnt = $c_cnt + $value['interview_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;"> <span>{{ $c_cnt }}</span> </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>4</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Selection Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>20% (of Interview Ratio)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['selection_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['selection_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $d = 0; $d_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['selected_cnt'] >= $bench_mark['selection_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['selected_cnt'] }}</span>
                                        </p>
                                    </td>
                                    @if($d==0) {{ $week1_c++ }} @elseif($d==1) {{ $week2_c++ }} @elseif($d==2) {{ $week3_c++ }} @elseif($d==3) {{ $week4_c++ }} @elseif($d==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['selected_cnt'] }}</span>
                                        </p>
                                    </td>
                                @endif
                            @endif
                            <?php $d = $d + 1; $d_cnt = $d_cnt + $value['selected_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;">
                            <span>{{ $d_cnt }}</span>
                        </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>5</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Offer Acceptance Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>70% (of Selection Ratio)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['offer_acceptance_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['offer_acceptance_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $e = 0; $e_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['offer_acceptance_ratio'] >= $bench_mark['offer_acceptance_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['offer_acceptance_ratio'] }}</span>
                                        </p>
                                    </td>
                                    @if($e==0) {{ $week1_c++ }} @elseif($e==1) {{ $week2_c++ }} @elseif($e==2) {{ $week3_c++ }} @elseif($e==3) {{ $week4_c++ }} @elseif($e==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;">
                                            <span>{{ $value['offer_acceptance_ratio'] }}</span>
                                        </p>
                                    </td>
                                @endif
                            @endif
                            <?php $e = $e + 1; $e_cnt = $e_cnt + $value['offer_acceptance_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;"> <span>{{ $e_cnt }}</span> </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>6</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>Joining Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>80% (of offer acceptance)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['joining_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['joining_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $f = 0; $f_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['joining_ratio'] >= $bench_mark['joining_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;"> <span>{{ $value['joining_ratio'] }}</span> </p>
                                    </td>
                                    @if($f==0) {{ $week1_c++ }} @elseif($f==1) {{ $week2_c++ }} @elseif($f==2) {{ $week3_c++ }} @elseif($f==3) {{ $week4_c++ }} @elseif($f==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;"> <span>{{ $value['joining_ratio'] }}</span> </p>
                                    </td>
                                @endif
                            @endif
                            <?php $f = $f + 1; $f_cnt = $f_cnt + $value['joining_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;">
                            <span>{{ $f_cnt }}</span>
                        </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="10">
                        <p align="center" style="text-align: center;"><span>7</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>After Joining Success Ratio</span></p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="35">
                        <p align="center" style="text-align: center;"><span>80% (Joining Ratio)</span></p>
                    </td>
                    <td style="background-color: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"><span style="color: #000000;">{{ $bench_mark['after_joining_success_ratio_monthly'] }}</span> </p>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="30">
                        <p align="center" style="text-align: center;"> <span style="color: #000000;">{{ $bench_mark['after_joining_success_ratio_weekly'] }}</span></p>
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $g = 0; $g_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                    <p align="center" style="text-align: center;"></p>
                                </td>
                            @else
                                @if($value['joining_success_ratio'] >= $bench_mark['after_joining_success_ratio_weekly'])
                                    <td style="background-color: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;"> <span>{{ $value['joining_success_ratio'] }}</span> </p>
                                    </td>
                                    @if($g==0) {{ $week1_c++ }} @elseif($g==1) {{ $week2_c++ }} @elseif($g==2) {{ $week3_c++ }} @elseif($g==3) {{ $week4_c++ }} @elseif($g==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background-color: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                                        <p align="center" style="text-align: center;"> <span>{{ $value['joining_success_ratio'] }}</span> </p>
                                    </td>
                                @endif
                            @endif
                            <?php $g = $g + 1; $g_cnt = $g_cnt + $value['joining_success_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15" class="no_of_resumes_weeks">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="40" width="15">
                        <p align="center" style="text-align: center;"> <span>{{ $g_cnt }}</span> </p>
                    </td>
                </tr>

                <tr style="height: 15px;">
                	<td></td>
                	<td></td>
                	<td></td>
                	<td colspan="2" style="background: #46BDC6;text-align: center;color: #000000;border: 5px solid #000000;" >
                        <b>Overall Feedback (Meet Expectation / Improvements Needed)</b>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;@if($week1_c >= 2) background-color: #32CD32; @else background-color: #ff0000; @endif"></td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;@if($week2_c >= 2) background-color: #32CD32; @else background-color: #ff0000; @endif"></td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;@if($week3_c >= 2) background-color: #32CD32; @else background-color: #ff0000; @endif"></td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;@if($week4_c >= 2) background-color: #32CD32; @else background-color: #ff0000; @endif"></td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;@if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4') 'NA' @else @if($week5_c >= 2) background-color: #32CD32; @else background-color: #ff0000; @endif @endif"></td>
                    <td></td>
                </tr>

                <tr style="height: 15px;">
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                </tr>

                <tr style="border:2px solid #000000;" height="210">
                	<td colspan="11" style="border:2px solid #000000;background: #ead1dc;color: #000000;" height="210">
                		<p>
                            <b><u><span style="color: #CC4125;"><br />Important Notes: </span></u></b>
                            <b><u><span style="color: #1155CC;"><br /></span></u></b>
                            <b><u><span style="color: #741B47;"><br /></span></u></b>
                            <span style="color: #000000;">
                                1) Weekly data is generated Auto Generated by Easy2hire based on actions made on software starting from associating resume to after joining success ratio.<br />
                                2) Please add a comment if that is important to justify weekly numbers. The remarks column is given for overall feedback of monthly performance / action points for coming month.<br />
                                3) Weekly Overall feedback will depend upon Standard Numbers Vs Achievement. Minimum 2 criteria's (out of first 6) should ideally meet expectation on weekly basis.<br />
                                4) Performance for the current month will lead to Benefits OR Efforts to improve (Penalties) for the next month. 
                            </span>
                            <span style="color: #741B47;"><br /></span>
                            <b>
                                <span style="color: #000000;">
                                    5) Monthly achievement will lead to Reflection Process.
                                </span>
                            </b>
                            <span style="color: #000000;"> <br />
                                a. Weightage percentage to be given between (80-100%) in case monthly productivity report is meet expectation / exceeds expectation / exceptional.<br />
                                b. Weightage percentage to be given between (50-80%) in case monthly productivity report is improvements needed.<br />
                                c. Weightage percentage to be given between (Less than 50%) in case monthly productivity report is unsatisfactory.
                            </span>
                            <b>
                                <u><span style="color: #CC4125;"></span></u>
                            </b>
                        </p>
                	</td>
                </tr>
            </table>
		</body>
	</html>
@endif