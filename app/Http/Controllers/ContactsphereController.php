<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contactsphere;
use App\User;
use App\Lead;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;

class ContactsphereController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');

        if($all_perm) {
        
            $count = Contactsphere::getAllContactsCount(1,$user->id);

            $convert_lead_count = Contactsphere::getConvertedLead(1,$user->id);
        }
        else if($userwise_perm) {

            $count = Contactsphere::getAllContactsCount(0,$user->id);

            $convert_lead_count = Contactsphere::getConvertedLead(0,$user->id);
        }

        return view('adminlte::contactsphere.index',compact('count','convert_lead_count'));
    }
}