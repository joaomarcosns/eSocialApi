<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            return response()->json([
                'message' => 'Logado!',
                'data' => $user,
                'status_code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao logar usuário!',
                'data' => $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
