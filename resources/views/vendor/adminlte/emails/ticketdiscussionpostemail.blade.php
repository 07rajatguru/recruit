<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
        <table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr>
                        <td align="center">
                           <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                    <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px;   vertical-align: middle;">
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
                        <tr>
                            <td>
                                <p style="margin:8px 0; line-height:1.5;"><b>Module Name : </b> 
                                {{ $ticket_res['module_name'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Ticket Status : </b> 
                                {{ $ticket_res['status'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Question Type : </b> 
                                {{ $ticket_res['question_type'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Comment : </b> 
                                {{ $post_res['content'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Comment Added By : </b>
                                {{ $post_res['added_by'] }} </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr style="height: 45px; background-color: #dddddd;">
                <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved.</td>
            </tr>
        </table>
    </body>
</html>