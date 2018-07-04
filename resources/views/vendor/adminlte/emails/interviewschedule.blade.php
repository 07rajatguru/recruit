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
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
        <tr>
            <td>
                <b><p>Dear Sir,</p></b>
            </td>
        </tr>
        <tr>
            <td>
                <p><u> Please find below Interview Schedule:</u></p>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                    <tr style="background-color: #7598d9">
                        <td align="center"><b>Sr.<br/>No.</b></td>
                        <td align="center"><b>Position</b></td>
                        <td align="center"><b>Position Location</b></td>
                        <td align="center"><b>Name of the Candidate</b></td>
                        <td align="center"><b>Interview Date</b></td>
                        <td align="center"><b>Interview Time</b></td>
                        <td align="center"><b>Candidate Location</b></td>
                        <td align="center"><b>Mode of Interview</b></td>
                        <td align="center"><b>Skype ID</b></td>
                        <td align="center"><b>Contact No.</b></td>
                        <td align="center"><b>Email ID</b></td>
                        <td align="center"><b>Confirmed</b></td>
                        <td align="center"><b>Source</b></td>
                    </tr>
                    <?php $i=0; ?>
                    <tr>
                        <td align="center">{{ ++$i }}</td>
                        <td align="center">{{$job_designation}}</td>
                        <td align="center">{{$job_location}}</td>
                        <td align="center">{{$cname}}</td>
                        <td align="center">{{date('d/m/Y',strtotime($interview_date)) }}</td>
                        <td align="center">{{date('h:i A',strtotime($interview_time))  }}</td>
                        <td align="center">{{$ccity}}</td>
                        <td align="center">{{$interview_type}}</td>
                        <td align="center">{{''}}</td>
                        <td align="center">{{$cmobile}}</td>
                        <td align="center">{{$cemail}}</td>
                        <td align="center"><b>{{'Yes'}}</b></td>
                        <td align="center"><b>{{'Adler'}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr style="height: 45px; background-color: #dddddd;">
                        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                    </tr>
                </table>
            </td>
        </tr>


</table>
</body>
</html>