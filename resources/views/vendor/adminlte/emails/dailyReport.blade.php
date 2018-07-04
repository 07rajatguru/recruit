<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<style>
    .cvs_td_top{
        border-top: black 1px solid;
        padding: 8px;
    }
    .cvs_td_right{
        border-right: black 1px solid;
    }
    .cvs_td_bottom{
        border-bottom: black 1px solid;
    }
    .cvs_td_left{
        border-left: black 1px solid;
    }

</style>
<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr>
                    <td align="center">
                       <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                {{--<a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                <img class="site-logo" width="60px" height="80px" src="http://adlertalent.com/wp-content/uploads/2016/06/logo.png" alt="Adler Talent Solutions Pvt. Ltd." style=" padding-top: 16px;   vertical-align: middle;">
                <span>Adler Talent Solutions Pvt. Ltd.</span> </a>--}}
   <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                            <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px;   vertical-align: middle;">
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
                <tr>
                    <td colspan="7">
                        <u><b><h1>No of CVs Associated : {{$associate_count or '0'}}</h1></b></u>
                    </td>
                </tr>
                <tr  style="background-color: #f39c12;">
                    <td  align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.No.</b></td>
                    <td  align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Date</b></td>
                    <td colspan="7" align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Position Name</b></td>
                    <td align="center" style="border-top: black 1px solid;padding:8px;border-left: black 1px solid;"><b>Company</b></td>
                    <td  align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Location</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>No of resumes<br/>associated</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>Status</b></td>
                </tr>

                <?php
                 $i=1;
                 $total_cnt = sizeof($associate_daily);
                ?>
                @foreach($associate_daily as $key=>$value)
                    <tr colspan="7">
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('jS F,y') }}</td>
                        <td colspan="7" align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['posting_title']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['company']}}</td>
                        <td  align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['location']}}</td>
                        <td  align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['associate_candidate_count'] or ''}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid; border-right: black 1px solid;">{{$value['status']}}</td>
                    </tr>
                    <?php $i++; ?>
                @endforeach

                <?php $total_interview_cnt = sizeof($interview_daily); ?>

                <tr>
                    <td colspan="7">
                        <u><b><h1>No of Interviews Scheduled : {{$total_interview_cnt or '0'}}</h1></b></u>
                    </td>
                </tr>

                <tr style="background-color: #7598d9">
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.<br/>No.</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Position</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Position Location</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Name of the Candidate</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Interview Date</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Interview Time</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Candidate Location</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Mode of Interview</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Skype ID</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Contact No.</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Email ID</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Confirmed</b></td>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;"><b>Source</b></td>
                </tr>

                <?php
                    $i=1;

                ?>
                @foreach($interview_daily as $key=>$value)
                    <tr>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{ $i }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['posting_title']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['interview_location']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['cname']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('d/m/Y',strtotime($value['interview_date'])) }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{date('h:i A',strtotime($value['interview_time']))  }}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['ccity']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['interview_type']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{''}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['cmobile']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;">{{$value['cemail']}}</td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;"><b>{{'Yes'}}</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px; <?php if ($total_interview_cnt==$i): ?>border-bottom: black 1px solid;<?php endif ?>  border-left: black 1px solid;border-right: black 1px solid;"><b>{{'Adler'}}</b></td>
                    </tr>
                    <?php $i++; ?>
                @endforeach

                <tr>
                    <td colspan="7">
                        <u><b><h1>No of Leads added : {{$lead_count or '0'}}</h1></b></u>
                    </td>
                </tr>


            </table>
        </td>
    </tr>
    <tr style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>



</body>
</html>