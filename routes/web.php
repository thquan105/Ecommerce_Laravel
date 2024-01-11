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

// Route::get('/product.html', function () {
//     return view('frontend.products.index');
// })->name('products.index');

// Route::get('carts', function () {
//     return view('frontend.carts.index');
// })->name('carts.index');

// Route::get('carts/checkout', function () {
//     return view('frontend.carts.checkout');
// })->name('carts.checkout');

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
Route::get('/product-detail/{id}', [App\Http\Controllers\Frontend\ProductController::class, 'productDetails']);
Route::get('/products', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('products.index');


Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);
Route::post('login/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@handleCallback');
Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');
Route::post('profile/add-email', [App\Http\Controllers\Auth\ProfileController::class, 'add_email'])->name('profile.email')->middleware('fireauth');
Route::post('get-subcategories', [\App\Http\Controllers\Seller\ProductController::class, 'subcategories']);
Route::get('/shop/{id}', [App\Http\Controllers\Frontend\ShopController::class, 'shop_index'])->name('shop.index');
Route::get('/shop/{id}/dm/{idCate}', [App\Http\Controllers\Frontend\ShopController::class, 'shop_category'])->name('shop.category');
Route::get('/shop/{id}/asc', [App\Http\Controllers\Frontend\ShopController::class, 'product_asc'])->name('shop.product_asc');
Route::get('/shop/{id}/desc', [App\Http\Controllers\Frontend\ShopController::class, 'product_desc'])->name('shop.product_desc');
Route::get('/shop/{id}/filter', [App\Http\Controllers\Frontend\ShopController::class, 'product_filter'])->name('shop.product_filter');

Route::group(['middleware' => ['fireauth', 'user']], function () {
    // User login
    Route::resource('profile', App\Http\Controllers\Auth\ProfileController::class);
    Route::put('/home/iamseller', [App\Http\Controllers\Auth\ProfileController::class, 'makeSeller'])->name('profile.makeSeller');
    Route::post('password/change', [App\Http\Controllers\Auth\ProfileController::class, 'changePassword'])->name('password.change');
    Route::post('profile/{profile}', [App\Http\Controllers\Auth\ProfileController::class, 'updateImg'])->name('profile.updateImg');
    Route::get('/carts', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('carts.index');
    Route::post('/carts/store', [\App\Http\Controllers\Frontend\CartController::class, 'addToCart'])->name('carts.store');
    Route::put('/carts/update', [\App\Http\Controllers\Frontend\CartController::class, 'update'])->name('carts.update');
    Route::get('/carts/remove/{cartId}', [\App\Http\Controllers\Frontend\CartController::class, 'destroy']);

    Route::get('carts/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('carts.checkout');
    Route::post('carts/checkout/post', [App\Http\Controllers\Frontend\CheckoutController::class, 'checkout'])->name('carts.confirm.checkout');



    Route::group(['middleware' => ['isSeller'], 'prefix' => 'seller', 'as' => 'seller.'], function () {
        // Seller
        Route::get('dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
        Route::resource('categories', \App\Http\Controllers\Seller\CategoryController::class);
        Route::post('products/images', [\App\Http\Controllers\Seller\ProductController::class, 'storeImage'])->name('products.storeImage');
        Route::resource('orders', \App\Http\Controllers\Seller\OrderController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    });
});

Route::group(['middleware' => ['isAdmin', 'fireauth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // Seller
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('{category}/subcategories', \App\Http\Controllers\Admin\SubCategoryController::class);
    Route::get('sellers', [App\Http\Controllers\Admin\SellerController::class, 'index'])->name('sellers.index');
    Route::get('sellers/{id}/disable', [App\Http\Controllers\Admin\SellerController::class, 'disable'])->name('sellers.disable');
    Route::get('sellers/{id}/enable', [App\Http\Controllers\Admin\SellerController::class, 'enable'])->name('sellers.enable');
    Route::get('sellers/approve', [App\Http\Controllers\Admin\SellerController::class, 'show'])->name('sellers.show');
    Route::get('sellers/{id}/deny', [App\Http\Controllers\Admin\SellerController::class, 'deny'])->name('sellers.deny');
    Route::get('sellers/{id}/accept', [App\Http\Controllers\Admin\SellerController::class, 'accept'])->name('sellers.accept');
    Route::resource('ship', \App\Http\Controllers\Admin\ShipController::class);
});
