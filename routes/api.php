<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\CartItemController;
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



Route::middleware('api')->group(function () {

    Route::post("/register/email",[UserController::class,"sendVerificationCode"]);
    Route::post("/register/code",[UserController::class,"verifyCode"]);
    Route::post("/register/user",[UserController::class,"registerUser"]);

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*Route::post('/register/send-confirmation-code', [UserController::class, 'sendConfirmationCode']);
Route::post('/register/confirm', [UserController::class, 'confirmRegistration']);*/

Route::post("login",[UserController::class,"login"]);


Route::post('password/email', [PasswordResetController::class, 'sendResetCode']);
Route::post('password/reset', [PasswordResetController::class, 'resetPasswordWithCode']);

//public routes no need to auth
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);

Route::post('/products/search', [ProductController::class, 'search']);
Route::post('/products/filters/show', [ProductController::class, 'filters']);
Route::get('/products/seller/{id}', [ProductController::class, 'user_products']);

Route::group(["middleware"=>["auth:api"]],function (){
    Route::get("myProfile",[UserController::class,"myProfile"]);
    Route::get("getProfile/{id}",[UserController::class,"getProfile"]);

    Route::post("user/profile",[UserController::class,"updateProfile"]);
    Route::get("logout",[UserController::class,"logout"]);
    Route::post("logout1",[UserController::class,"logout1"]);

    Route::post('/products', [ProductController::class, 'create']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::post('/products/status/{id}', [ProductController::class, 'change_status']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    Route::get('/user/products', [ProductController::class, 'my_products']);
    
    
    //cart api
    Route::post('/cart', [CartItemController::class, 'AddToCart']);
    Route::get('/cart', [CartItemController::class, 'ShowCart']);
    Route::get('/cart/{index}', [CartItemController::class, 'ShowCartItem']);
    Route::post('/cart/{index}', [CartItemController::class, 'ChangeDetails']);
    Route::delete('/cart/{index}', [CartItemController::class, 'DeleteCartItem']);




    Route::post('products/{product}/reviews', [ReviewController::class,"setReview"]);
    Route::get('products/{product_id}/reviews', [ReviewController::class, 'showProductReviews']);

    Route::post('wishlist/{productId}', [WishlistController::class,'addToWishlist']);
    Route::delete('wishlist/{productId}', [WishlistController::class,'removeFromWishlist']);
    Route::get('wishlist', [WishlistController::class,'getWishlist']);

    Route::post('favorites/{productId}', [FavoriteController::class,'addFavorite']);
    Route::delete('favorites/{productId}', [FavoriteController::class,'removeFavorite']);
    Route::get('favorite', [FavoriteController::class,'getFavoriteUsers']);

});
