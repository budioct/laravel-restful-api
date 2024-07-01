<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post("/users", [\App\Http\Controllers\UserController::class, "register"]);
Route::post("/users/login", [\App\Http\Controllers\UserController::class, "login"]);

// middleware: user boleh akses setiap endpoint dengan syarat sudah login, dan hak akses token
Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function (){

   Route::get("/users/current", [\App\Http\Controllers\UserController::class, "getList"]);
   Route::patch("/users/current", [\App\Http\Controllers\UserController::class, "update"]);
   Route::delete("/users/logout", [\App\Http\Controllers\UserController::class, "logout"]);

});
