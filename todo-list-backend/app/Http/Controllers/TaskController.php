<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Lista todas as tarefas do usuário autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $task = Auth::user()->tasks;

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarefas não encontrada.',
                    'task' => null
                ], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarefas Localizadas.',
                    'task' => $task
                ], 201);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    /**
     * Armazena uma nova tarefa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!$request->hasAny(['title', 'description'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum dado válido foi informado para criação.',
                    'task' => null
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                    'task' => null
                ], 422);
            } else {
                $task = Auth::user()->tasks()->create($validator->validated());
                return response()->json([
                    'success' => true,
                    'message' => 'Tarefa criada com sucesso.',
                    'task' => $task
                ], 201);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    /**
     * Mostra uma tarefa específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarefa não encontrada.',
                    'task' => null
                ], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarefa Localizada com sucesso.',
                    'task' => $task
                ], 201);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    /**
     * Atualiza uma tarefa específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarefa não encontrada.',
                    'task' => null
                ], 404);
            } else if( !$request->hasAny(['title', 'description', 'status'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum dado válido foi informado para atualização.',
                    'task' => null
                ], 422);
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'status' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(),
                        'task' => null
                    ], 422);
                } else {
                    $task->update($validator->validated());
                    return response()->json([
                        'success' => true,
                        'message' => 'Tarefa Atualizada com sucesso.',
                        'task' => $task
                    ], 201);
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    /**
     * Remove uma tarefa específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarefa não encontrada.',
                    'task' => null
                ], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarefa removida com sucesso.',
                    'task' => null
                ], 201);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 401);
        }
    }
}