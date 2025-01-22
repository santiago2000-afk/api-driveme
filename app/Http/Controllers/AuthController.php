<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de usuario.
     */
    public function register(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Usar Hash para almacenar la contraseña
        ]);

        // Generar el token para el nuevo usuario
        $token = $user->createToken('AppName')->plainTextToken;

        // Responder con los datos del usuario y el token
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Iniciar sesión de usuario.
     */
    public function login(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Verificar las credenciales del usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Generar un nuevo token
            $token = $user->createToken('AppName')->plainTextToken;

            // Responder con los datos del usuario y el token
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Si las credenciales son incorrectas
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Cerrar sesión del usuario.
     */
    public function logout(Request $request)
    {
        // Invalida el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Obtener el perfil del usuario autenticado.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
