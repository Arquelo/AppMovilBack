<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sessionId = $request->header('X-Session-ID');
        session()->setId($sessionId);
        session()->start();
        // 🔹 Acceder a los datos guardados en sesión
        $userId = session('user_id');
        // respuesta en caso de error
        if (!$userId) return response()->json(['error' => 'Sesión inválida o expirada'], 401);
        // consulta para obtener todos los tipos
        $types = Type::where('user_id', $userId)->get();
        // respuesta en caso de exito
        return response()->json(['data' => $types]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sessionId = $request->header('X-Session-ID');
        session()->setId($sessionId);
        session()->start();
        // 🔹 Acceder a los datos guardados en sesión
        $userId = session('user_id');
        // respuesta en caso de error
        if (!$userId) return response()->json(['error' => 'Sesión inválida o expirada'], 401);
        // validacion de los datos
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
        ], [
            'type.required' => 'El campo Tipo es requerido',
        ]);
        // validar los datos
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'details' => array_values($validator->errors()->all())
            ], 400);
        }
        $validated_data = $validator->validated();
        // creacion del tipo y validacion de la creacion del typo
        if (Type::create(["type" => $validated_data['type'], "user_id" => $userId])) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Registro creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el registro'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return response()->json($type);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $sessionId = $request->header('X-Session-ID');
        session()->setId($sessionId);
        session()->start();
        // 🔹 Acceder a los datos guardados en sesión
        $userId = session('user_id');
        // respuesta en caso de error
        if (!$userId) return response()->json(['error' => 'Sesión inválida o expirada'], 401);
        // validacion de los datos
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
        ], [
            'type.required' => 'El campo Tipo es requerido',
        ]);
        // validar los datos
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'details' => array_values($validator->errors()->all())
            ], 400);
        }
        $validated_data = $validator->validated();
        // validar que el tipo pertenezca al usuario
        if ($type->user_id != $userId) return response()->json(['message' => 'No tienes permisos para editar este registro'], 401);
        // actualizar y validacion de la actualizacion del typo
        if ($type->update($validated_data)) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Registro creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el registro'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        // eliminacion del tipo
        if ($type->delete()) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Registro eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el registro'], 400);
    }
}
