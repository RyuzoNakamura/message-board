<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('boards.index');
});

Route::resource('boards', BoardController::class)
    ->only(['index', 'create', 'store', 'show', 'update', 'destroy']);

Route::post('boards/{board}/posts', [PostController::class, 'store'])
    ->name('boards.posts.store');

Route::delete('boards/{board}/posts/{post}', [PostController::class, 'destroy'])
    ->name('boards.posts.destroy')
    ->where('post', '[0-9]+');

Route::get('/img/{board_id}/{post_number}', 'ImageController@show')
    ->name('images.show');

Route::get('/activity', [BoardController::class, 'activity'])
    ->name('activities.index');
