<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);

Route::post(
    '/posts/{post}/lock',
    [PostController::class, 'lock']
)->name('posts.lock');

Route::post(
    '/posts/{post}/unlock',
    [PostController::class, 'unlock']
)->name('posts.unlock');


Route::get(
    '/posts/{post}/history',
    [PostController::class, 'history']
)->name('posts.history');


Route::get(
    '/posts/{post}',
    [PostController::class, 'show']
)->name('posts.show');
