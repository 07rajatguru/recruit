<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    // Home Page
    public function getIndex()
    {
        return view('index.home');
    }
    // Platform / Overview page
    public function getOverview(){

        return view('index.overview');
    }
    // Platform / Dashboard page
    public function getDashboard(){

        return view('index.dashboard');
    }
    // Platform / Features page
    public function getFeatures(){

        return view('index.features');
    }
    // Platform / Modules page
    public function getModules(){

        return view('index.modules');
    }
    // Benefits / Time Saver page
    public function getTimeSaver(){

        return view('index.time_saver');
    }
    // Benefits / Time Saver page
    public function getTransparent(){

        return view('index.transparent');
    }
    // Benefits / Time Saver page
    public function getDataInsight(){

        return view('index.data_insight');
    }
    // Coming Soon page
    public function getComingSoon(){

        return view('index.coming_soon');
    }
    // About Us Page
    public function getAboutUs(){

        return view('index.about_us');
    }
    // Career Page
    public function getCareers(){

        return view('index.careers');
    }
    // Contact Us Page
    public function getContactUs(){

        return view('index.contact_us');
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
