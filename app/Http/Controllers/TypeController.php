<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // consulta para obtener todos los tipos
        $types = Type::all();
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
        // creacion del tipo
        $type = Type::create(["type" => $request->type]);
        // validacion de la creacion del typo
        if ($type) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Producto creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el producto'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        // eliminacion del tipo
        if($type->delete()){
            // respuesta en caso de exito
            return response()->json(['message' => 'Tipo eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el tipo'], 400);
    }
}
