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
            // Verificar el ID Token de Firebase
            $verifiedIdToken = $auth->verifyIdToken($request->token);
            // Obtener datos del usuario
            $email = $verifiedIdToken->claims()->get('email');
            $name = $verifiedIdToken->claims()->get('name');
            $uid = $verifiedIdToken->claims()->get('sub');
            // Buscar o registrar usuario en la base de datos
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => bcrypt(uniqid()), 'google_id' => $uid]
            );

            session(['user_id' => $user->id]);
            session(['user_email' => $user->email]);
            session()->save();

            return response()->json([
                'session_id' => session()->getId(),
                'user' => [
                    'id' => session('user_id'),
                    'email' => session('user_email'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Error al procesar los datos'], 400);
        }

        $validatedData = $validator->validated();

        $user = User::where('email', $validatedData['email'])->first();

        session(['user_id' => $user->id]);
        session(['user_email' => $user->email]);
        session()->save();

        return response()->json([
            'session_id' => session()->getId(),
            'user' => [
                'id' => session('user_id'),
                'email' => session('user_email'),
            ]
        ]);
    }
}
