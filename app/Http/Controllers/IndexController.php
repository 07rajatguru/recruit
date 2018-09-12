<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{

    public function index()
    {
        return view('index.index');
    }

    public static function sendMail()
    {
    	$name=Input::get('name');
    	$email=Input::get('email');
    	$subject=Input::get('subject');
		$msg=Input::get('message');

		$to=getenv('CONTACTMAIL_TO');
		$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
		$input['to']=$to;
		$input['name']=$name;
		$input['email']=$email;
		$input['subject']=$subject;
		$input['msg']=$msg;

		\Mail::send('adminlte::emails.contactus', $input, function ($message) use ($input) 
		{
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->subject( $input['subject']);
        });

        return redirect('/index');
    }
}
