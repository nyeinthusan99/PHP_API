<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\UsersController;

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
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::post('password/forgotPassword', [ForgetPasswordController::class, 'sendResetLinkResponse'])->name('passwords.sent');
Route::post('password/reset', [ResetPasswordController::class, 'sendResetResponse'])->name('passwords.reset');

Route::post('users/import',[UsersController::class,'import']);
Route::get('users/export', [UsersController::class, 'export']);
Route::post('posts/import',[PostController::class,'import']);
Route::get('posts/export', [PostController::class, 'export']);


Route::middleware('auth:api')->group(function () {
    Route::get('getUser', [PassportAuthController::class, 'userInfo']);
    Route::get('userLists', [PassportAuthController::class, 'userLists']);
    Route::post('logout', [PassportAuthController::class, 'logout']);
    Route::resource('posts', PostController::class);
    Route::resource('users',PassportAuthController::class);
    Route::post('updateUser/{id}',[PassportAuthController::class,'updateUser']);
});

//Route::middleware('admin')->group(function(){
//    Route::get('getUser', [PassportAuthController::class, 'userInfo']);
//});







