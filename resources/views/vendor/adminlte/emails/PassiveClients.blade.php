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
            <td width="600">
                <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr style="background-color:white;">
                        <td align="center">
                            <div class="site-branding col-md-2 col-sm-6 col-xs-12">
                                <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px; vertical-align: middle;">
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table  width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 54px;">
                    <tr>
                        <td colspan="7">
                            <u>
                                <b><h1>No of Passive Clients : {{ $clients_count or '0' }}</h1></b>
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
                                <?php
                                    $coordinator = $value['coordinator_prefix'] . $value['coordinator_name'];
                                ?>
                                {{ $coordinator }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;">
                                {{ $value['category'] }}
                            </td>
                            <td align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;border-left: black 1px solid;border-right: black 1px solid;">
                                <?php
                                    $address ='';
                                    if($value['area'] != '')
                                    {
                                        $address .= $value['area'];
                                    }
                                    if($value['city'] != '')
                                    {
                                        if($address=='')
                                            $address .= $value['city'];
                                        else
                                            $address .= ", ".$value['city'];
                                    }
                                ?>
                                {{ $address }}
                            </td>
                        </tr>
                    <?php $i++; ?>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr style="height: 45px; background-color: #dddddd;">
            <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved.
            </td>
        </tr>                   
    </table>
</body>
</html>