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
                <td width="600">
                    <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td><b>Subject :</b> {!! $work_from_home_res['subject'] !!}</td>
                        </tr>

                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td><br/><b>Reason :</b> {!! $work_from_home_res['reason'] !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="600">
                    <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                        <tr><td>{!! $signature !!}</td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="600">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                        <tr>
                            <td align="center" style="padding: 0px;">
                                <a style="border: black; background-color: skyblue;color: white;padding: 10px 20px 10px 20px; border-radius: 50px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/work-from-home/'.$work_from_home_res['id'].'/show'}}">Reply</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>