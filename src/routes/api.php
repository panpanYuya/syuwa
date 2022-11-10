<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\drink\BoardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
});

//TODO login機能が修正出来次第sanctumを噛ませるように修正
Route::get('/drink/show', [BoardController::class, 'show']);

Route::post('/login', [LoginController::class, 'authenticate']);

//TODO もう一つのURL作成後にgroup化
Route::post('/user/regist', [RegisterController::class, 'registUser']);
