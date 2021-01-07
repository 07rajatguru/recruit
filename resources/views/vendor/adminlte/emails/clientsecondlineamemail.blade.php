<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr style="background-color:white;">
                        <td colspan="2"></td>
                        <td align="center">
                            <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                    <img width="600" class="site-logo" src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                </a>
                            </div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600"  style="background-color: green; !important;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td style="font-family:Cambria, serif;font-size: 11.0pt;">

                            <b><p style="margin-top: 0px; margin-bottom: 14px;">
                            Dear {{ $client['second_line_am_name'] }},</p></b>
                            <p>Greetings !</p>

                            <p>Please note that you have been assigned as 2nd line coordinator to this client.</p>
                            <p>Please connect with your reporting manager to understand more about the same.</p>
                            
                            <p>Thanks</p>
                            <p>Easy2Hire Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600">
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