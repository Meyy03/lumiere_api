<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\CategoryImageController;
use App\Http\Controllers\ProductImageController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
|
| Opening http://127.0.0.1:8000 logs out any previous
| Admin web session and opens the Lumière Admin Login page.
|
*/

Route::get('/', function (Request $request) {

    if (Auth::check()) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    return redirect()->route('admin.login');
});


/*
|--------------------------------------------------------------------------
| DEFAULT LOGIN ROUTE
|--------------------------------------------------------------------------
|
| Laravel's auth middleware redirects unauthenticated users
| to a route named "login".
|
*/

Route::get('/login', function () {

    return redirect()->route('admin.login');

})->name('login');


/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE ROUTE
|--------------------------------------------------------------------------
|
| Used by Flutter and Laravel Admin for uploaded product images.
|
| Example:
| http://127.0.0.1:8000/product-images/example.png
|
*/

Route::get(
    '/product-images/{filename}',
    [ProductImageController::class, 'show']
)
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('product.image');


/*
|--------------------------------------------------------------------------
| CATEGORY IMAGE ROUTE
|--------------------------------------------------------------------------
|
| Used by Flutter and Laravel Admin for uploaded category images.
|
| Example:
| http://127.0.0.1:8000/category-images/example.png
|
*/

Route::get(
    '/category-images/{filename}',
    [CategoryImageController::class, 'show']
)
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('category.image');


/*
|--------------------------------------------------------------------------
| PROFILE IMAGE ROUTE
|--------------------------------------------------------------------------
|
| Used by Flutter and Laravel Admin for customer profile images.
|
| Database example:
| profiles/abc123.jpg
|
| Browser URL:
| http://127.0.0.1:8000/profile-images/abc123.jpg
|
*/

Route::get(
    '/profile-images/{filename}',
    function (string $filename) {

        // Security: only use the filename itself.
        $filename = basename($filename);

        $path = 'profiles/' . $filename;

        /*
        |--------------------------------------------------------------------------
        | CHECK FILE EXISTS
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | GET FULL FILE PATH
        |--------------------------------------------------------------------------
        */

        $fullPath = Storage::disk('public')->path($path);

        /*
        |--------------------------------------------------------------------------
        | RETURN IMAGE
        |--------------------------------------------------------------------------
        |
        | CORS headers allow Flutter Web running on another port
        | to display the image.
        |
        */

        return response()->file(
            $fullPath,
            [
                'Access-Control-Allow-Origin' => '*',
                'Cross-Origin-Resource-Policy' => 'cross-origin',
                'Cache-Control' => 'public, max-age=3600',
            ]
        );
    }
)
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('profile.image');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ADMIN LOGIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [AdminAuthController::class, 'showLogin']
        )->name('login');


        Route::post(
            '/login',
            [AdminAuthController::class, 'login']
        )->name('login.submit');


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED ADMIN ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'auth',
            'admin',
        ])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [DashboardController::class, 'index']
            )->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | PRODUCTS CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'products',
                ProductController::class
            );


            /*
            |--------------------------------------------------------------------------
            | CATEGORIES CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'categories',
                CategoryController::class
            );


            /*
            |--------------------------------------------------------------------------
            | ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/orders',
                [OrderController::class, 'index']
            )->name('orders.index');


            Route::get(
                '/orders/{order}',
                [OrderController::class, 'show']
            )
                ->whereNumber('order')
                ->name('orders.show');


            Route::patch(
                '/orders/{order}/status',
                [OrderController::class, 'updateStatus']
            )
                ->whereNumber('order')
                ->name('orders.status.update');


            /*
            |--------------------------------------------------------------------------
            | CUSTOMERS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/customers',
                [CustomerController::class, 'index']
            )->name('customers.index');


            Route::get(
                '/customers/{customer}',
                [CustomerController::class, 'show']
            )
                ->whereNumber('customer')
                ->name('customers.show');


            /*
            |--------------------------------------------------------------------------
            | REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/reports',
                [ReportController::class, 'index']
            )->name('reports.index');


            Route::get(
                '/reports/export/pdf',
                [ReportController::class, 'exportPdf']
            )->name('reports.pdf');


            /*
            |--------------------------------------------------------------------------
            | LOGOUT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/logout',
                [AdminAuthController::class, 'logout']
            )->name('logout');
        });
    });