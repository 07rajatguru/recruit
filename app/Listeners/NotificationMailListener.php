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

        $header = '<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
               <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                            <tr>
                                <td align="center">
                                    <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                            <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px; vertical-align: middle;">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
               </tr>';


        $footer = '<tr style="height: 45px; background-color: #dddddd;">
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent. All rights reserved</td>
                    </tr>
                </table>';

        $body = $header.$message.$footer;

        $emailnotification = new EmailsNotifications();
        $emailnotification->module = $module;
        $emailnotification->sender_name = $sender_name;
        $emailnotification->to = $to;
        $emailnotification->cc = $cc;
        $emailnotification->subject = $subject;
        $emailnotification->message = $body;
        $emailnotification->module_id = $module_id;
        $emailnotification->save();
    }
}
