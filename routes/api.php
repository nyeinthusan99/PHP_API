<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\SendEmailController;
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

Route::middleware('auth:api')->group(function () {
Route::get('getUser', [PassportAuthController::class, 'userInfo']);
Route::post('logout', [PassportAuthController::class, 'logout']);
Route::resource('products', ProductController::class);
Route::get('products/search/{keyword}', [ProductController::class,'search']);
});







