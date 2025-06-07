<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAuthData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);
        $authData = UserAuthData::where('login', $credentials['login'])->first();
        
        if (!$authData) {
            return back()->withErrors([
                'login' => 'Неверный логин или пароль.'
            ])->withInput();
        }
        if (!password_verify($credentials['password'], $authData->password)) {
            return back()->withErrors([
                'login' => 'Неверный логин или пароль.'
            ])->withInput();
        }

        Auth::loginUsingId($authData->user_id);
        $request->session()->regenerate();

        return redirect()->intended('/')
            ->with('success', 'Вы успешно вошли в систему!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Вы успешно вышли из системы.');
    }
}