<?php

namespace App\Http\Controllers\Admin;

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
        
        #update remember_token
        $remember_token = Hash::make(md5(time().$request->password));
        DB::table('admins')->where('email', $request->email)->update(['remember_token' => $remember_token]);

        #proses authentication
        if (auth()->guard('admin')->attempt($auth)) {


            return redirect()->intended(route('admin-dashboard'));
        }



        return redirect()->back()->with(['error' => 'Email / Passwords Salah']);
        
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect(route('admin-login'));
    }
}
