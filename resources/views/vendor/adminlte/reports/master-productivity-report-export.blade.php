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

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">Particular</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">Minimum %/ <br/> Benchmark</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">Standard Numbers/ <br/> Monthly</th>

					<th rowspan="2" style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">Standard Numbers/ <br/> Weekly</th>

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
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="10">
                                    <b>Week1</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 1)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="10">
                                    <b>Week2</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 2)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="10">
                                    <b>Week3</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 3)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="10">
                                    <b>Week4</b><br/> <b>{{ $from_date }} to {{ $to_date }}</b>
                                </th>
                            @endif

                            @if($i == 4)
                                <th style="background: #F1C232;text-align: center;color: #000000;border: 5px solid #000000;" height="48" width="10">
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
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="10">1</td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="30">
                        <span>Number of Resumes delivered <br/> to client</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="25">
                        <span>NA</span>
                    </td>
                    <td style="background: #ead1dc;text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="15">
                       {{ $bench_mark['no_of_resumes_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="15">
                        {{ $bench_mark['no_of_resumes_weekly'] }} 
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $a = 0; $a_cnt = 0;?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="10" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['ass_cnt'] >= $bench_mark['no_of_resumes_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="10" class="no_of_resumes_weeks">{{ $value['ass_cnt'] }}</td>
                                    @if($a==0) {{ $week1_c++ }} @elseif($a==1) {{ $week2_c++ }} @elseif($a==2) {{ $week3_c++ }} @elseif($a==3) {{ $week4_c++ }} @elseif($a==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="10">{{ $value['ass_cnt'] }}</td>
                                @endif
                            @endif
                            <?php $a = $a + 1; $a_cnt = $a_cnt + $value['ass_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="10" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="30" width="15">{{ $a_cnt }}</td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10">2</td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>Shortlist Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                       <span>{{ $bench_mark['role_shortlist_ratio'] ?? 0 }}% (of Total CVs)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['shortlist_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['shortlist_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $b = 0; $b_cnt = 0;?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['shortlisted_cnt'] >= $bench_mark['shortlist_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">{{ $value['shortlisted_cnt'] }} </td>
                                    @if($b==0) {{ $week1_c++ }} @elseif($b==1) {{ $week2_c++ }} @elseif($b==2) {{ $week3_c++ }} @elseif($b==3) {{ $week4_c++ }} @elseif($b==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $value['shortlisted_cnt'] }} </td>
                                @endif
                            @endif
                            <?php $b = $b + 1; $b_cnt = $b_cnt + $value['shortlisted_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $b_cnt }}</td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10">3 </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>Interview Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                       <span>{{ $bench_mark['role_interview_ratio'] ?? 0 }}% (Shortlist Ratio)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['interview_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['interview_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $c = 0; $c_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['interview_cnt'] >= $bench_mark['interview_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">{{ $value['interview_cnt'] }}</td>
                                    @if($c==0) {{ $week1_c++ }} @elseif($c==1) {{ $week2_c++ }} @elseif($c==2) {{ $week3_c++ }} @elseif($c==3) {{ $week4_c++ }} @elseif($c==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $value['interview_cnt'] }}</td>
                                @endif
                            @endif
                            <?php $c = $c + 1; $c_cnt = $c_cnt + $value['interview_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"> {{ $c_cnt }} </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10"> 4 </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>Selection Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                        <span>{{ $bench_mark['role_selection_ratio'] ?? 0 }}% (of Interview Ratio)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['selection_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['selection_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $d = 0; $d_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['selected_cnt'] >= $bench_mark['selection_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"> {{ $value['selected_cnt'] }} </td>
                                    @if($d==0) {{ $week1_c++ }} @elseif($d==1) {{ $week2_c++ }} @elseif($d==2) {{ $week3_c++ }} @elseif($d==3) {{ $week4_c++ }} @elseif($d==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"> {{ $value['selected_cnt'] }} </td>
                                @endif
                            @endif
                            <?php $d = $d + 1; $d_cnt = $d_cnt + $value['selected_cnt']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $d_cnt }} </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10">5</td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>Offer Acceptance Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                        <span>{{ $bench_mark['role_offer_acceptance_ratio'] ?? 0 }}% (of Selection Ratio)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['offer_acceptance_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['offer_acceptance_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $e = 0; $e_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['offer_acceptance_ratio'] >= $bench_mark['offer_acceptance_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"> {{ $value['offer_acceptance_ratio'] }} </td>
                                    @if($e==0) {{ $week1_c++ }} @elseif($e==1) {{ $week2_c++ }} @elseif($e==2) {{ $week3_c++ }} @elseif($e==3) {{ $week4_c++ }} @elseif($e==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"> {{ $value['offer_acceptance_ratio'] }} </td>
                                @endif
                            @endif
                            <?php $e = $e + 1; $e_cnt = $e_cnt + $value['offer_acceptance_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $e_cnt }} </td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10">6</td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>Joining Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                        <span>{{ $bench_mark['role_joining_ratio'] ?? 0 }}% (of offer acceptance)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['joining_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['joining_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $f = 0; $f_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['joining_ratio'] >= $bench_mark['joining_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">{{ $value['joining_ratio'] }}</td>
                                    @if($f==0) {{ $week1_c++ }} @elseif($f==1) {{ $week2_c++ }} @elseif($f==2) {{ $week3_c++ }} @elseif($f==3) {{ $week4_c++ }} @elseif($f==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $value['joining_ratio'] }}</td>
                                @endif
                            @endif
                            <?php $f = $f + 1; $f_cnt = $f_cnt + $value['joining_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $f_cnt }}</td>
                </tr>

                <tr style="height: 15px;">
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="10">7</td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="30">
                        <span>After Joining Success Ratio</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="25">
                        <span>{{ $bench_mark['role_after_joining_success_ratio'] ?? 0 }}% (Joining Ratio)</span>
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                       {{ $bench_mark['after_joining_success_ratio_monthly'] }}
                    </td>
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">
                        {{ $bench_mark['after_joining_success_ratio_weekly'] }}
                    </td>

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) > 0)
                        <?php $g = 0; $g_cnt = 0; ?>
                        @foreach($bench_mark['frm_to_date_array'] as $key => $value)

                            <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                            @if($from_date > $to_date_default)
                                <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"></td>
                            @else
                                @if($value['joining_success_ratio'] >= $bench_mark['after_joining_success_ratio_weekly'])
                                    <td style="background: #32CD32; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks"> {{ $value['joining_success_ratio'] }} </td>
                                    @if($g==0) {{ $week1_c++ }} @elseif($g==1) {{ $week2_c++ }} @elseif($g==2) {{ $week3_c++ }} @elseif($g==3) {{ $week4_c++ }} @elseif($g==4) {{ $week5_c++ }} @endif
                                @else
                                    <td style="background: #ff0000; text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15"> {{ $value['joining_success_ratio'] }} </td>
                                @endif
                            @endif
                            <?php $g = $g + 1; $g_cnt = $g_cnt + $value['joining_success_ratio']; ?>
                        @endforeach
                    @endif

                    @if(isset($bench_mark['frm_to_date_array']) && sizeof($bench_mark['frm_to_date_array']) == '4')
                        <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15" class="no_of_resumes_weeks">
                            <span>NA</span>
                        </td>
                    @endif
                    
                    <td style="text-align: center;color: #000000;border: 5px solid #000000;" height="15" width="15">{{ $g_cnt }}</td>
                </tr>

                <tr style="height: 15px;">
                	<td height="55"></td>
                	<td height="55"></td>
                	<td height="55"></td>
                	<td colspan="2" style="background: #46BDC6;text-align: center;color: #000000;border: 5px solid #000000;" height="55">
                        <b>Overall Feedback (Meet <br/> Expectation / Improvements <br/> Needed)</b>
                    </td>

                    <td height="55"></td>
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

                <!-- <tr style="border:2px solid #000000;" height="170">
                	<td colspan="11" style="border:2px solid #000000;background: #ead1dc;" height="170">
                        <span style="color: #CC4125;">Important Notes: </span>
                        <br/><br/>
                        <span style="color: #000000;">
                            1) Weekly data is generated Auto Generated by Easy2hire based on actions made on software starting from associating resume to after joining success ratio.<br />
                            2) Please add a comment if that is important to justify weekly numbers. The remarks column is given for overall feedback of monthly performance / action points for coming month.<br />
                            3) Weekly Overall feedback will depend upon Standard Numbers Vs Achievement. Minimum 2 criteria's (out of first 6) should ideally meet expectation on weekly basis.<br />
                            4) Performance for the current month will lead to Benefits OR Efforts to improve (Penalties) for the next month. 
                        </span>
                        <span><br /></span>
                        
                            <span style="color: #000000;">
                                5) Monthly achievement will lead to Reflection Process.
                            </span>
                        
                        <span style="color: #000000;"> <br />
                            a. Weightage percentage to be given between (80-100%) in case monthly productivity report is meet expectation / exceeds expectation / exceptional.<br />
                            b. Weightage percentage to be given between (50-80%) in case monthly productivity report is improvements needed.<br />
                            c. Weightage percentage to be given between (Less than 50%) in case monthly productivity report is unsatisfactory.
                        </span>
                        
                	</td>
                </tr> -->

                <tr>
                    <td colspan="11" style="border-top:2px solid #000000;border-left:2px solid #000000;border-right:2px solid #000000; border-bottom: none;background: #ead1dc;">
                        <span style="color: #CC4125;text-decoration: underline;"><b>Important Notes:</b></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="11" style="border-top:2px solid #000000;border-left:2px solid #000000;border-right:2px solid #000000; border-bottom: none;background: #ead1dc;" height="65">
                        <span style="color: #000000">
                            1) Weekly data is generated Auto Generated by Easy2hire based on actions made on software starting from associating resume to after joining success ratio.<br />
                            2) Please add a comment if that is important to justify weekly numbers. The remarks column is given for overall feedback of monthly performance / action points for coming month.<br />
                            3) Weekly Overall feedback will depend upon Standard Numbers Vs Achievement. Minimum 2 criteria's (out of first 6) should ideally meet expectation on weekly basis.<br />
                            4) Performance for the current month will lead to Benefits OR Efforts to improve (Penalties) for the next month. 
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="11" style="border-top:2px solid #000000;border-left:2px solid #000000;border-right:2px solid #000000; border-bottom: none;background: #ead1dc;">
                        <span style="color: #000000">
                            <b>5) Monthly achievement will lead to Reflection Process.</b>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="11" style="border-top:2px solid #000000;border-left:2px solid #000000;border-right:2px solid #000000; border-bottom: none;background: #ead1dc;" height="45">
                        <span style="color: #000000">
                            a. Weightage percentage to be given between (80-100%) in case monthly productivity report is meet expectation / exceeds expectation / exceptional.<br />
                            b. Weightage percentage to be given between (50-80%) in case monthly productivity report is improvements needed.<br />
                            c. Weightage percentage to be given between (Less than 50%) in case monthly productivity report is unsatisfactory.
                        </span>
                    </td>
                </tr>
            </table>
		</body>
	</html>
@endif