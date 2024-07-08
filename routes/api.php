<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

//open routes
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

//protected routes
Route::group([
    'middleware'=>'auth:api'
],function(){
    Route::get('profile',[UserController::class,'profile']);
    Route::get('logout',[UserController::class,'logout']);
});