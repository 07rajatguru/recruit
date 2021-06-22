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
                <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                <p style="text-align: left;"><u>Please add following users salary information:</u></p>
                <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                        <td align="center"><b>Sr. No.</b></td>
                        <td align="center"><b>User Name</b></td>
                        <td align="center"><b>User Email</b></td>
                    </tr>

                    <?php $i=0; ?>
                    @foreach($users_array as $key => $value)
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="center">{{ ++$i }}</td>
                            <td align="center">{{ $value['name'] }}</td>
                            <td align="center">{{ $value['email'] }}</td>
                        </tr>
                    @endforeach
                </table>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
    </table>
</body>
</html>