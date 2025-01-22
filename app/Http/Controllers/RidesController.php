<?php

namespace App\Http\Controllers;

use App\Models\Rides;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las carreras
        $rides = Rides::all();
        return response()->json($rides, 200);
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
            'user_id' => 'required|exists:users,id', // Asegurarse de que el id de usuario existe
            'driver_id' => 'required|exists:drivers,id', // Asegurarse de que el id del conductor existe
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'distance' => 'required|numeric',
            'total_price' => 'required|numeric',
            'vehicle_type' => 'required|in:Motorcycle,Car',
            'status' => 'required|in:Requested,Accepted,Completed,Cancelled',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Crear una nueva carrera
        $ride = Rides::create([
            'user_id' => $request->user_id,
            'driver_id' => $request->driver_id,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'destination_lat' => $request->destination_lat,
            'destination_lng' => $request->destination_lng,
            'distance' => $request->distance,
            'total_price' => $request->total_price,
            'vehicle_type' => $request->vehicle_type,
            'status' => $request->status,
            'requested_at' => now(),
        ]);

        return response()->json($ride, 201); // Responde con la carrera recién creada
    }

    /**
     * Display the specified resource.
     */
    public function show(Rides $ride)
    {
        // Mostrar los detalles de una carrera específica
        return response()->json($ride, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rides $ride)
    {
        // Este método no es necesario en una API RESTful.
        // Normalmente se usaría para mostrar un formulario en una vista.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rides $ride)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id', // Asegurarse de que el id de usuario existe
            'driver_id' => 'sometimes|exists:drivers,id', // Asegurarse de que el id del conductor existe
            'start_lat' => 'sometimes|numeric',
            'start_lng' => 'sometimes|numeric',
            'destination_lat' => 'sometimes|numeric',
            'destination_lng' => 'sometimes|numeric',
            'distance' => 'sometimes|numeric',
            'total_price' => 'sometimes|numeric',
            'vehicle_type' => 'sometimes|in:Motorcycle,Car',
            'status' => 'sometimes|in:Requested,Accepted,Completed,Cancelled',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Actualizar la carrera con los nuevos datos
        $ride->update($request->only([
            'user_id',
            'driver_id',
            'start_lat',
            'start_lng',
            'destination_lat',
            'destination_lng',
            'distance',
            'total_price',
            'vehicle_type',
            'status',
        ]));

        return response()->json($ride, 200); // Responde con la carrera actualizada
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rides $ride)
    {
        // Eliminar la carrera de la base de datos
        $ride->delete();

        return response()->json(null, 204); // Responde con código 204 (sin contenido) indicando que fue eliminado
    }

    /**
     * Cambiar el estado de la carrera (por ejemplo, de "Requested" a "Accepted").
     */
    public function changeStatus(Request $request, Rides $ride)
    {
        // Validación para asegurarse de que el estado sea válido
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Requested,Accepted,Completed,Cancelled',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Cambiar el estado de la carrera
        $ride->status = $request->status;
        $ride->save();

        return response()->json($ride, 200); // Responde con la carrera actualizada
    }
    
    public function locations(Rides $ride)
    {
        return response()->json([
            'start_lat' => $ride->start_lat,
            'start_lng' => $ride->start_lng,
            'destination_lat' => $ride->destination_lat,
            'destination_lng' => $ride->destination_lng,
        ]);
    }

}