<?php

namespace App\Http\Controllers\Site\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()){
            if (Auth::user()->type == 'admin') {
                return Redirect::route('admin/index');
            } 
            return Redirect::route('user/index');
         }
        return view('Site/Account/Register/index');
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'size: 11', 'regex:/(^[0-9]+$)+/', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:5', 'max:10'],
            'password_confirmation' => ['required']
        ], [
            'required' => 'Esse campo não pode está vazio',
            'name.min' => 'Preencha com um nome válido',
            'name.max' => 'Preencha com um nome válido',
            'phone.size' => 'Telefone inválido.',
            'phone.regex' => 'Telefone inválido.',
            'phone.unique' => 'Telefone já utilizado, use outro telefone.',
            'email.email' => 'Digite um email válido.',
            'email.unique' => 'Email já cadastrado, utilize outro.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.min' => 'A senha deve ter entre 5 e 10 caractere.',
            'password.max' => 'A senha deve ter entre 5 e 10 caractere.' 
        ]);
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'phone' => $credentials['phone'],
            'type' => 'user',
        ]);
        
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('user/index');
    }
}
