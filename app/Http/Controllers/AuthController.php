<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro de usuário
    public function register(Request $request)
    {
        // Validação dos dados do usuário
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Retornar resposta de sucesso
        return response()->json(['message' => 'Usuário registrado com sucesso.'], 201);
    }

    // Login de usuário
    public function login(Request $request)
    {
        // Validação dos dados de login
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verificar as credenciais
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            // Criar o token de acesso
            $token = $user->createToken('API Token')->plainTextToken;

            // Retornar a resposta com o token
            return response()->json([
                'message' => 'Login bem-sucedido.',
                'token' => $token,
            ], 200);
        }

        // Se as credenciais não forem válidas
        return response()->json(['message' => 'Credenciais inválidas.'], 401);
    }

    // Logout de usuário
    public function logout(Request $request)
    {
        // Deletar os tokens do usuário
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
    }
}