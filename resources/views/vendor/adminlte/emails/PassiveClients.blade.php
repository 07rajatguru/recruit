<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent Solutions Pvt. Ltd.</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
            <tr>
                <td width="600">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <p><u>Passive Client List : {{ $clients_count or '0' }}</u></p>
                                <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9;">
                                        <td align="center"><b>Sr.No.</b></td>
                                        <td align="center"><b>Company Name</b></td>
                                        <td align="center"><b>Contact Point</b></td>
                                        <td align="center"><b>Client Owner</b></td>
                                        <!-- <td align="center"><b>Client Category</b></td>
                                        <td align="center"><b>Location</b></td> -->
                                    </tr>
                                    <?php $i=0; ?>
                                    @foreach($client_res as $key => $value)
                                        <tr>
                                            <td align="center">{{ ++$i }}</td>
                                            <td align="center">{{ $value['name'] }}</td>
                                            <td align="center">{{ $value['coordinator'] }}</td>

                                            @if($value['account_manager'] != '')
                                                <td align="center">{{ $value['account_manager'] }}</td>
                                            @else
                                                <td align="center">Yet to Assign</td>
                                            @endif
                                            <!-- <td align="center">{{ $value['category'] }}</td>
                                            <td align="center">{{ $value['address'] }}</td> -->
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>