<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent Solution Pvt. Ltd.</title>
    @yield('style')
</head>

<body>
    @foreach($mail as $key => $value)
        @if($value['module'] == 'Leave')
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;">
                <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                            <tr style="background-color:white;">
                                <td colspan="2"></td>
                                <td align="center">
                                    <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                            <img width="600" class="site-logo" src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                        </a>
                                    </div>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                                   <p>{!! $leave_message !!}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                            <tr>
                                <td style="padding: 0px;">
                                    <a style="border: black; background-color: skyblue;color: white;padding: 10px 20px 10px 20px; border-radius: 50px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/leave/reply/'.$leave_id}}">Reply</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0;">
                            <tr><td style="padding: 20px;">{!! $signature !!}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        @else
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;">
                <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                            <tr style="background-color:white;">
                                <td colspan="2"></td>
                                <td align="center">
                                    <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                            <img width="600" class="site-logo" src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                        </a>
                                    </div>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <p>{!! $leave_message !!}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="600">
                        <table lpadding="0" cellspacing="0" style="border:0;">
                            <tr><td style="padding: 20px;">{!! $signature !!}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        @endif
    @endforeach
</body>
</html>