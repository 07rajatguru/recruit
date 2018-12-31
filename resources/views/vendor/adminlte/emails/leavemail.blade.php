<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
@foreach($mail as $key => $value)
    @if($value['module'] == 'Leave')
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
            <td width="600" >
                <table  cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                    <tr>
                        <td>
                            <b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Hello, </p></b>
                            <p>{!! $leave_message !!}</p>
                            <p>Thanks & Regards,</p>
                            <p>{{ $logged_in_user_nm }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
            <tr>
                <td align="center" style="padding: 0px;">
                    <a style="border: black; background-color: skyblue;color: white;padding: 10px 20px 10px 20px; border-radius: 50px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/leave/reply/'.$leave_id}}">Reply</a>
                </td>
            </tr>
        </table>
        

        <tr width="600" style="height: 45px; background-color: #dddddd;">
            <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
        </tr>
    </table>
    @else
        {!! $value['message'] !!}
    @endif
@endforeach
</body>
</html>