<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body>
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Cambria; border-collapse: collapse; color: #444444;padding: 50px 54px;border:0" align="center">
        <tr>
            <td width="600">
                <p>
                <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                    <tr style="background-color: #7598d9">
                        <td align="center"style="font-family: Cambria;"><b>Sr.No.</b></td>
                        <td align="center"style="font-family: Cambria;"><b>Company Name</b></td>
                        <td align="center"style="font-family: Cambria;"><b>Contact Point</b></td>
                    </tr>
                    <?php $i=0; ?>
                    @foreach($client_info as $key => $value)
                        <tr>
                            <td align="center"style="font-family: Cambria;">{{ ++$i }}</td>
                            <td align="center"style="font-family: Cambria;">{{ $value['name'] }}</td>
                            <td align="center"style="font-family: Cambria;">{{ $value['coordinator_name'] }}</td>
                        </tr>
                    @endforeach
                </table>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>