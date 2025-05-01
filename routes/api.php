<?php


use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/testing', function(){
    return response()->json(['message' => 'TESTADO!'], 200);
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('/user')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});
