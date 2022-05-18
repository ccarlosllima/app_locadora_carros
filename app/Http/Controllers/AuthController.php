<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // método responsável por realizar o login do usuário
    public function login(Request $request)
    {
        $credenciais = $request->all(['email','password']);

        $token = auth('api')->attempt($credenciais);

        if (!$token) {
            return response()->json(['erro' => 'Usuário e o senha inválido'], 403);
        }
        return response()->json(['token' => $token]);
    }

    // Método responsável por realizar o logout da do usuário
    function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado com sucesso!']);
    }

    // Método responsável por renover o token de autorização
    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }
    
    // Método responsável por retornar o dados do usuário autenticado
    public function me()
    {
        return response()->json(auth()->user());
    }

}
