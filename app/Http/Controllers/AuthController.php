<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name'     => 'required|string|max:60',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = User::create($datos);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('animales.index')
            ->with('ok', "Bienvenido, {$usuario->name}.");
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function entrar(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credenciales)) {
            return back()
                ->withErrors(['email' => 'Esas credenciales no coinciden con ningún usuario.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('animales.index');
    }

    public function salir(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
