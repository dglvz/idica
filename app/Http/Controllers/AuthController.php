<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Procesa el intento de login.
     */
    public function login(Request $request)
    {
         $credentials = $request->validate([
            'nombre' => ['required', 'string'],
            'clave'  => ['required', 'string'],
        ]);

        // Auth::attempt espera 'password' como llave para la contraseña.
        // Pasamos 'clave' del request mapeada a 'password'.
        if (Auth::attempt(['nombre' => $request->nombre, 'password' => $request->clave])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirección basada en el rol
            if ($user->rol === 'admin') {
                return redirect()->route('usuarios.index');
            }

            // Vista por defecto para otros roles (ej. usuario)
            return redirect()->route('inicio'); // Asegúrate de tener una ruta llamada 'inicio'
        }

        return back()->withErrors([
            'nombre' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('nombre');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}