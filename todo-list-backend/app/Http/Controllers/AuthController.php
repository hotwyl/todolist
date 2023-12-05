<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro de um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));
            

            $login = [
                'nome' => $user->name,
                'email' => $user->email,
                'token' => $user->createToken('invoice')->plainTextToken,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Usuário Cadastrado com sucesso.',
                'user' => $login
            ], 201);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    /**
     * Login do usuário e criação de token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }
    
            if (Auth::attempt($validator->validated())) {
                $user = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'Login efetuado com Sucesso.',
                    'content' => [
                        'nome' => Auth::user()->name,
                        'token' => Auth::user()->createToken('invoice')->plainTextToken
                    ],
                ], 201);
            }else {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro. Credenciais inválidas.',
                ], 422);

            }

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function unlogged()
    {
        return response()->json([
            'status' => false, 
            'message' => 'Usuário não autenticado.']
        , 401);
    }

    public function user(){
        try {
            $user = Auth::user();

            return response()->json([
                'status' => true,
                'message' => 'Usuario Logado.',
                'user' => $user
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }

    }

    public function logout(){
        try {
            $user = Auth::user();

            $user->currentAccessToken()->delete();

            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Usuário Deslogado.',
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }

    }

    public function refresh(){
        try {
            $user = Auth::user();

            $user->currentAccessToken()->delete();

            $user->tokens()->delete();

            $return = [
                'codigo' => Auth::user()->cod,
                'login' => Auth::user()->login,
                'nome' => Auth::user()->name,
                'email' => Auth::user()->email,
                'token' => Auth::user()->createToken('invoice')->plainTextToken
            ];

            return response()->json([
                'status' => true,
                'message' => 'Token atualizado.',
                'content' => $return,
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }

    }
    
}
