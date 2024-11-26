<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;

Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::group(['middleware' => 'JwtAuth'], function () {

    // User Auth
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/user', [JWTAuthController::class, 'getUser']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
    });

    // Maganizes Auth
    Route::group(['prefix' => 'maganizes'], function () {
        Route::post('/store', [MagazineController::class, 'store']);
    });

    // Articles Auth
    Route::group(['prefix' => 'articles'], function () {
        Route::get('/show/{id}', [ArticleController::class, 'show']);
        Route::get('/index/{id}', [ArticleController::class, 'index']);
        Route::post('/store', [ArticleController::class, 'store']);
    });

    // Comments Auth
    Route::group(['prefix' => 'comments'], function () {
        Route::put('/block/{id}', [CommentController::class, 'block_comment']);
        Route::get('/index/{id}', [CommentController::class, 'index']);
        Route::post('/store', [CommentController::class, 'store']);
    });

    // Subscriptions Auth
    Route::group(['prefix' => 'subscriptions'], function () {
        Route::get('/index', [SubscriptionController::class, 'index']);
        Route::post('/store', [SubscriptionController::class, 'store']);
    });

    // Payments Auth
    Route::group(['prefix' => 'payments'], function () {
        Route::post('/store', [PaymentController::class, 'store']);
    });

});

// Maganize Auth
Route::get('maganizes/index', [MagazineController::class, 'index']);
