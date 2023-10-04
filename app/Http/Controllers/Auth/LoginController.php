<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\UsersLog;
use Illuminate\Http\Request;
// use App\Trackerlog;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials, $request->has('remember')))
        {
            $user = \Auth::user();
            
            // Tracker log entry
            //TrackerLog::getInsertTrackerLog($user->id,'logged-in successfully.');

            // For set Session Variable
            $new_sessionid = \Session::getId(); //get new session_id after user sign in

            if($user->session_id != '') {
                $last_session = \Session::getHandler()->read($user->session_id); 

                if ($last_session) {
                    if (\Session::getHandler()->destroy($user->session_id)) {
                        
                    }
                }
            }

            \DB::table('users')->where('id', $user->id)->update(['session_id' => $new_sessionid]);
            // Ending of Session Variable

            $user_status = $user->status;
            if ($user_status == 'Inactive') {
                \Auth::logout();

                // Tracker log entry
                //TrackerLog::getInsertTrackerLog($user->id,'User status inactive error');

                return redirect('/login')
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'This user currently inactive',
                    ]);
            }
            //$user_id = \Auth::user()->id;

            $type = $user->type;
            if ($type == 'client') {
                // Entry of login
                $users_log= new UsersLog();
                $users_log->user_id = $user->id;
                $users_log->date = gmdate("Y-m-d");
                $users_log->time = gmdate("H:i:s");
                $users_log->type ='login';
                $users_log->created_at = gmdate("Y-m-d H:i:s");
                $users_log->updated_at = gmdate("Y-m-d H:i:s");
                $users_log->save();

                return redirect('/jobs');
            }
            else {
                // Entry of login
                $users_log= new UsersLog();
                $users_log->user_id = $user->id;
                $users_log->date = gmdate("Y-m-d");
                $users_log->time = gmdate("H:i:s");
                $users_log->type ='login';
                $users_log->created_at = gmdate("Y-m-d H:i:s");
                $users_log->updated_at = gmdate("Y-m-d H:i:s");
                $users_log->save();

                return redirect('/dashboard');
            }
            //return redirect()->intended($this->redirectPath());
        }

        // Tracker log entry
        //TrackerLog::getInsertTrackerLog(0,'Credentials do not match.');

        return redirect('/login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'These credentials do not match our records',
            ]);
    }

    /*public function redirectPath(){

        $user = \Auth::user();

        if(isset($user->id) && $user->id>0){
            //$user_id = \Auth::user()->id;

            // Entry of login
            $users_log= new UsersLog();
            $users_log->user_id = $user->id;
            $users_log->date = gmdate("Y-m-d");
            $users_log->time = gmdate("H:i:s");
            $users_log->type ='login';
            $users_log->created_at = gmdate("Y-m-d H:i:s");
            $users_log->updated_at = gmdate("Y-m-d H:i:s");
            $users_log->save();

            return redirect('/');
        }
    }*/

    public function logout(Request $request)
    {
        $user_id = \Auth::user()->id;
        
        // Tracker log entry
        //TrackerLog::getInsertTrackerLog($user_id,'logged-out successfully.');

        // Entry of login
        $users_log= new UsersLog();
        $users_log->user_id = $user_id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='logout';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();

        \Session::flush();
        //\Session::put('success','you are logout Successfully');
        \Auth::logout();

        // Update in work planning table

        $date = date('Y-m-d');
        $time = date('H:i:s');

        \DB::statement("UPDATE `work_planning` SET `loggedout_time` = '$time' WHERE `added_date` = '$date' AND `added_by` = '$user_id'");

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
