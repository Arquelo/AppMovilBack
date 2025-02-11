<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Controlador para la tabla de productos

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('index');
        // consulta para obtener todos los productos
        $products = Product::all();
        // respuesta en caso de exito
        return response()->json(['data' => $products]);
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
        dd('store');
        // creacion del producto
        $product = Product::create($request);
        // validacion de la creacion del producto
        if ($product) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Producto creado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al crear el producto'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        dd('edit');
        // consulta para encontrar el producto
        $product = Product::find($id);
        // validacion de la consulta del producto
        if ($product) {
            // respuesta en caso de exito
            return response()->json(['data' => $product]);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd('update');
        // consultar para encontrar el producto
        $product = Product::find($id);
        // actualizar los datos del producto y validacion del mismo
        if ($product->update($request)) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Producto creado correctamente']);
        }
        // respuesta en caso de errors
        return response()->json(['message' => 'Error al crear el producto'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('destroy');
        // consulta para encontrar el producto
        $product = Product::find($id);
        // eliminacion del producto y validacion del mismo
        if ($product->delete()) {
            // respuesta en caso de exito
            return response()->json(['message' => 'Producto eliminado correctamente']);
        }
        // respuesta en caso de error
        return response()->json(['message' => 'Error al eliminar el producto'], 400);
    }
}
