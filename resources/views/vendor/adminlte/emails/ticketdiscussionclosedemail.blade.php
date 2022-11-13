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
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 50px 54px;">
                        <tr>
                            <td>
                                <b><p style="text-align: left;">Dear {{ $ticket_res['added_by'] }},</p></b>
                                <p style="text-align: left;">Greetings from Easy2Hire!</p>
                                <p style="text-align: left;">This is to inform that your ticket {{ $ticket_res['ticket_no'] }} has been resolved by the IT team. Request you to verify and update regarding the same.</p>
                                <p style="text-align: left;">Thanks. <br/> Easy2Hire Team</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>