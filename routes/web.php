<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('wishlists', function () {
    return view('frontend.wishlists.index');
})->name('wishlists.index');

Route::get('products', function () {
    return view('frontend.products.index');
})->name('products.index');

Route::get('products/detail', function () {
    return view('frontend.products.detail');
})->name('products.detail');

Route::get('carts', function () {
    return view('frontend.carts.index');
})->name('carts.index');

Route::get('carts/checkout', function () {
    return view('frontend.carts.checkout');
})->name('carts.checkout');

Route::get('about', function () {
    return view('frontend.other.about');
})->name('about');

Route::get('contact', function () {
    return view('frontend.other.contact');
})->name('contact');

Route::get('blogs', function () {
    return view('frontend.blogs.index');
})->name('blogs.index');

Route::get('blogs/detail', function () {
    return view('frontend.blogs.detail');
})->name('blogs.detail');




Route::get('password.change', function () {
    return view('auth.password.change');
})->name('password.change');

// user -> verify
// fireauth -> login
// isSeller -> seller

Auth::routes();


Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
Route::post('login/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@handleCallback');
Route::get('/product-detail/{id}', [App\Http\Controllers\Frontend\ProductController::class, 'productDetails']);


Route::group(['middleware' => 'fireauth'], function () {
    // User login
    Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify');
    // Route::get('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'index'])->name('profile.index');
    // Route::put('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');
    Route::resource('profile', App\Http\Controllers\Auth\ProfileController::class);
    

    Route::group(['middleware' => 'user'], function () {
        // verify
        Route::get('/home/iamseller', [App\Http\Controllers\Auth\ProfileController::class, 'makeSeller']);

    });



    Route::group(['middleware' => ['isSeller', 'user'], 'prefix' => 'seller', 'as' => 'seller.'], function () {
        // Seller
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });
});