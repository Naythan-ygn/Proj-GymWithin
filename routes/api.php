<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

// The URL will automatically become: /api/chatbot
Route::post('/chatbot', [ChatbotController::class, 'handleChat']);
