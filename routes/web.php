<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/post', [PostController::class, 'post'])->name('post');

Route::post('/ai-review/{id}', [PostController::class, 'aiReview'])->name('ai-review');

Route::post('/chatId', [ChannelController::class, 'getChatId'])->name('get_chat_id');