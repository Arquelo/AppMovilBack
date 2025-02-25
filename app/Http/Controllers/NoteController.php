<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // consulta para obtener todos los tipos
         $types = Note::with('group', 'type')->all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
         // eliminacion del tipo
         if($note->delete()){
            // respuesta en caso de exito
            return response()->json(['message' => 'Tipo eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el tipo'], 400);
    }
}
