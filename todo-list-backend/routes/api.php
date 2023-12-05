<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/unlogged', [AuthController::class, 'unlogged'])->name('unlogged');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/task/{id}', [TaskController::class, 'show']);
    Route::post('/task', [TaskController::class, 'store']);
    Route::put('/task/{id}', [TaskController::class, 'update']);
    Route::delete('/task/{id}', [TaskController::class, 'destroy']);
});