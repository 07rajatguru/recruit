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
                <tr style="background-color:white;">
                    <td colspan="2"></td>
                    <td align="center">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:80%;height: 90px;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                <tr>
                    <td>
                        <b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Dear {{$uname}},</p></b>

                        <p><b>Subject : </b>{{ $todo_subject }}</p>
                        <p><b>Description: </b>{!! $description !!}</p>
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