<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
    <tr>
        <td width="600" >
            <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr style="background-color:white;">
                    <td align="center">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img width="600" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="600" >
            <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                <tr>
                    <td width="600">
                        <b>Hello,</p></b>
                        <p>{{ $subject }} :</p>
                        <p>
                        <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                            <tr style="background-color: #7598d9">
                                <td align="center"><b>Sr.<br/>No.</b></td>
                                <td align="center"><b>Candidate Name</b></td>
                                <td align="center"><b>Candidate Owner</b></td>
                                <td align="center"><b>Candidate Email</b></td>
                                <td align="center"><b>Candidate Status</b></td>
                                <td align="center"><b>Candidate Mobile No.</b></td>
                                <td align="center"><b>Round</b></td>
                            </tr>
                            <?php $i=0; ?>
                            @foreach($candidate as $key => $value)
                                <tr>
                                    <td align="center">{{ ++$i }}</td>
                                    <td align="center">{{$value['fname']}}</td>
                                    <td align="center">{{$value['owner']}}</td>
                                    <td align="center">{{$value['email']}}</td>
                                    <td align="center">{{$value['status']}}</td>
                                    <td align="center">{{$value['mobile']}}</td>
                                    <td align="center">{{$value['shortlisted']}}</td>
                                </tr>
                            @endforeach
                        </table>
                        </p>
                        <p>Thanks.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="600" >
            <table width="600" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr style="height: 45px; background-color: #dddddd;">
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>