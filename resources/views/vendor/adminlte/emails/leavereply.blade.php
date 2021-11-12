<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;">
            <tr>
                <td width="600">
                    <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">

                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td>{!! $leave_message !!}</td>
                        </tr>

                        @if(isset($remarks) && $remarks != '')
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td><br/><b>Remarks :</b></td>
                            </tr>

                            <tr tyle="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td><br/>{!! $remarks !!}</td>
                            </tr>
                        @endif

                        <tr><td>{!! $signature !!}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>