<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;


/**
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
|
| Public authentication routes used by the Flutter mobile application.
|
*/

Route::post('/register', [
    AuthController::class,
    'register',
]);

Route::post('/login', [
    AuthController::class,
    'login',
]);


/**
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
|
| Public product routes.
|
*/

Route::get('/products', [
    ProductController::class,
    'index',
]);


/**
 * BEST SELLING
 *
 * Public because it is calculated from
 * overall customer purchases.
 */
Route::get('/products/best-selling', [
    ProductController::class,
    'bestSelling',
]);


/**
 * PRODUCT DETAILS
 *
 * Keep this after /best-selling.
 */
Route::get('/products/{id}', [
    ProductController::class,
    'show',
])
    ->whereNumber('id');


/**
|--------------------------------------------------------------------------
| CATEGORIES
|--------------------------------------------------------------------------
|
| Public category routes.
|
*/

Route::get('/categories', [
    CategoryController::class,
    'index',
]);

Route::get('/categories/{id}/products', [
    CategoryController::class,
    'products',
])
    ->whereNumber('id');


/**
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
|
| These routes require a valid Laravel Sanctum Bearer token.
|
*/

Route::middleware('auth:sanctum')->group(function () {


    /**
    |--------------------------------------------------------------------------
    | AUTHENTICATED USER
    |--------------------------------------------------------------------------
    */

    Route::get('/user', [
        AuthController::class,
        'user',
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ]);


    /**
    |--------------------------------------------------------------------------
    | RECOMMENDED PRODUCTS
    |--------------------------------------------------------------------------
    |
    | This route is protected because Laravel
    | needs to know the logged-in customer's
    | shopping_preference.
    |
    */

    Route::get('/products/recommended', [
        ProductController::class,
        'recommended',
    ]);


    /**
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [
        OrderController::class,
        'index',
    ]);

    Route::post('/orders', [
        OrderController::class,
        'store',
    ]);

    Route::get('/orders/{id}', [
        OrderController::class,
        'show',
    ])
        ->whereNumber('id');


    /**
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'show',
    ]);

    Route::put('/profile', [
        ProfileController::class,
        'update',
    ]);


    /**
     * PROFILE IMAGE
     */
    Route::post(
        '/profile/image',
        [
            ProfileController::class,
            'updateImage',
        ]
    );


    /**
    |--------------------------------------------------------------------------
    | FAVORITES
    |--------------------------------------------------------------------------
    |
    | {product} uses Laravel route-model binding
    | with the Product model.
    |
    */

    Route::get('/favorites', [
        FavoriteController::class,
        'index',
    ]);

    Route::post('/favorites/{product}', [
        FavoriteController::class,
        'store',
    ])
        ->whereNumber('product');

    Route::delete('/favorites/{product}', [
        FavoriteController::class,
        'destroy',
    ])
        ->whereNumber('product');
});