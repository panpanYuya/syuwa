<?php

use App\Http\Controllers\API\Auth\FollowUserController;
use App\Http\Controllers\API\Auth\PasswordResetController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserPageController;
use App\Http\Controllers\API\drink\BoardController;
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

Route::post('/login', [LoginController::class, 'authenticate']);


Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/password/email', 'checkEmail');
    Route::post('/password/reset/{token}', 'passwordReset');
    Route::post('/password/complete', 'changePasswordComplete');
});

Route::controller(RegisterController::class)->group(function () {
    Route::post('/user/regist', 'registTmpUser');
    Route::post('/user/regist/complete/{token}', 'registUserComplete');
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(BoardController::class)->group(function () {
        Route::get('/drink/show/{numOfDisplaiedPosts}', 'show');
        Route::post('/drink/add', 'add');
        Route::get('/drink/create', 'create');
        Route::get('/drink/detail/{postId}', 'detail');
        Route::get('/drink/search/{tagId}/{numOfDisplaiedPosts}', 'searchPostsByTag');
    });


    Route::controller(FollowUserController::class)->group(function () {
        Route::put('/user/follow/{followId}', 'followUser');
        Route::delete('/user/unfollow/{unfollowId}', 'unfollowUser');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::post('/user/check', 'checkLogin');
    });

    Route::get('/user/page/{userId}', [UserPageController::class, 'showUserPage']);
});
