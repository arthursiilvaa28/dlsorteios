<?php

namespace App\Http\Controllers\Site\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()){
            if (Auth::user()->type == 'admin') {
                return redirect()->route('admin/sorteio/index');
            } 
            return Redirect::route('user/index');
        }
        return view('Site/Account/Login/index');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->type == 'admin') {
                return redirect()->route('admin/sorteio/index');
            } 
            return redirect()->intended('/');
        }
        return back()->withInput()->withErrors(['error' => 'Usuário ou senha incorretos !']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
