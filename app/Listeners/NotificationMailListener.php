<?php

namespace App\Listeners;

use App\Events\NotificationMail;
use App\EmailsNotifications;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class NotificationMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationMail  $event
     * @return void
     */
    public function handle(NotificationMail $event)
    {
        //
        //$user_id = \Auth::user()->id;

        $module = $event->module;
        $sender_name = $event->sender_name;
        $to = $event->to;
        $cc = $event->cc;
        $subject = $event->subject;
        $message = $event->message;
        $module_id = $event->module_id;
        
        $app_url = getenv('APP_URL');
        $yr = date('Y');
     
        $header = '<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
               <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                            <tr style="background-color:white;">
                                <td align="center">
                                    <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                            <img width="600" class="site-logo"  src="'.$app_url.'/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
               </tr>';


        $footer = '<tr style="height: 45px; background-color: #dddddd;">
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent '.$yr.'. All rights reserved</td>
                    </tr>
                </table>';

        $body = $header.$message.$footer;

        $emailnotification = new EmailsNotifications();
        $emailnotification->module = $module;
        $emailnotification->sender_name = $sender_name;
        $emailnotification->to = $to;
        $emailnotification->cc = $cc;
        $emailnotification->subject = $subject;

        if($module == "Client Bulk Email" || $module == "Lead Bulk Email" | $module == "Contact Bulk Email") {

            $emailnotification->message = $message;
        }
        else {

            $emailnotification->message = $body;
        }
        $emailnotification->module_id = $module_id;
        $emailnotification->save();
    }
}
