<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td>
                            <h4 style="margin: 0;line-height:1.4;">Greetings from Adler Talent Solutions !</h4><br/>

                            @if(isset($email_template_details) && $email_template_details != '')
                                <p style="margin:8px 0; line-height:1.4;"><b>Name : </b>{{ $email_template_details['name'] }} </p>
                                <p style="margin:8px 0; line-height:1.4;"><b>Subject : </b>{{ $email_template_details['subject'] }}</p>
                                <p style="margin:8px 0; line-height:1.4;"><b>Email Body : </b>{!! $email_template_details['email_body'] !!}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>