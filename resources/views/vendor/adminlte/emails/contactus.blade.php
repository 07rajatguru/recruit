<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #F2F2F2;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color:#F2F2F2  ; padding: 50px 54px;">
                    <tr>
                        <td>
                            <h6 style="font-size: 16px; margin: 0;">Hello,</h6>
                            <p style="margin:8px 0; line-height:1.2;">{{$name}} Contact You.<br/>Details are</p>
                            <p style="margin:8px 0; line-height:1.2;">Name : {{$name}} </p>
                            <p style="margin:8px 0; line-height:1.2;">Email : {{$email}}</p>
                            <p style="margin:8px 0; line-height:1.2;">Message : {{$msg}}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>