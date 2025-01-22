<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los usuarios
        $users = Users::all();
        return response()->json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Este método no es necesario en una API RESTful.
        // Normalmente se usaría para mostrar un formulario en una vista.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Crear un nuevo usuario
        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hasheo de la contraseña
        ]);

        return response()->json($user, 201); // Responde con el usuario recién creado
    }

    /**
     * Display the specified resource.
     */
    public function show(Users $user)
    {
        // Mostrar los detalles de un usuario específico
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Users $user)
    {
        // Este método no es necesario en una API RESTful.
        // Normalmente se usaría para mostrar un formulario en una vista.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Users $user)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Actualizar el usuario con los nuevos datos
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password); // Hasheo de la contraseña
        }

        $user->save();

        return response()->json($user, 200); // Responde con el usuario actualizado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users $user)
    {
        // Eliminar el usuario de la base de datos
        $user->delete();

        return response()->json(null, 204); // Responde con código 204 (sin contenido) indicando que fue eliminado
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
