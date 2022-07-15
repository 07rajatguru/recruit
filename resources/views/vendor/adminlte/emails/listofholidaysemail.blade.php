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

                    <i><p style="text-align: left;">Greetings !</p></i>

                    <p style="text-align: left;">Please find attached the List of Holidays document for your reference. Request you to go through the same and select your optional holidays in E2H as per the eligibility criteria (mentioned in the same PDF).</p>

                    <p style="text-align: left;">Also, post updating us with your optional leaves; you will be required to send an email a week prior intimating your reporting manager regarding your approaching optional leave.</p>

                    <p style="text-align: left;">In case of any further queries, Please feel free to get in touch with your reporting manager / HR Team.</p>
                    
                    <p style="text-align: left;">Thanks.<br/>Easy2Hire Team</p>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
            <tr>
                <td align="center" style="padding: 0px;">
                    <a style="border: black; background-color: #00c0ef;color: white;padding: 15px 50px 15px 50px; border-radius: 18px;font-size: 14px;width: 59%;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/list-of-holidays/'.$module_id }}">Select Option</a>
                </td>
            </tr>
        </table>
    </body>
</html>