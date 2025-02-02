<?php

namespace App\Http\Controllers;

use App\Models\Viajes;
use Illuminate\Http\Request;

class ViajesController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtén todos los conductores y retorna una respuesta JSON
        $viajes = Viajes::all();
        return response()->json($viajes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Este método no es necesario en una API RESTful,
        // normalmente se usaría para mostrar un formulario en una vista
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:drivers,phone',
            'vehicle_id' => 'required|integer|exists:vehicles,id', // Asegúrate de tener la tabla 'vehicles' con un campo 'id'
            'availability' => 'required|in:Available,Busy',
            'image_url' => 'nullable|url',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Crear el nuevo conductor
        $driver = Drivers::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'vehicle_id' => $request->vehicle_id,
            'availability' => $request->availability,
            'image_url' => $request->image_url,
        ]);

        return response()->json($driver, 201); // Responde con el conductor creado
    }

    /**
     * Display the specified resource.
     */
    public function show(Drivers $driver)
    {
        // Mostrar los detalles de un conductor específico
        return response()->json($driver, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Drivers $driver)
    {
        // Este método no es necesario en una API RESTful,
        // normalmente se usaría para mostrar un formulario en una vista
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Drivers $driver)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:20|unique:drivers,phone,' . $driver->id,
            'vehicle_id' => 'sometimes|integer|exists:vehicles,id',
            'availability' => 'sometimes|in:Available,Busy',
            'image_url' => 'nullable|url',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Actualizar los detalles del conductor
        $driver->update($request->only([
            'name', 
            'phone', 
            'vehicle_id', 
            'availability', 
            'image_url'
        ]));

        return response()->json($driver, 200); // Responde con el conductor actualizado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Drivers $driver)
    {
        // Eliminar el conductor de la base de datos
        $driver->delete();

        return response()->json(null, 204); // Responde con código 204 (sin contenido) indicando que fue eliminado
    }

}
