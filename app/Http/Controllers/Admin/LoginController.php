<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginForm()
    {
        if (auth()->guard('admin')->check()) return redirect(route('admin-dashboard'));
        return view('auth.login-admin');
    }

    public function login(Request $request)
    {
        
        $this->validate($request, [
            'email' => 'required|email|exists:admins,email',
    		'password' => 'required|string'
        ]);

        $auth = $request->only('email','password');
        $auth['status'] = 1; #yg bisa login hanya status 1 (aktiv)

        $admin = DB::table('admins')->where('email', $request->email);

        $checkAdmin = $admin->count();
        if($checkAdmin == 0) return redirect()->back()->with(['error' => 'Email / Password Salah']); 
        
        #update remember_token
        $remember_token = Hash::make(md5(time().$request->password));
        $admin->update(['remember_token' => $remember_token]);

        #save token ke tabel token_management
        // HandleToken::storeToken($remember_token);

        #proses authentication
        if (auth()->guard('admin')->attempt($auth)) {


            return redirect()->intended(route('admin-dashboard'));

        }else{

            return redirect()->back()->with(['error' => 'Email / Password Salah']);
        }

        
    }

    public function logout()
    {
        $auth = auth()->guard('admin');

        #non aktifkan token
        // HandleToken::isActiveToken($auth->user()->remember_token);

        #logout
        $auth->logout();
        return view('auth.login-admin');
    }
}
