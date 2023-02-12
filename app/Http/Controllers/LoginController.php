<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            return redirect()->intended('home');
        } else {
            return view('pages.login');
        }
    }
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // dd($request->all());

        // $credentials = $request->validate([
        //     'user_email' => ['required', 'email'],
        //     'user_password' => ['required'],
        // ]);

        $credentials = array('user_email' => $request->user_email, 'user_password' => $request->user_password);
        $user = Users::where('user_email', '=', $credentials['user_email'])->where('user_password', '=', $credentials['user_password'])->first();
        if ($user) {
            if (Auth::loginUsingId($user->user_id, $remember = true)) {
                $request->session()->regenerate();
                return redirect()->intended('home');
            }
            // if (Auth::attempt($credentials)) {
            //     $request->session()->regenerate();
            //     return redirect()->intended('dashboard');
            // }
        }

        return back()->with('error', 'Login Gagal');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}