<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// Группа маршрутов с префиксом /api/v1/tasks
Route::prefix('v1')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    
    // Кастомный маршрут для восстановления (вне стандартного apiResource)
    Route::post('tasks/{id}/restore', [TaskController::class, 'restore']);
});
