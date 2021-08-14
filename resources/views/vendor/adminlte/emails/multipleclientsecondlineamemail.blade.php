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
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <b><p style="margin-top: 0px; margin-bottom: 14px;">Dear {{ $second_line_am_name }},</p></b>
                            <p>Greetings !</p>
                            <p>Please note that you have been assigned as 2nd line coordinator to following clients :</p>
                            <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                <tr style="background-color: #7598d9">
                                    <td align="center" style="font-family: Cambria;"><b>Sr.No.</b></td>
                                    <td align="center" style="font-family: Cambria;"><b>Account Manager
                                    </b></td>
                                    <td align="center" style="font-family: Cambria;"><b>Company Name
                                    </b></td>
                                    <td align="center" style="font-family: Cambria;"><b>Contact Point
                                    </b></td>
                                </tr>
                                <?php $i=0; ?>
                                @foreach($client_info as $key => $value)
                                    <tr>
                                        <td align="center" style="font-family: Cambria;">
                                            {{ ++$i }}</td>
                                        <td align="center" style="font-family: Cambria;">
                                            {{ $value['am_name'] }}</td>
                                        <td align="center" style="font-family: Cambria;">
                                            {{ $value['name'] }}</td>
                                        <td align="center" style="font-family: Cambria;">
                                            {{ $value['coordinator_name'] }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <p>Please connect with respective account manager to understand more about the same.</p>
                            <p>Thanks.</p>
                            <p>Easy2Hire Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>