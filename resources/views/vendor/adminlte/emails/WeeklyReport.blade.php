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
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr>
                    <td align="center">
                       <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                {{--<a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">        <img class="site-logo" width="60px" height="80px" src="http://adlertalent.com/wp-content/uploads/2016/06/logo.png" alt="Adler Talent Solutions Pvt. Ltd." style=" padding-top: 16px;   vertical-align: middle;"> <span>Adler Talent Solutions Pvt. Ltd.</span> </a>--}}
                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                            <img width="600" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 150px;padding-top: 16px;   vertical-align: middle;">
                        </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
                <tr>
                    <td colspan="3">
                        <u><b><h1>No of CVs Associated in this week : {{$associate_count or '0'}}</h1></b></u>
                    </td>
                </tr>

                <tr style="background-color: #f39c12;">
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of resumes<br/>associated</b></td>
                </tr>

                <?php
                 $i=1;
                 $total_cnt = sizeof($associate_weekly);
                ?>
                @foreach($associate_weekly as $key=>$value)
                    <tr>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['associate_date']))}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['associate_candidate_count']}}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach

                    <tr>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total Associated</td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$associate_count or '0'}}</td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Benchmark</td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">40</td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">No of resumes not <br/> achieved</td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid; color: red"><?php if ($associate_count<40): ?>{{$associate_count-40}}<?php endif ?></td>
                    </tr>
                @endif
                @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')

                    <tr>
                        <td colspan="3">
                            <u><b><h1>No of Interviews Scheduled : {{$interview_count or '0'}}</h1></b></u>
                        </td>
                    </tr>

                    <tr style="background-color: #7598d9;">
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of Interviews</b></td>
                    </tr>

                <?php
                 $i=1;
                 $total_cnt = sizeof($interview_weekly);
                ?>
                @foreach($interview_weekly as $key=>$value)
                    <tr>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['interview_date']))}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['interview_daily_count']}}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach

                    <tr>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total</td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$interview_count or '0'}}</td>
                    </tr>
                @endif

                @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                    <tr>
                        <td colspan="3">
                            <u><b><h1>No of Leads Added : {{$leads_count or '0'}}</h1></b></u>
                        </td>
                    </tr>

                    <tr style="background-color: #C4D79B;">
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Day(Date)</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>No of Leads</b></td>
                    </tr>

                    <?php
                        $j=1;
                        $total_cnt = $leads_count;
                    ?>

                    @foreach($leads_weekly as $key=>$value)
                        <tr>
                            <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $j }}</td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('l (jS F,y) ',strtotime($value['lead_date']))}}</td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$j): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;">{{$value['lead_count']}}</td>
                        </tr>
                        <?php $j++; ?>
                    @endforeach

                    <tr>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;"></td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">Total</td>
                        <td align="center" style="padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">{{$leads_count or '0'}}</td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>

    <tr style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>
</body>
</html>