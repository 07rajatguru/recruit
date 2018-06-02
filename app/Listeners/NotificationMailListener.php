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
        $user_id = \Auth::user()->id;

        $module = $event->module;
        $sender_name = $event->sender_name;
        $to = $event->to;
        $subject = $event->subject;
        $message = $event->message;

        $header = '<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: rgba(157,92,172,0.9); height: 70px;">
                            <tr>
                                <td align="center">
                                    Adler Talent Solutions Pvt. Ltd.
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
        $emailnotification->subject = $subject;
        $emailnotification->message = $body;
        $emailnotification->save();
    }
}
