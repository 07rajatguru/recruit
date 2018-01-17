<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\UsersLog;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function redirectPath(){

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

            return redirect('/home');
        }
    }

    public function logout(Request $request)
    {
        $user_id = \Auth::user()->id;
        // Entry of login
        $users_log= new UsersLog();
        $users_log->user_id = $user_id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='logout';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();

        \Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
