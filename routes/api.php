<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
    return $request->user();
});


//Route::post("register",[UserController::class,"register"]);
Route::post('/register/send-confirmation-code', [UserController::class, 'sendConfirmationCode']);
Route::post('/register/confirm', [UserController::class, 'confirmRegistration']);

Route::post("login",[UserController::class,"login"]);
Route::get("list-product",[ProductController::class,"listProduct"]);


Route::post('password/email', [PasswordResetController::class, 'sendResetCode']);
Route::post('password/reset', [PasswordResetController::class, 'resetPasswordWithCode']);



Route::group(["middleware"=>["auth:api"]],function (){
    Route::get("profile",[UserController::class,"profile"]);
    Route::get("logout",[UserController::class,"logout"]);
    Route::post("logout1",[UserController::class,"logout1"]);

    Route::get("author-product",[ProductController::class,"sellerProduct"]);
    Route::post("create-product",[ProductController::class,"createProduct"]);
    Route::get("single-product/{id}",[ProductController::class,"singleProduct"]);
    Route::post("update-product/{id}",[ProductController::class,"updateProduct"]);
    Route::get("delete-product/{id}",[ProductController::class,"deleteProduct"]);

});
