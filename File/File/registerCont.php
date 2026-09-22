<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Admin\Sekolah;
use Illuminate\Support\Facades\Hash;
class registerCont extends Controller
{
    //
    public function index()
    {
        $sekolah=Sekolah::all();
    	return view('portal.signup.signup',compact('sekolah'));
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            'nama'    => 'required',
            'telp'   => 'required',
            'email' => 'required|email',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);
        $password=$request->password;
        $password1=Hash::make($password);
        User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'password'   => $password1,
            'level'   => 'MEMBER',
            'aktif'   => 'Y',
            'kontak'   => $request->telp,
            'id_sekolah'=>$request->sekolah,
        ]);
        return redirect()->route('register')->with(['success' => 'Registrasi berhasil.']);
    }
    
    public function reset() {
        return view('portal.signup.reset');
    }
    
    public function resetPassword(Request $request){
         $user = User::where("email", $request->email)->first();
        
        if ($user == null) {
             return redirect()->route('reset')->with(['danger' => 'Email tidak ditemukan.']);
        }
        
       $user->update([
            'password'   => bcrypt($request->password),
        ]);
        
         return redirect()->route('signin')->with(['success' => 'Reset Password berhasil.']);
    }
}
