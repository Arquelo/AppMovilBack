<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Note;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
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
        // consulta para obtener todos las notas
        $types = Note::where('user_id', $userId)->with('group', 'type')->get();
        // respuesta en caso de exito
        return response()->json(['data' => $types]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        // Consultar los datos para el create
        $types = Type::all();
        $groups = Group::all();
        // respuesta en caso de exito
        return response()->json(['types' => $types, 'groups' => $groups]);
    }

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
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type_id' => 'required|string|exists:types,id',
            'group_id' => 'required|string|exists:groups,id',
        ], [
            'description.required' => 'El campo Tipo es requerido',
            'start_date.required' => 'El campo Fecha de inicio es requerido', 
            'end_date.required' => 'El campo Fecha de fin es requerido',
            'type_id.required' => 'El campo Tipo es requerido',
            'group_id.required' => 'El campo Grupo es requerido',
        ]);
        // validar los datos
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al procesar los datos',
                'details' => array_values($validator->errors()->all())
            ], 400);
        }
        $validated_data = $validator->validated();
        // agregar el user_id al arreglo
        $validated_data['user_id'] = $userId;
        // creacion del tipo y validacion de la creacion de la nota
        if (Note::create($validated_data)) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Producto creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el producto'], 400);
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
        if ($note->delete()) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Registro eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el registro'], 400);
    }
}
