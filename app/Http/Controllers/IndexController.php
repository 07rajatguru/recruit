<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\ContactUs;
use App\DemoRequest;

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

    // Demo Request Page
    public function getDemoRequest()
    {
        return view('index.request_demo');
    }

    public static function sendMail()
    {
    	$name=Input::get('name');
    	$email=Input::get('email');
    	$subject=Input::get('subject');
		$msg=Input::get('message');
        $check=Input::get('check');

        $contact_us = new ContactUs();
        $contact_us->name = $name;
        $contact_us->email = $email;
        $contact_us->subject = $subject;
        $contact_us->message = $msg;
        $contact_us->save();

		$to=getenv('CONTACTMAIL_TO');
		$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
		$input['to']=$to;
		$input['name']=$name;
		$input['email']=$email;
		$input['subject']=$subject;
		$input['msg']=$msg;
        $input['app_url'] = $app_url;

		\Mail::send('adminlte::emails.contactus', $input, function ($message) use ($input) 
		{
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject( $input['subject']);
        });

        if(isset($check) && $check != '')
        {
            return redirect('/contact_us');
        }
        else
        {
            return redirect('/index');
        }
    }

    public static function sendDemoRequest()
    {
        $name = Input::get('name');
        $email = Input::get('email');
        $phone = Input::get('phone');
        $company = Input::get('company');
        $preferred_date = Input::get('preferred_date');
        $preferred_time = Input::get('preferred_time');

        $demo_request = new DemoRequest();
        $demo_request->name = $name;
        $demo_request->email = $email;
        $demo_request->phone = $phone;
        $demo_request->company = $company;
        $demo_request->preferred_date = $preferred_date;
        $demo_request->preferred_time = $preferred_time;

        $demo_request->save();

        $to=getenv('CONTACTMAIL_TO');
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to']=$to;
        $input['subject']="Demo Request";
        $input['name']=$name;
        $input['email']=$email;
        $input['phone']=$phone;
        $input['company']=$company;
        $input['preferred_date']=$preferred_date;
        $input['preferred_time']=$preferred_time;
        $input['app_url'] = $app_url;

        \Mail::send('adminlte::emails.demorequest', $input, function ($message) use ($input) 
        {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject( $input['subject']);
        });

        return redirect('/demo_request');
    }
}
