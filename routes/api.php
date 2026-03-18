<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

// Auth routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Get authenticated user
Route::get('/user', function (Request $request) {
    $user = $request->user();
    return [
        'id' => $user->id,
        'name' => $user->first_name . ' ' . $user->last_name,
        'email' => $user->email
    ];
})->middleware('auth:sanctum');

// Projects routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    // Users
    Route::get('/users/search', [UserController::class, 'search']);

    // Dashboard
    Route::middleware('auth:sanctum')->get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Labels
    Route::get('/labels', [LabelController::class, 'index']);
    Route::post('/labels', [LabelController::class, 'store']);

    // Task Routes
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}/archive', [TaskController::class, 'archive']);

    // Project
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::patch('/projects/{id}/star', [ProjectController::class, 'toggleStar']);
    Route::patch('/projects/{id}/archive', [ProjectController::class, 'archive']);
    Route::post('/projects/{project}/assign', [ProjectController::class, 'assignUser']);
});

