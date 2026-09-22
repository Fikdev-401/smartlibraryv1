<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Validator;
class signinCont extends Controller
{
    //
    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function show()
    {
        return view('portal.signup.signin');
    }
    public function post(Request $request)
    {
        $this->validate($request, [
            // 'g-recaptcha-response' => 'required|captcha',
        ]);
        if(Auth::guard('user')->attempt(['email'=>$request->email,'password'=>$request->password,'aktif'=>'Y'], $request->remember)){
            if(Auth::guard("user")->user()->level=="SUPERUSER") {
                return redirect()->route('dev.home');
            } else if(Auth::guard("user")->user()->level=="ADMIN") {
                return redirect()->route('dev.home');
            } else if(Auth::guard("user")->user()->level=="OPERATOR") {
                return redirect()->route('dev.home');
            } else if(Auth::guard("user")->user()->level=="MEMBER") {
                return redirect()->route('dashboard');
            }
            else {
                 //return redirect()->route('dashboard');
            }
        }
        return redirect()->route('signin')->with(['warning' => 'Email atau password tidak terdaftar!']);;
    }
    public function logout() {
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
        }
        return redirect('/');
    }
}
