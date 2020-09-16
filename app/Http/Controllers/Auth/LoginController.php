<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;
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
    protected $redirectTo = 'admin/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * [username Change login username to email]
     * @return [string] [return email field]
     */
    public function username()
    {
        return 'email';
    }
    
    public function login(Request $request)
    {
        $login=['password'=>$request->password,'email'=>$request->email];
        if(Auth::attempt($login))
        {
            if(Auth::user()->is_admin==false)
            {
                Auth::logout();
                Session::flush();
                return redirect('login')->with('error', 'Tài khoản của bạn không được phép truy cập.');
            }
            return redirect('/admin');
        }
        else
        {
            return redirect('/login')->with('error', 'Tài khoản hoặc mật khẩu không chính xác');
        }
    }
}
