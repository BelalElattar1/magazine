<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ActivityController;

Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::group(['middleware' => 'JwtAuth'], function () {

    // User Auth
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/user', [JWTAuthController::class, 'getUser']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
    });

    // Activities Controller
    Route::group(['prefix' => 'activities'], function () {
        Route::get('/index', [ActivityController::class, 'index']);
    });

    // Maganizes Controller
    Route::group(['prefix' => 'maganizes'], function () {
        Route::post('/store', [MagazineController::class, 'store']);
    });

    // Articles Controller
    Route::group(['prefix' => 'articles'], function () {
        Route::get('/show/{id}', [ArticleController::class, 'show']);
        Route::get('/index/{id}', [ArticleController::class, 'index']);
        Route::post('/store', [ArticleController::class, 'store']);
    });

    // Comments Controller
    Route::group(['prefix' => 'comments'], function () {
        Route::put('/block/{id}', [CommentController::class, 'block_comment']);
        Route::get('/index/{id}', [CommentController::class, 'index']);
        Route::post('/store', [CommentController::class, 'store']);
    });

    // Subscriptions Controller
    Route::group(['prefix' => 'subscriptions'], function () {
        Route::get('/index', [SubscriptionController::class, 'index']);
        Route::post('/store', [SubscriptionController::class, 'store']);
    });

    // Payments Controller
    Route::group(['prefix' => 'payments'], function () {
        Route::post('/store', [PaymentController::class, 'store']);
    });

});

// Maganize Controller
Route::get('maganizes/index', [MagazineController::class, 'index']);
