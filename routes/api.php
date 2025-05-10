<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\PostController as PostControllerV1;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('v1')->group(function () {
    Route::apiResource('/posts', PostControllerV1::class);
});

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [PostControllerV1::class, 'index']);
Route::get('/posts/{id}', [PostControllerV1::class, 'show']);

//protected routes
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/posts', PostControllerV1::class)->only(['store', 'update', 'destroy']);
});