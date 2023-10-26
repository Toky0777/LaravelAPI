<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Créer un lien qui permettra aux clients : React, Vue, Angular, Node, rEACT nATIVE

//Récuperer la liste des posts
Route::get('posts', [PostController::class, 'index']);


//Ajouter un post | POST PUT PATCH
Route::post('posts/create', [PostController::class, 'store']);
Route::put('posts/edit/{id} ', [PostController::class, 'update']);
Route::delete('posts/{post } ', [PostController::class, 'delete']);

// Inscription
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
