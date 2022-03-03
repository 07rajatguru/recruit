<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                <b><p style="text-align: left;">Dear {{ $rm_name }},</p></b>

                <p style="text-align: left;">This is to inform you that {{ $user_name }} is going on leave from {{ $from_date }} to {{ $to_date }}.</p>

                <p style="text-align: left;">Request you to schedule a handover call and take a download on the positions as well as clients before he/she goes for the leave.
                </p>

                <p style="text-align: left;">Thanks.<br/>Easy2Hire Team.</p>
            </td>
        </tr>
    </table>
</body>
</html>