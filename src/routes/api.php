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
Route::controller(BoardController::class)->group(function () {
    Route::get('/drink/show', 'show');
    Route::post('/drink/add', 'add');
});

Route::post('/login', [LoginController::class, 'authenticate']);

Route::middleware('auth:sanctum')->controller(RegisterController::class)->group(function () {
    Route::post('/user/regist', 'registTmpUser');
    Route::post('/user/regist/complete/{token}', 'registUserComplete');
});

// get('/user', function (Request $request) {
//     return $request->user();
// });
