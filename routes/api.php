<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
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



//user api
Route::middleware('api')->group(function () {
    Route::post("/register/email",[UserController::class,"sendVerificationCode"]);
    Route::post("/register/code",[UserController::class,"verifyCode"]);
    Route::post("/register/user",[UserController::class,"registerUser"]);
});
Route::post("login",[UserController::class,"login"]);

/*Route::post('/register/send-confirmation-code', [UserController::class, 'sendConfirmationCode']);
Route::post('/register/confirm', [UserController::class, 'confirmRegistration']);*/


//Password reset api
Route::post('password/email', [PasswordResetController::class, 'sendResetCode']);
Route::post('password/reset', [PasswordResetController::class, 'resetPasswordWithCode']);

//Product api
Route::get('/products', [ProductController::class, 'index']);
Route::post('search', [ProductController::class, 'search']);
Route::post('/products/filters/show', [ProductController::class, 'filters']);

//review api
Route::get('products/{product_id}/reviews', [ReviewController::class, 'showProductReviews']);

//Noore test photo api
Route::get('/photos', [ProductController::class, 'showphoto']);
Route::post('/photos', [ProductController::class, 'putphoto']);


Route::group(["middleware"=>["auth:api"]],function (){
    //User api
    Route::get("myProfile",[UserController::class,"myProfile"]);
    Route::get("getProfile/{id}",[UserController::class,"getProfile"]);
    Route::post("user/profile",[UserController::class,"updateProfile"]);
    Route::get("logout",[UserController::class,"logout"]);
    Route::post("checkPassword",[UserController::class,"checkPassword"]);


    //product api
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'create']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::post('/products/change/status', [ProductController::class, 'change_status']);
    Route::post('/products/change/prices', [ProductController::class, 'change_prices']);
    Route::post('/products/set/sales', [ProductController::class, 'setSales']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    Route::post('/user/products', [ProductController::class, 'my_products']);

    //cart api
    Route::post('/cart', [CartItemController::class, 'AddToCart']);
    Route::get('/cart', [CartItemController::class, 'ShowCart']);
    Route::get('/cart/{index}', [CartItemController::class, 'ShowCartItem']);
    Route::post('/cart/{index}', [CartItemController::class, 'ChangeDetails']);
    Route::delete('/cart/{index}', [CartItemController::class, 'DeleteCartItem']);

    //Review api
    Route::post('products/{product}/reviews', [ReviewController::class,"setReview"]);



    //wishlist api
    Route::get('wishlists/add', [WishlistController::class,'addToWishlist']);
    Route::delete('wishlist/{productId}', [WishlistController::class,'removeFromWishlist']);
    Route::get('wishlist', [WishlistController::class,'getWishlist']);
    Route::get('wishlist/ID', [WishlistController::class,'getIDs']);


    //favorite api
    Route::get('favorites/add', [FavoriteController::class,'addFavorite']);
    Route::delete('favorites/{productId}', [FavoriteController::class,'removeFavorite']);
    Route::get('favorite', [FavoriteController::class,'getFavoriteUsers']);
    Route::get('favorite/ID', [FavoriteController::class,'getIDs']);



    //Order api
    Route::post('orders/placeOrder', [OrderController::class,'placeOrder']);
    Route::get('orders/{order}', [OrderController::class,'showOrder']);
    Route::get('orders/user/{user}', [OrderController::class,'showAllOrder']);

    Route::post('/orders/{id}/check', [OrderController::class,'checkOrder']);


    //dashboard api
    Route::group(['middleware' => 'Dashboard'], function () {
        Route::get('/dashboard/reports', [DashboardController::class, 'showReports']);
        Route::get('/dashboard/users/{userId}/reports', [DashboardController::class, 'getUserReports']);
        Route::post('/dashboard/reports/check', [DashboardController::class, 'checkReports']);
        Route::delete('/dashboard/products/{id}', [DashboardController::class, 'deleteProduct']);
        Route::delete('/dashboard/users/{id}', [DashboardController::class, 'deleteUser']);
        Route::put('/dashboard/users/{id}/ban', [DashboardController::class, 'BanUser']);
        Route::put('/dashboard/users/{id}/unban', [DashboardController::class, 'unBanUser']);
        Route::get('/dashboard/history/orders', [DashboardController::class, 'getOrders']);
        Route::get('/dashboard/products/search', [DashboardController::class, 'searchProducts']);
        Route::get('/dashboard/users/search', [DashboardController::class, 'searchUsers']);
        Route::get('/dashboard/users/{userId}/orders', [DashboardController::class, 'getUserOrders']);
        Route::get('/dashboard/sellers', [DashboardController::class, 'getSellers']);
        Route::get('/dashboard/workers', [DashboardController::class, 'getWorkers']);
        Route::get('/dashboard/users/{userId}', [DashboardController::class, 'showUser']);
        Route::get('/dashboard/users', [DashboardController::class, 'indexUsers']);
        Route::get('/dashboard/products/{productId}', [DashboardController::class, 'showProduct']);
        Route::get('/dashboard/products', [DashboardController::class, 'indexProducts']);
        Route::get('/dashboard/orders/{orderId}', [DashboardController::class, 'showOrder']);
        Route::get('/dashboard/orders', [DashboardController::class, 'indexOrders']);
        Route::post('/dashboard/products/filter', [DashboardController::class, 'index_with_filter']);
        });

    //reports api
    Route::post('/reports', [ReportController::class, 'create_report']);
    Route::get('/reports', [ReportController::class, 'my_reports']);

    //notification
    Route::get('/notifications', [NotificationController::class,'index']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class,'markAsRead']);
});

