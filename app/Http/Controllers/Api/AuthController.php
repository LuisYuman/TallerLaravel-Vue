<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo usuario
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:150',
            'email'    => 'required|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $usuario = Usuario::create($validated);

        // Iniciar sesión inmediatamente (opcional)
        auth()->login($usuario);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'usuario' => $usuario,
            'auth_via' => 'session-cookie'
        ], 201);
    }

    /**
     * Login de usuario existente
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas.'],
            ]);
        }

        // Autenticar mediante sesión (Sanctum SPA)
        auth()->login($usuario);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login exitoso',
            'usuario' => $usuario,
            'auth_via' => 'session-cookie'
        ]);
    }

    /**
     * Formulario de login para testing desde navegador
     */
    public function loginForm()
    {
        return response()->json([
            'message' => 'Este endpoint requiere POST. Usa Postman o curl para hacer login.',
            'endpoint' => 'POST /api/login',
            'example' => [
                'email' => 'isaac@isaac.com',
                'password' => '123456'
            ]
        ]);
    }

    /**
     * Logout (revocar tokens)
     */
    public function logout(Request $request)
    {
        // Cerrar sesión de la forma recomendada
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout exitoso'
        ]);
    }
}
