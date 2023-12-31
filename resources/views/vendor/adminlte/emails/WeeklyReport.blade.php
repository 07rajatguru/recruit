<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>
    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
        <table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                        @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
                        <tr>
                            <td colspan = "11">
                                <u><b><h1>No of CVs Associated in this week : {{$associate_count or '0'}}</h1></b></u>
                            </td>
                        </tr>

                        <tr style="background-color: #f39c12;">
                            <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                            <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                            <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of resumes<br/>associated</b></td>
                        </tr>

                        <?php
                            $i=1;
                            $total_cnt = sizeof($associate_weekly);
                        ?>
                        @foreach($associate_weekly as $key=>$value)
                            <tr>
                                <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}
                                </td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['associate_date']))}}</td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['associate_candidate_count']}}</td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach

                            <tr>
                                <td colspan = "1" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total Associated
                                </td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$associate_count or '0'}}</td>
                            </tr>

                            <tr>
                                <td colspan = "1" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Benchmark</td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">40</td>
                            </tr>

                            <tr>
                                <td colspan = "1" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">No of resumes not
                                    <br/> achieved</td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid; color: red"><?php if ($associate_count<40): ?>{{$associate_count-40}}<?php endif ?></td>
                            </tr>
                        @endif
                        @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')

                            <tr>
                                <td colspan = "11">
                                    <u><b><h1>No of Interviews Scheduled : {{$interview_count or '0'}}</h1></b></u>
                                </td>
                            </tr>

                            <tr style="background-color: #7598d9;">
                                <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of Interviews</b></td>
                            </tr>

                        <?php
                             $i=1;
                            $total_cnt = sizeof($interview_weekly);
                        ?>
                        @foreach($interview_weekly as $key=>$value)
                            <tr>
                                <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}</td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['interview_date']))}}</td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['interview_daily_count']}}</td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach

                            <tr>
                                <td colspan = "1" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total</td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$interview_count or '0'}}</td>
                            </tr>
                        @endif

                        @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                            <tr>
                                <td colspan = "11">
                                    <u><b><h1>No of Leads Added : {{$leads_count or '0'}}</h1></b></u>
                                </td>
                            </tr>

                            <tr style="background-color: #C4D79B;">
                                <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                                <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of Leads</b></td>
                            </tr>

                            <?php
                                $j=1;
                                $total_cnt = $leads_count;
                            ?>

                            @foreach($leads_weekly as $key=>$value)
                                <tr>
                                    <td colspan = "1" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $j }}</td>
                                    <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['lead_date']))}}</td>
                                    <td colspan = "5" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['lead_count']}}</td>
                                </tr>
                                <?php $j++; ?>
                            @endforeach

                            <tr>
                                <td colspan = "1" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total</td>
                                <td colspan = "5" align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$leads_count or '0'}}</td>
                            </tr>

                            <tr>
                                <td colspan = "11">
                                    <u><b><h1>Lead Details : {{$leads_count or '0'}}</h1></b></u>
                                </td>
                            </tr>

                            <tr style="background-color: #C4D79B;">
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Company Name</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Contact Point</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Designation</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Email ID</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Mobile No.</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>City</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Website</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Service</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Lead Status</b></td>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                                    <b>Source</b></td>
                            </tr>

                            <?php
                                $j=1;
                                $total_cnt = $leads_count;
                            ?>

                            @foreach($leads_weekly as $key=>$value)
                                <tr>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $j }}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['company_name']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['contact_point']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['designation']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['email']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['mobile']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['city']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['website']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['service']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $value['lead_status']}}</td>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['source']}}</td>
                                </tr>
                                <?php $j++; ?>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>