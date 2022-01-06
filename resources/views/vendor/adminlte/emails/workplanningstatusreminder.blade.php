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

                <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>

                <p style="text-align: left;">Greetings from Easy2Hire!</p>

                <p style="text-align: left;">This is to remind you about your Work Planning status for the date of {{ $previous_date }}. You are hereby requested to do the needful before 10.59 AM.</p>

                <p style="text-align: left;">Please note that system will send the delayed work planning report post 11 AM in case if you fail to submit the same on time.</p>

                <p style="text-align: left;">Your earliest action will be highly appreciated.</p>
                
                <p style="text-align: left;">Thanks.<br/>Easy2Hire Team</p>
            </td>
        </tr>
    </table>
</body>
</html>