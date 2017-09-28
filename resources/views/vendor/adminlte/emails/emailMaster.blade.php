<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Telent</title>

    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #f39c12; height: 70px;">
                <tr>
                    <td align="center">
                        <img src="/images/logo.png">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                <tr>
                    <td>
                        <p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Hello,</p>
                        <p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">@yield('title')</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        @yield('description')
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright TATA MOTORS <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>



</body>
</html>