<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // consulta para obtener todos los tipos
        $types = Group::all();
        // respuesta en caso de exito
        return response()->json(['data' => $types]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // De momento la variable esta hardcoreada, cambiar a futuro
        $user_id = 1;
        // creacion del tipo
        $Group = Group::create(["title" => $request->title, "color" => $request->color, "user_id" => $user_id]);
        // validacion de la creacion del typo
        if ($Group) {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
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
