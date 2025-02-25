<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/producto', [ProductController::class, 'index']);

Route::get('/{any}', function () {
    $path = public_path('build/index.html');
    if (File::exists($path)) {
        return Response::file($path);
    }
    abort(404);
})->where('any', '.*');

// Route::get('/descargar-react', function () {
//     $file = public_path('downloads/react-app.zip');
//     return Response::download($file, 'MiAppReact.zip');
// });