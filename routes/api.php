<?php

use App\Http\Controllers\AuthenticatedController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::controller(AuthenticatedController::class)->group(function () {
    Route::get('/check-auth', 'checkAuth');
    Route::post('/login', 'login');
    Route::post('/logout', 'destroy');
    Route::post('/register', 'register');
    Route::post('/refresh-token', 'refreshToken')->middleware('jwt.refresh');
});
