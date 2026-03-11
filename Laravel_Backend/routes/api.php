<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Middleware\AgentMiddleware;


Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);


Route::middleware('auth:api')->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);

    Route::post('/chat', [ChatController::class, 'chat']);

    Route::middleware('agent')->group(function() {
        Route::get('/agent-dashboard', [HelpdeskController::class, 'index']);
        Route::post('/agent/reply', [HelpdeskController::class, 'reply']);
    });

});


