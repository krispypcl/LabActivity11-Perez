<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

Route::redirect('/', '/posts');

// guest middleware: logged-in users can't open the login page again
// the route is named "login" because the auth middleware sends guests to route('login')
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
// auth middleware: only logged-in users can reach these
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// /posts/create has to be above /posts/{id}, otherwise "create" is treated as an id
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
// {post} is route model binding -> CommentController@store gets a Post model
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
