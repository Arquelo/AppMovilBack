<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
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
        $types = Group::where('user_id', $userId)->get();
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
            'title' => 'required|string',
            'color' => 'required|string',
        ], [
            'title.required' => 'El campo titulo es requerido',
            'color.required' => 'El campo color es requerido',
        ]);
        // validar los datos
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'details' => array_values($validator->errors()->all())
            ], 400);
        }
        $validated_data = $validator->validated();
        // validacion de la creacion del typo y realizar la creacion del tipo
        if (Group::create(["title" => $validated_data['title'], "color" => $validated_data['color'], "user_id" => $userId])) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Grupo creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el producto'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return response()->json($group);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
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
            'title' => 'required|string',
            'color' => 'required|string',
        ], [
            'title.required' => 'El campo titulo es requerido',
            'color.required' => 'El campo color es requerido',
        ]);
        // validar los datos
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'details' => array_values($validator->errors()->all())
            ], 400);
        }
        $validated_data = $validator->validated();
        // verificar que el registro sea del usuario logueado
        if ($group->user_id != $userId) return response()->json(['message' => 'No tienes permisos para editar este registro'], 401);
        // validacion de la creacion del typo y realizar la creacion del tipo
        if ($group->update($validated_data)) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Grupo creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el producto'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // eliminacion del tipo
        if ($group->delete()) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Tipo eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el tipo'], 400);
    }
}
