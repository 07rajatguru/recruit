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
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: rgba(157,92,172,0.9); height: 70px;">
                <tr>
                    <td align="center">
                        Adler Talent Solutions Pvt. Ltd.
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
                <tr style="background-color: #C4D79B;">
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Sr.No.</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left" ><b>Date</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Position Name</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left" ><b>Company</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Location</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>No of resumes<br/>associated</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left cvs_td_right" ><b>Status</b></td>
                </tr>

                <?php
                 $i=1;
                 $total_cnt = sizeof($associate_daily);
                ?>
                @foreach($associate_daily as $key=>$value)
                    <tr>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{ $i }}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left">{{date('jS F,y') }}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left">{{$value['posting_title']}}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left">{{$value['company']}}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left">{{$value['location']}}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left">{{$value['associate_candidate_count'] or ''}}</td>
                        <td align="center" class="cvs_td_top @if($total_cnt==$i) cvs_td_bottom @endif  cvs_td_left cvs_td_right" >{{$value['status']}}</td>
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
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Sr.<br/>No.</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Position</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Position Location</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Name of the Candidate</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Interview Date</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Interview Time</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Candidate Location</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Mode of Interview</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Skype ID</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Contact No.</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Email ID</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left"><b>Confirmed</b></td>
                    <td align="center" class="cvs_td_top cvs_td_left cvs_td_right"><b>Source</b></td>
                </tr>

                <?php
                    $i=1;

                ?>
                @foreach($interview_daily as $key=>$value)
                    <tr>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{ $i }}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['posting_title']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['interview_location']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['cname']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{date('d/m/Y',strtotime($value['interview_date'])) }}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{date('h:i A',strtotime($value['interview_time']))  }}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['ccity']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['interview_type']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{''}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['cmobile']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left">{{$value['cemail']}}</td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left"><b>{{'Yes'}}</b></td>
                        <td align="center" class="cvs_td_top @if($total_interview_cnt==$i) cvs_td_bottom @endif cvs_td_left cvs_td_right"><b>{{'Adler'}}</b></td>
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