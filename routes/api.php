<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/test', function (Request $request) {
    return response()->json([
        'message' => 'Laravel recibió el dato correctamente',
        'data' => $request->input('dato'),
    ]);
});

Route::resource('product', ProductController::class)->names('products');
Route::resource('type', TypeController::class)->names('types');
Route::resource('group', GroupController::class)->names('groups');
Route::resource('note', NoteController::class)->names('notes');
Route::post('login-google', [AuthController::class, 'loginWithGoogle']);
Route::post('login', [AuthController::class, 'login']);
