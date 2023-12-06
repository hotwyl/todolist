<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\CustomVerifyEmailNotification;

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
                [
                    'status' => User::STATUS_ACTIVE, // 'status' => 'active
                    'tipo' => User::TYPE_USER, // 'tipo' => 'user
                    'cod' => (string) Str::uuid(),
                    'login' => Str::slug($request->name, '_'),
                    'password' => bcrypt($request->password)
                    ]
            ));
            
            // Envie a notificação de verificação de e-mail
            // $user->notify(new CustomVerifyEmailNotification($user->verification_url));
            // $user->sendEmailVerificationNotification();
            
            $login = [
                'nome' => $user->name,
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
                        'nome' => $user->name,
                        'token' => $user->createToken('invoice')->plainTextToken
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
                'nome' => $user->name,
                'token' => $user->createToken('invoice')->plainTextToken
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
    
    public function updateUser($request){
        try {
            // Validação dos dados
            $validator = Validator::make($request, [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'password' => 'nullable|confirmed|min:8',
                'status' => 'nullable|['.User::STATUS_INACTIVE.','.User::STATUS_ACTIVE.']',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Falha na validação dos dados.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Obter o usuário autenticado
            $user = Auth::user();

            $dados = [];

            if(isset($request['name'])){
                $dados['name'] = $request['name'];
            }

            if(isset($request['email'])){
                $dados['email'] = $request['email'];
            }

            if(isset($request['password'])){
                $dados['password'] = bcrypt($request['password']);
            }

            if(isset($request['status'])){
                $dados['status'] = $request['status'];
            }   
            
            $usuario = User::where('cod', $user->cod)->update($dados);

            return response()->json([
                'status' => true,
                'message' => 'Usuário Atualizado com sucesso.',
                'usuario' => $usuario,

            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 401);
        }

    }

    public function forgotPassword($email){
        try {

            // Validação dos dados
            $validator = Validator::make($request, [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Falha na validação dos dados.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::where('email', $request['email'])->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado.',
                ], 404);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $user->notify(new ResetPasswordNotification($token));

            // Enviar e-mail de redefinição de senha
            //Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

            return response()->json([
                'status' => true,
                'message' => 'Email enviado com sucesso.',
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function resetPassword($request){
        try {

            // Validação dos dados
            $validator = Validator::make($request, [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Falha na validação dos dados.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Verificar se o e-mail existe na tabela de usuários
            $user = User::where('email', $request['email'])->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado.',
                ], 404);
            }

            if(!$user->tokens()->where('token', hash('sha256', $request['token']))->first()){
                return response()->json([
                    'status' => false,
                    'message' => 'Token inválido.',
                ], 401);
            }

            // $user->password = bcrypt($request['password']);
            // $user->save();

            // Verificar se o token de redefinição de senha é válido
            $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            });

            if ($status === Password::INVALID_TOKEN) {
                return response()->json([
                    'message' => 'Token de redefinição de senha inválido.',
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Senha alterada com sucesso.',
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function updatePassword($request){
        try {

            // Validação dos dados
            $validator = Validator::make($request, [
                'current_password' => 'required',
                'new_password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Falha na validação dos dados.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Obter o usuário autenticado
            $user = Auth::user();

            // Verificar se a senha atual fornecida está correta
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Senha atual incorreta.',
                ], 400);
            }

            // Atualizar a senha do usuário
            // $user->password = bcrypt($request['password']);
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Senha alterada com sucesso.',
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function me(){
        try {
            $user = Auth::user();

            return response()->json([
                'status' => true,
                'message' => 'Usuário Logado.',
                'user' => $user
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 401);
        }

    }
}
