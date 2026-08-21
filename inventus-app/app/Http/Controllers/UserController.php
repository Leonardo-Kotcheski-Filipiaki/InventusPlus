<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class UserController extends Controller
{
    public function showLogin()
    {
        $erros = Session::get('error');
        return Inertia::render("user/login", [
            'user' => new User(),
            'erros' => $erros
        ]);
    }

    public function authenticate(Request $request)
    {
        
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ], [
            'login.required' => 'Usuário é obrigatório',
            'password.required' => 'Senha é obrigatória'
        ]);

        if(!Auth::attempt($credentials)) return redirect()->back()->withErrors('Usuário ou senha inválida!', 'error');

        $request->session()->regenerate();
        

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
