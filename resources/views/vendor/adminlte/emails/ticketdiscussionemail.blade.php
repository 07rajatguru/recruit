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
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 50px 54px;">
                        <tr>
                            <td>
                                <b><p style="text-align: left;">Dear {{ $ticket_res['added_by'] }},</p></b>
                                <p style="text-align: left;">Greetings from Easy2Hire!</p>
                                <p style="text-align: left;">This is to inform that you have added a ticket under the {{ $ticket_res['module_name'] }} on {{ $ticket_res['added_date'] }} with the Ticket No as {{ $ticket_res['ticket_no'] }}. The reason for applying the ticket and its related screenshot can be viewed on E2H directly. For your reference, snapshot is also attached with the ticket. </p>
                                <p style="text-align: left;"><b>Question Type : </b> {{ $ticket_res['question_type'] }} </p>
                                <p style="text-align: left;"><b>Description : </b> {{ $ticket_res['description'] }} </p>
                                <p style="text-align: left;">You can expect a solution on the same in the next 48-72 hours. If you do not receive any communication, feel free to connect with Kazvin/Hemali over mail.</p>
                                <p style="text-align: left;">Thanks. <br/> Easy2Hire Team</p>

                                <!-- <p style="margin:8px 0; line-height:1.5;"><b>Ticket No. : </b> 
                                {{ $ticket_res['ticket_no'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Module Name : </b> 
                                {{ $ticket_res['module_name'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Ticket Status : </b> 
                                {{ $ticket_res['status'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Question Type : </b> 
                                {{ $ticket_res['question_type'] }} </p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Description : </b> 
                                {{ $ticket_res['description'] }}</p>
                                <p style="margin:8px 0; line-height:1.5;"><b>Ticket Added By : </b> 
                                {{ $ticket_res['added_by'] }}</p> -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>