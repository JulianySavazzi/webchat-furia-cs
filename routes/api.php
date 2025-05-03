<?php


use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/testing', function () {
    return response()->json(['message' => 'TESTADO!'], 200);
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('/user')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('/me', [\App\Http\Controllers\UserController::class, 'me']);
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
    Route::prefix('/chat/messages')->group(function () {
        Route::get('/{userTo}', [\App\Http\Controllers\MessageController::class, 'listUsersMessages']);
        Route::post('/{userTo}', [\App\Http\Controllers\MessageController::class, 'sendMessage']);
        Route::get('/team/{team}', [\App\Http\Controllers\MessageController::class, 'listTeamMessages']);
        Route::post('/team/{team}', [\App\Http\Controllers\MessageController::class, 'sendTeamMessage']);
    });
});
