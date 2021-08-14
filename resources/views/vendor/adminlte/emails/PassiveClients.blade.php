<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<style>
    .cvs_td_top{
        border-top: black 1px solid;
        padding: 8px;
    }
    .cvs_td_right{
        border-right: black 1px solid;
    }
    .cvs_td_bottom{
        border-bottom: black 1px solid;
    }
    .cvs_td_left{
        border-left: black 1px solid;
    }

</style>

<body>
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td>
                <table  width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 54px;">
                    <tr>
                        <td colspan="7">
                            <u>
                                <b><h1>No of expected passive clients in next week : {{ $clients_count or '0' }}</h1></b>
                            </u>
                        </td>
                    </tr>

                    <tr style="background-color:#7598d9">
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Sr.<br/>No.</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Client Owner</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Company Name</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;"><b>Contact Point</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                            <b>Client Category</b></td>
                        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                            <b>Client Address</b></td>
                    </tr>

                    <?php $i=1; ?>
                    @foreach($client_res as $key => $value)
                        <tr colspan="7">
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $i }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $value['account_manager'] }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $value['name'] }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $value['coordinator'] }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $value['category'] }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">
                                {{ $value['address'] }}
                            </td>
                        </tr>
                    <?php $i++; ?>
                    @endforeach
                </table>
            </td>
        </tr>             
    </table>
</body>
</html>