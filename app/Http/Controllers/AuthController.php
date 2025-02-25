<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        try {
            $firebase = (new Factory)->withServiceAccount(storage_path('app/firebase_credentials.json'));
            $auth = $firebase->createAuth();

            // 🔥 Verificar el ID Token de Firebase
            $verifiedIdToken = $auth->verifyIdToken($request->token);

            // ✅ Acceder correctamente a los claims en Lcobucci/JWT 4+
            $email = $verifiedIdToken->claims()->get('email');
            $name = $verifiedIdToken->claims()->get('name');
            $uid = $verifiedIdToken->claims()->get('sub');

            // 🔍 Buscar o registrar usuario en la base de datos
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => bcrypt(uniqid()), 'google_id' => $uid]
            );

            // Generar token para el usuario en Laravel Sanctum
            $authToken = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Autenticación exitosa',
                'authToken' => $authToken,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function login(Request $request)
    {
        dd($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Error al procesar los datos'], 400);
        }

        $validatedData = $validator->validated();

        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            $user = User::where("email", $request->email)->first();

            return response()->json($user);
        } else {
            return response()->json(["error" => "Usuario y/o contraseña incorrectos"], 404);
        }
    }
}
