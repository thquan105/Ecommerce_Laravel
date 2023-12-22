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






// user -> verify
// fireauth -> login
// isSeller -> seller

Auth::routes();


Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
Route::post('login/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@handleCallback');
Route::get('/product-detail/{id}', [App\Http\Controllers\Frontend\ProductController::class, 'productDetails']);
Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');
Route::post('profile/add-email', [App\Http\Controllers\Auth\ProfileController::class, 'add_email'])->name('profile.email')->middleware('fireauth');

Route::group(['middleware' => ['fireauth' ,'user']], function () {
    // User login
    Route::resource('profile', App\Http\Controllers\Auth\ProfileController::class);
    Route::get('/home/iamseller', [App\Http\Controllers\Auth\ProfileController::class, 'makeSeller']);
    Route::post('password/change', [App\Http\Controllers\Auth\ProfileController::class, 'changePassword'])->name('password.change');



    Route::group(['middleware' => ['isSeller'], 'prefix' => 'seller', 'as' => 'seller.'], function () {
        // Seller
        Route::get('dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
    });
});

Route::group(['middleware' => ['isAdmin', 'fireauth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // Seller
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('{category}/subcategories', \App\Http\Controllers\Admin\SubCategoryController::class);
});
