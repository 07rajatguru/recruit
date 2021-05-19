<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
    @foreach($mail as $key => $value)
        @if($value['module'] == 'Leave')
        <table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                        <tr>
                            <td align="center">
                                <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                    <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                        <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px;vertical-align: middle;">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                        <tr>
                            <td>
                                <br/><b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Hello, </p></b>
                                <p>{!! $leave_message !!}</p>
                                <p>Thanks & Regards,</p>
                                <p>{{ $logged_in_user_nm }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                        <tr>
                            <td align="center" style="padding: 0px;">
                                <a style="border: black; background-color: skyblue;color: white;padding: 10px 20px 10px 20px; border-radius: 50px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/leave/reply/'.$leave_id}}">Reply</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="height: 45px; background-color: #dddddd;">
                <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
            </tr>
        </table>
        @else
            {!! $value['message'] !!}
        @endif
    @endforeach
</body>
</html>