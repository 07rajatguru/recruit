<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
    <tr>
        <td width="600" >
            <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr style="background-color:white;">
                    <td align="center">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img width="600" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 50px 54px;">
                <td colspan="7">
                    <h3>Basic Information</h3>
                </td>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Contact Point</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['coordinator_name'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Display Name</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['display_name'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Contact Number</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['mobile'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Email</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['mail'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Account Manager</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['am_name'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Website</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['website'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Client Status</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['status'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Industry</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['ind_name'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">About</th>
                    <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">{!! $client['description'] !!}</td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                <td colspan="7">
                    <h3>Address Information</h3>
                </td>
                <tr>
                    <th align="center" colspan="2" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Address 1</th>
                    <td align="center" colspan="2" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">Address 2</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Street Address</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['billing_street'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Street Address</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['billing_street'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">City</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['billing_city'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">City</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['shipping_city'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">State</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['billing_state'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">State</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['shipping_state'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Country</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['billing_country'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Country</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $client['shipping_country'] !!}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Code</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">{!! $client['billing_code'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;">Code</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">{!! $client['shipping_code'] !!}</td>
                </tr>
            </table>

            @if(isset($module) && $module == 'Client Delete')

            @else
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                    <tr>
                        <td align="center" style="padding: 0px;">
                            <a style="border: black; background-color: #9c5cac;color: white;padding: 15px 50px 15px 50px; border-radius: 18px;font-size: 15px;width: 59%;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/client/'.$module_id}}">Show</a>
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>

    <tr width="600" style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>
</body>
</html>