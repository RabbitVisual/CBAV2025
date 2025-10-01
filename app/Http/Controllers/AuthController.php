<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirecionamento inteligente baseado no papel do usuário
            if ($user->hasRole('Super Admin') || $user->hasRole('Pastor') || $user->hasRole('Líder')) {
                // Administradores vão para o dashboard principal
                return redirect()->intended('/admin');
            } elseif ($user->hasRole('Tesoureiro')) {
                // Tesoureiro vai para a gestão financeira
                return redirect()->intended('/admin/finance');
            } elseif ($user->hasRole('Membro')) {
                // Membros vão para a área de membros
                return redirect()->intended('/member');
            } else {
                // Fallback para o dashboard principal
                return redirect()->intended('/admin');
            }
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
